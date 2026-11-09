<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $maturityDays = (int) setting('earning_maturity_days', 3);
        $matureBefore = Carbon::now()->subDays($maturityDays);

        $query = Withdrawal::with('user')->latest();

        if ($request->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($request->filter === 'matured') {
            $query->where('status', 'pending')->where('created_at', '<=', $matureBefore);
        } elseif ($request->filter && $request->filter !== 'all') {
            $query->where('status', $request->filter);
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        return view('admin.withdrawals.index', [
            'withdrawals' => $withdrawals,
            'matureBefore' => $matureBefore,
            'maturityDays' => $maturityDays,
        ]);
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('user', 'bankDetail');
        $maturityDays = (int) setting('earning_maturity_days', 3);

        return view('admin.withdrawals.show', [
            'withdrawal' => $withdrawal,
            'isMatured' => $withdrawal->status === 'pending'
                && $withdrawal->created_at->lte(Carbon::now()->subDays($maturityDays)),
        ]);
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,reject,pay'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $status = match ($data['action']) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'pay' => 'paid',
        };

        $withdrawal->update([
            'status' => $status,
            'admin_note' => $data['admin_note'] ?? $withdrawal->admin_note,
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        // When paid, mark matured earnings as withdrawn (up to the amount).
        if ($status === 'paid') {
            $this->consumeEarnings($withdrawal);
        }

        $this->notifyUser($withdrawal, $status);

        return redirect()->route('admin.withdrawals.show', $withdrawal)
            ->with('success', "Withdrawal marked as {$status}.");
    }

    protected function consumeEarnings(Withdrawal $withdrawal): void
    {
        $remaining = (float) $withdrawal->amount;

        $earnings = $withdrawal->user->earnings()
            ->where('status', '!=', 'cancelled')
            ->whereNull('withdrawal_id')
            ->where('matures_at', '<=', now())
            ->orderBy('matures_at')
            ->get();

        foreach ($earnings as $earning) {
            if ($remaining <= 0) {
                break;
            }
            $earning->update(['status' => 'withdrawn', 'withdrawal_id' => $withdrawal->id]);
            $remaining -= (float) $earning->amount;
        }
    }

    protected function notifyUser(Withdrawal $withdrawal, string $status): void
    {
        $email = $withdrawal->user?->email;
        if (! $email || str_ends_with($email, '@contributor.local')) {
            return;
        }

        try {
            Mail::raw(
                "Your withdrawal request of Rs. " . number_format($withdrawal->amount, 0) . " has been {$status}." .
                ($withdrawal->admin_note ? "\n\nNote: {$withdrawal->admin_note}" : ''),
                fn ($m) => $m->to($email)->subject("Withdrawal {$status}")
            );
        } catch (\Throwable $e) {
            // ignore mail errors
        }
    }
}
