<x-dashboard title="Earnings" active="earnings">

    @php
        $minWithdraw = (float) setting('withdraw_min_amount', 500);
        $clearDays   = (int) setting('earning_maturity_days', 3);
        $available   = $summary['available'];
        $pending     = $summary['pending'];
        $canWithdraw = $available >= $minWithdraw && $minWithdraw > 0;

        if ($summary['total'] <= 0) {
            $withdrawReason = "You don't have any earnings yet. Share your referral link and earn a commission on every order.";
        } elseif ($available <= 0 && $pending > 0) {
            $withdrawReason = "Your commission is released when an order is completed and becomes available {$clearDays} days after that. ".money($pending)." is still clearing and will be ready to withdraw soon.";
        } else {
            $withdrawReason = "A minimum of ".money($minWithdraw)." is required to request a withdrawal. You currently have ".money($available)." available".($pending > 0 ? ", with ".money($pending)." still clearing." : ".");
        }
    @endphp

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-500 to-brand-700"></div>
            <div class="flex items-start justify-between">
                <div><p class="text-sm text-gray-500">Total Earned</p><p class="mt-1 text-2xl font-bold text-ink">{{ money($summary['total']) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-500/10 text-brand-600 transition group-hover:scale-110"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg></span>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-800 to-brand-900 p-5 text-white shadow-lg shadow-brand-900/20 transition hover:-translate-y-0.5">
            <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gold-500/15 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div><p class="text-sm text-gold-200/70">Available</p><p class="mt-1 text-2xl font-bold text-gold-400">{{ money($summary['available']) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300 transition group-hover:scale-110"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg></span>
            </div>
        </div>
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
            <div class="flex items-start justify-between">
                <div><p class="text-sm text-gray-500">Pending</p><p class="mt-1 text-2xl font-bold text-amber-500">{{ money($summary['pending']) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-500 transition group-hover:scale-110"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg></span>
            </div>
        </div>
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="flex items-start justify-between">
                <div><p class="text-sm text-gray-500">Withdrawn</p><p class="mt-1 text-2xl font-bold text-emerald-600">{{ money($summary['withdrawn']) }}</p></div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-600 transition group-hover:scale-110"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg></span>
            </div>
        </div>
    </div>

    {{-- Referral link --}}
    <div class="card mt-6 overflow-hidden" x-data="{ copied:false, link: @js($user->referralLink()) }">
        <div class="relative overflow-hidden bg-gradient-to-r from-brand-900 to-brand-800 p-6">
            <div aria-hidden="true" class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-gold-500/10 blur-3xl"></div>
            <div class="relative flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg></span>
                <div><h2 class="font-serif text-lg font-semibold text-white">Your Referral Link</h2><p class="text-sm text-gold-200/60">Share it to keep earning commissions.</p></div>
            </div>
            <div class="relative mt-5 flex gap-2">
                <input readonly :value="link" class="flex-1 rounded-xl border border-white/10 bg-white/10 px-4 py-2.5 text-sm font-medium text-white focus:outline-none">
                <button @click="navigator.clipboard.writeText(link); copied=true; setTimeout(()=>copied=false,1500)" class="btn-gold whitespace-nowrap px-5">
                    <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Earnings table --}}
    <div class="card mt-6 overflow-hidden" x-data="{ showMsg: false }">
        <div class="flex items-center justify-between border-b border-gray-100 p-5">
            <h2 class="section-title">Earnings History</h2>
            @if($canWithdraw)
                <a href="{{ route('dashboard.withdrawals') }}" class="btn-gold px-5 py-2 text-sm">Withdraw</a>
            @else
                <button type="button" @click="showMsg = !showMsg" class="btn-gold px-5 py-2 text-sm">Withdraw</button>
            @endif
        </div>

        @unless($canWithdraw)
            <div x-show="showMsg" x-cloak x-transition
                 class="flex items-start gap-3 border-b border-amber-100 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                <div class="flex-1">
                    <p class="font-semibold">Withdrawal not available yet</p>
                    <p class="mt-0.5">{{ $withdrawReason }}</p>
                </div>
                <button type="button" @click="showMsg = false" class="text-amber-500 transition hover:text-amber-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endunless

        @if($earnings->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                        <tr><th class="px-5 py-3">Product</th><th class="px-5 py-3">Order</th><th class="px-5 py-3">Amount</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Available On</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($earnings as $e)
                            @php
                                if ($e->status === 'withdrawn') {
                                    [$badgeLabel, $badgeColor] = ['Paid', 'blue'];
                                } elseif ($e->matures_at && $e->matures_at->lte(now())) {
                                    [$badgeLabel, $badgeColor] = ['Available', 'emerald'];
                                } else {
                                    [$badgeLabel, $badgeColor] = ['Pending', 'amber'];
                                }
                            @endphp
                            <tr class="transition hover:bg-gold-500/[0.04]">
                                <td class="px-5 py-3.5 text-ink">{{ $e->product?->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-500">#{{ $e->order?->order_number ?? '—' }}</td>
                                <td class="px-5 py-3.5 font-semibold text-emerald-600">{{ money($e->amount) }}</td>
                                <td class="px-5 py-3.5"><x-status-badge :status="$badgeLabel" :color="$badgeColor" /></td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $e->matures_at?->format('d M Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $earnings->links() }}</div>
        @else
            <div class="flex flex-col items-center px-6 py-14 text-center">
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300 ring-1 ring-gray-100">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
                <p class="mt-4 font-medium text-gray-600">No earnings yet</p>
                <p class="mt-1 text-sm text-gray-400">Share your referral link to start earning commissions.</p>
            </div>
        @endif
    </div>

</x-dashboard>
