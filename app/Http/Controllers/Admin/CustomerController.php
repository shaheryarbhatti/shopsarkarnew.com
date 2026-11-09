<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::role('customer')
            ->when($request->search, function ($q, $s) {
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%");
                });
            })
            ->withCount('orders')
            ->withSum('orders as orders_total_sum', 'total')
            ->with('referrer')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_unless($user->hasRole('customer'), 404);

        $user->load('referrer');
        $orders = $user->orders()->withCount('items')->latest()->paginate(15);

        $stats = [
            'orders' => $user->orders()->count(),
            'spent' => (float) $user->orders()->sum('total'),
            'completed' => $user->orders()->where('status', 'completed')->count(),
        ];

        return view('admin.customers.show', compact('user', 'orders', 'stats'));
    }

    public function destroy(User $user)
    {
        abort_unless($user->hasRole('customer'), 404);

        // Orders keep their history as guest orders (user_id is set to null by the FK).
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
