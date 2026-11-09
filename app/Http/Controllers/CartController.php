<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        return back()->with('success', $product->name . ' added to cart.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        cart()->update($data['product_id'], $data['quantity']);

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
