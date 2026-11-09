<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);

        return view('dashboard.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load('items');

        return view('dashboard.order-detail', compact('order'));
    }

    public function invoice(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load('items');

        $pdf = Pdf::loadView('dashboard.invoice', compact('order'));

        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }

    protected function authorizeOrder(Order $order): void
    {
        abort_unless($order->user_id === Auth::id(), 403);
    }
}
