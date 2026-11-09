<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function index(Request $request)
    {
        $contributors = User::where('is_contributor', true)
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")->orWhere('referral_code', 'like', "%{$s}%"))
            ->withCount('earnings')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contributors.index', compact('contributors'));
    }

    public function show(User $user)
    {
        abort_unless($user->is_contributor, 404);

        $earnings = $user->earnings()->with('product', 'order')->latest()->take(20)->get();
        $withdrawals = $user->withdrawals()->latest()->take(10)->get();

        return view('admin.contributors.show', [
            'contributor' => $user,
            'earnings' => $earnings,
            'withdrawals' => $withdrawals,
            'summary' => [
                'total' => $user->totalEarnings(),
                'available' => $user->availableBalance(),
                'pending' => $user->pendingEarnings(),
                'withdrawn' => $user->withdrawnAmount(),
            ],
        ]);
    }
}
