<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AffiliateController extends Controller
{
    protected function ensureContributor(): void
    {
        abort_unless(Auth::user()->is_contributor, 403);
    }

    public function earnings()
    {
        $this->ensureContributor();
        $user = Auth::user();

        $earnings = $user->earnings()->with('product', 'order')->latest()->paginate(15);

        return view('dashboard.earnings', [
            'user' => $user,
            'earnings' => $earnings,
            'summary' => [
                'total' => $user->totalEarnings(),
                'available' => $user->availableBalance(),
                'pending' => $user->pendingEarnings(),
                'withdrawn' => $user->withdrawnAmount(),
            ],
        ]);
    }

    public function withdrawals()
    {
        $this->ensureContributor();
        $user = Auth::user();

        return view('dashboard.withdrawals', [
            'user' => $user,
            'withdrawals' => $user->withdrawals()->latest()->paginate(10),
            'available' => $user->availableBalance(),
            'minAmount' => (float) setting('withdraw_min_amount', 500),
            'hasBank' => $user->bankDetails()->exists(),
        ]);
    }

    public function requestWithdrawal(Request $request)
    {
        $this->ensureContributor();
        $user = Auth::user();

        $min = (float) setting('withdraw_min_amount', 500);
        $available = $user->availableBalance();

        // Must have bank details first.
        $bank = $user->defaultBankDetail();
        if (! $bank) {
            return redirect()->route('dashboard.bank')
                ->with('error', 'Please add your payout details before requesting a withdrawal.');
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $min, 'max:' . $available],
        ], [
            'amount.min' => "Minimum withdrawal amount is Rs. {$min}.",
            'amount.max' => 'Amount exceeds your available balance.',
        ]);

        $withdrawal = DB::transaction(function () use ($user, $data, $bank) {
            return Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'status' => 'pending',
                'bank_detail_id' => $bank->id,
                'bank_snapshot' => $bank->only(['type', 'account_number', 'account_title', 'bank_name', 'iban']),
            ]);
        });

        $this->notifyAdmin($user, $withdrawal);

        return redirect()->route('dashboard.withdrawals')
            ->with('success', 'Withdrawal request submitted. It will be processed after review.');
    }

    public function bank()
    {
        $this->ensureContributor();
        $user = Auth::user();

        return view('dashboard.bank', [
            'user' => $user,
            'banks' => $user->bankDetails()->latest()->get(),
        ]);
    }

    public function saveBank(Request $request)
    {
        $this->ensureContributor();
        $user = Auth::user();

        $type = $request->input('type');

        $rules = ['type' => ['required', 'in:easypaisa,jazzcash,bank']];
        if ($type === 'bank') {
            $rules += [
                'iban' => ['required', 'string', 'max:40'],
                'account_title' => ['required', 'string', 'max:255'],
                'bank_name' => ['required', 'string', 'max:255'],
            ];
        } else { // easypaisa / jazzcash
            $rules += ['account_number' => ['required', 'string', 'max:30']];
        }

        $data = $request->validate($rules);

        $payload = [
            'user_id' => $user->id,
            'type' => $data['type'],
            'is_default' => true,
        ];

        if ($type === 'bank') {
            $payload += [
                'iban' => $data['iban'],
                'account_title' => $data['account_title'],
                'bank_name' => $data['bank_name'],
            ];
        } else {
            $payload += [
                'account_number' => $data['account_number'],
                'account_title' => $user->name,
            ];
        }

        DB::transaction(function () use ($user, $payload) {
            $user->bankDetails()->update(['is_default' => false]);
            BankDetail::create($payload);
        });

        return redirect()->route('dashboard.bank')->with('success', 'Payout details saved.');
    }

    protected function notifyAdmin($user, Withdrawal $withdrawal): void
    {
        $adminEmail = setting('contact_email', 'admin@shop.com');

        try {
            Mail::raw(
                "New withdrawal request\n\n" .
                "Contributor: {$user->name} ({$user->email})\n" .
                "Amount: Rs. " . number_format($withdrawal->amount, 0) . "\n" .
                "Method: " . ($withdrawal->bankDetail?->label() ?? 'N/A') . "\n" .
                "Requested at: " . $withdrawal->created_at->format('d M Y H:i'),
                function ($m) use ($adminEmail) {
                    $m->to($adminEmail)->subject('New Withdrawal Request');
                }
            );
        } catch (\Throwable $e) {
            // Mail failures must not block the request.
        }
    }
}
