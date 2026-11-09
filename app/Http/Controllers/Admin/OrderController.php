<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Order;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user', 'contributor')
            ->when($request->search, fn ($q, $s) => $q->where('order_number', 'like', "%{$s}%")->orWhere('customer_name', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%"))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items', 'user', 'contributor');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
            'payment_status' => ['nullable', 'in:pending,paid,failed'],
        ]);

        $order->update([
            'status' => $data['status'],
            'payment_status' => $data['payment_status'] ?? $order->payment_status,
        ]);

        // Contributor commission is released only when the order is completed,
        // and the withdrawal maturity window starts counting from that moment.
        if ($data['status'] === 'completed') {
            $this->releaseEarnings($order);
        } elseif ($data['status'] === 'cancelled') {
            // Cancelled order → void any commission already released.
            $order->earnings()->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Order status updated.');
    }

    /**
     * Create the contributor's earnings the first time an order is completed.
     * The maturity (clearing) window starts now, from the completion date.
     */
    protected function releaseEarnings(Order $order): void
    {
        // Only for referred orders, and only once (ignore previously cancelled rows).
        if (! $order->contributor_id || $order->earnings()->where('status', '!=', 'cancelled')->exists()) {
            return;
        }

        $maturityDays = max(0, (int) setting('earning_maturity_days', 3));
        $maturesAt = $maturityDays > 0 ? now()->addDays($maturityDays) : now();

        foreach ($order->items as $item) {
            if ($item->contributor_price > 0) {
                Earning::create([
                    'user_id' => $order->contributor_id,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'amount' => (float) $item->contributor_price * $item->quantity,
                    'status' => 'pending',
                    'matures_at' => $maturesAt,
                ]);
            }
        }
    }

    public function invoice(Order $order)
    {
        $order->load('items');
        $pdf = Pdf::loadView('dashboard.invoice', compact('order'));

        // Force a file download so the browser saves the PDF without navigating
        // away — the admin stays on the page they were viewing.
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            // Capture withdrawals that consumed this order's commissions (before deleting earnings).
            $withdrawalIds = $order->earnings()
                ->whereNotNull('withdrawal_id')
                ->pluck('withdrawal_id')
                ->unique();

            // Remove the affiliate commissions earned from this order, the line items, then the order.
            $order->earnings()->delete();
            $order->items()->delete();
            $order->delete();

            // Remove the related withdrawal records too.
            if ($withdrawalIds->isNotEmpty()) {
                Withdrawal::whereIn('id', $withdrawalIds)->delete();
            }
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order, its earnings and related withdrawals deleted.');
    }
}
