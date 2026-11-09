<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
        ];

        if ($user->is_contributor) {
            $stats += [
                'total_earnings' => $user->totalEarnings(),
                'available_balance' => $user->availableBalance(),
                'pending_earnings' => $user->pendingEarnings(),
                'withdrawn' => $user->withdrawnAmount(),
            ];
        }

        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('dashboard.index', compact('user', 'stats', 'recentOrders'));
    }
}
