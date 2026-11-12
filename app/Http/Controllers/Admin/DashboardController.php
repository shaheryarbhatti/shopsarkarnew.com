<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $maturityDays = (int) setting('earning_maturity_days', 3);
        $matureBefore = Carbon::now()->subDays($maturityDays);

        $revenue = (float) Order::where('status', 'completed')->sum('total');
        $expenses = (float) Expense::sum('amount');
        $commissions = (float) Earning::where('status', '!=', 'cancelled')->sum('amount');
        $profit = $revenue - $expenses - $commissions;

        $stats = [
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue' => $revenue,
            'expenses' => $expenses,
            'commissions' => $commissions,
            'profit' => $profit,
            'products' => Product::count(),
            'customers' => User::role('customer')->count(),
            'contributors' => User::where('is_contributor', true)->count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
        ];

        // Matured withdrawal requests (pending long enough to be processed)
        $maturedWithdrawals = Withdrawal::with('user')
            ->where('status', 'pending')
            ->where('created_at', '<=', $matureBefore)
            ->latest()
            ->get();

        $recentOrders = Order::latest()->take(6)->get();
        $pendingWithdrawals = Withdrawal::with('user')->where('status', 'pending')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'stats', 'maturedWithdrawals', 'recentOrders', 'pendingWithdrawals', 'maturityDays'
        ));
    }
}
