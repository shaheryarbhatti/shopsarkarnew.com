<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->manage_stock && $product->stock <= 0) {
            return back()->with('error', 'This product is out of stock.');
        }

        cart()->add($product->id, (int) ($data['quantity'] ?? 1));

        // "Shop Now" adds the item and goes straight to checkout.
        if ($request->input('action') === 'buy') {
            return redirect()->route('checkout');
        }

        return back()->with('cart_added', [
            'name' => $product->name,
            'image' => $product->thumbnail_url,
            'qty' => (int) ($data['quantity'] ?? 1),
            'price' => $product->final_price,
        ]);
    }

    public function update(Request $request, PricingService $pricing)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        cart()->update($data['product_id'], $data['quantity']);

        // AJAX (quantity +/- on checkout): return fresh totals, no page reload.
        if ($request->wantsJson()) {
            $items  = cart()->items();
            $line   = $items->first(fn ($l) => $l->product->id == $data['product_id']);
            $totals = $pricing->totals(cart()->subtotal());

            return response()->json([
                'product_id'    => (int) $data['product_id'],
                'qty'           => $line?->qty ?? 0,
                'line_subtotal' => money($line?->subtotal ?? 0),
                'subtotal'      => money($totals['subtotal']),
                'shipping_free' => $totals['free_shipping'] || $totals['shipping'] <= 0,
                'shipping'      => money($totals['shipping']),
                'tax'           => money($totals['tax']),
                'tax_enabled'   => $totals['tax_enabled'],
                'total'         => money($totals['total']),
                'count'         => cart()->count(),
                'empty'         => cart()->isEmpty(),
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        cart()->remove($data['product_id']);

        return back()->with('success', 'Item removed from cart.');
    }
}
