<?php

namespace App\Http\Controllers;

use App\Models\Earning;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CheckoutController extends Controller
{
    public function index(PricingService $pricing)
    {
        $items = cart()->items();
        $subtotal = cart()->subtotal();
        $totals = $pricing->totals($subtotal);

        return view('frontend.checkout', [
            'items' => $items,
            'totals' => $totals,
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request, PricingService $pricing)
    {
        if (cart()->isEmpty()) {
            return redirect()->route('checkout')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'note' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod'],
        ]);

        $items = cart()->items();
        $subtotal = (float) $items->sum('subtotal');
        $totals = $pricing->totals($subtotal);

        $order = DB::transaction(function () use ($data, $items, $totals, $request) {

            // Resolve or create the buyer account (so they can reach the dashboard).
            $user = Auth::user() ?? $this->resolveUser($data);

            // Resolve the contributor: lifetime attribution → this visit → device cookie.
            $contributor = $this->resolveContributor($user, $request);

            // Lock the contributor onto the customer the first time they order through
            // a referral, so every future order keeps crediting that contributor —
            // even if the customer later returns without the referral link.
            if ($contributor && ! $user->referred_by) {
                $user->forceFill([
                    'referred_by' => $contributor->id,
                    'referred_at' => now(),
                ])->save();
                Cookie::queue('ref_code', $contributor->referral_code, 525600);
            }

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user?->id,
                'contributor_id' => $contributor?->id,
                'ref_code' => $contributor?->referral_code,
                'customer_name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'state' => $data['state'] ?? null,
                'city' => $data['city'],
                'address' => $data['address'],
                'note' => $data['note'] ?? null,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'pending',
                'subtotal' => $totals['subtotal'],
                'shipping_amount' => $totals['shipping'],
                'tax_amount' => $totals['tax'],
                'total' => $totals['total'],
                'currency' => 'PKR',
            ]);

            foreach ($items as $line) {
                $product = $line->product;

                // The contributor commission is snapshotted on the line item, but the
                // earning itself is only created when the order is marked completed
                // (see Admin\OrderController@releaseEarnings).
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $line->price,
                    'quantity' => $line->qty,
                    'subtotal' => $line->subtotal,
                    'contributor_price' => $product->contributor_price,
                ]);

                // Decrement stock
                if ($product->manage_stock) {
                    $product->decrement('stock', $line->qty);
                }
            }

            return $order;
        });

        cart()->clear();
        session()->forget('ref_code');

        return redirect()->route('order.success', $order->order_number);
    }

    /** Find an existing user by email or create a lightweight account and log them in. */
    protected function resolveUser(array $data): User
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            $user = User::create([
                'name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make(Str::random(16)),
                'country' => $data['country'],
                'state' => $data['state'] ?? null,
                'city' => $data['city'],
                'address' => $data['address'],
                'is_active' => true,
            ]);
            $user->assignRole('customer');
        }

        Auth::login($user);

        return $user;
    }

    /**
     * Decide which contributor (if any) earns the commission on this order.
     *
     * Priority:
     *  1. Lifetime attribution — the customer is already tied to a contributor
     *     from a previous order, so they keep earning on every future order.
     *  2. First touch — a referral captured during this visit (session) or saved
     *     on the customer's device (cookie) from an earlier visit.
     */
    protected function resolveContributor(?User $buyer, Request $request): ?User
    {
        // 1) Already attributed to a contributor — honour it for life.
        if ($buyer && $buyer->referred_by) {
            $existing = User::where('id', $buyer->referred_by)
                ->where('is_contributor', true)
                ->first();

            if ($existing && $existing->id !== $buyer->id) {
                return $existing;
            }
        }

        // 2) First-touch referral: this visit, or remembered on the device.
        $code = session('ref_code') ?: $request->cookie('ref_code');
        if (! $code) {
            return null;
        }

        $contributor = User::where('referral_code', $code)->where('is_contributor', true)->first();

        // No self-referral commission.
        if (! $contributor || ($buyer && $contributor->id === $buyer->id)) {
            return null;
        }

        return $contributor;
    }
}
