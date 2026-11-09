<x-dashboard title="Withdrawals" active="withdrawals">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Request form --}}
        <div class="card h-fit overflow-hidden">
            <div class="relative overflow-hidden bg-gradient-to-br from-brand-800 to-brand-900 p-6 text-white">
                <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gold-500/15 blur-2xl"></div>
                <div class="relative">
                    <p class="text-xs uppercase tracking-wider text-gold-200/60">Available to withdraw</p>
                    <p class="mt-1 text-3xl font-bold text-gold-400">{{ money($available) }}</p>
                </div>
            </div>
            <div class="p-6">
                @if(! $hasBank)
                    <div class="flex items-start gap-3 rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                        <span>Add your payout details first.<br><a href="{{ route('dashboard.bank') }}" class="font-semibold text-amber-700 underline">Add bank details →</a></span>
                    </div>
                @elseif($available < $minAmount)
                    <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">You need at least <span class="font-semibold text-ink">{{ money($minAmount) }}</span> available to withdraw.</div>
                @else
                    <form method="POST" action="{{ route('dashboard.withdrawals.request') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="label">Amount (min {{ money($minAmount) }})</label>
                            <input type="number" name="amount" step="1" min="{{ $minAmount }}" max="{{ $available }}" class="input" required value="{{ old('amount', $available) }}">
                            @error('amount')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <button class="btn-gold group w-full"><span>Request Withdrawal</span></button>
                        <p class="text-center text-xs text-gray-400">Processed after admin review.</p>
                    </form>
                @endif
            </div>
        </div>

        {{-- History --}}
        <div class="lg:col-span-2 card overflow-hidden">
            <div class="border-b border-gray-100 p-5"><h2 class="section-title">Withdrawal History</h2></div>
            @if($withdrawals->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                            <tr><th class="px-5 py-3">Date</th><th class="px-5 py-3">Amount</th><th class="px-5 py-3">Method</th><th class="px-5 py-3">Status</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($withdrawals as $w)
                                <tr class="transition hover:bg-gold-500/[0.04]">
                                    <td class="px-5 py-3.5 text-gray-500">{{ $w->created_at->format('d M Y') }}</td>
                                    <td class="px-5 py-3.5 font-semibold text-ink">{{ money($w->amount) }}</td>
                                    <td class="px-5 py-3.5 text-gray-500">{{ $w->bankDetail?->label() ?? ($w->bank_snapshot['type'] ?? '—') }}</td>
                                    <td class="px-5 py-3.5"><x-status-badge :status="$w->status" :color="$w->statusColor()" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $withdrawals->links() }}</div>
            @else
                <div class="flex flex-col items-center px-6 py-14 text-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300 ring-1 ring-gray-100">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                    </span>
                    <p class="mt-4 font-medium text-gray-600">No withdrawals yet</p>
                    <p class="mt-1 text-sm text-gray-400">Your withdrawal requests will appear here.</p>
                </div>
            @endif
        </div>
    </div>

</x-dashboard>
