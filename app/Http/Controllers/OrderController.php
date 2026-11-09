<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function success(Order $order)
    {
        // Only the owner (we log guests in at checkout) may view it.
        if ($order->user_id && Auth::id() !== $order->user_id) {
            abort(403);
        }

        $order->load('items');

        return view('frontend.order-success', compact('order'));
    }
}
