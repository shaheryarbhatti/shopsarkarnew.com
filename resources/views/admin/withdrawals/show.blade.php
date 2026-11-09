<x-admin title="Withdrawal Request" active="withdrawals">

    <a href="{{ route('admin.withdrawals.index') }}" class="mb-5 inline-block text-sm text-gold-600 hover:underline">← Back</a>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Amount Requested</p>
                        <p class="text-3xl font-bold text-brand-700">{{ money($withdrawal->amount) }}</p>
                    </div>
                    <x-status-badge :status="$withdrawal->status" :color="$withdrawal->statusColor()" />
                </div>
                @if($isMatured)
                    <div class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-700">⏱ This request has matured and is ready to process.</div>
                @endif
            </div>

            <div class="card p-6">
                <h2 class="mb-4 font-serif text-lg font-semibold">Payout Details</h2>
                @php $b = $withdrawal->bank_snapshot ?? []; @endphp
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-400">Method</dt><dd class="font-medium capitalize">{{ $b['type'] ?? '—' }}</dd></div>
                    @if(($b['type'] ?? '') === 'bank')
                        <div><dt class="text-gray-400">Account Title</dt><dd class="font-medium">{{ $b['account_title'] ?? '—' }}</dd></div>
                        <div><dt class="text-gray-400">Bank Name</dt><dd class="font-medium">{{ $b['bank_name'] ?? '—' }}</dd></div>
                        <div><dt class="text-gray-400">IBAN</dt><dd class="font-medium">{{ $b['iban'] ?? '—' }}</dd></div>
                    @else
                        <div><dt class="text-gray-400">Account Title</dt><dd class="font-medium">{{ $b['account_title'] ?? '—' }}</dd></div>
                        <div><dt class="text-gray-400">Account Number</dt><dd class="font-medium">{{ $b['account_number'] ?? '—' }}</dd></div>
                    @endif
                </dl>
            </div>

            <div class="card p-6">
                <h2 class="mb-4 font-serif text-lg font-semibold">Contributor</h2>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-400">Name</dt><dd class="font-medium">{{ $withdrawal->user?->name }}</dd></div>
                    <div><dt class="text-gray-400">Email</dt><dd class="font-medium">{{ $withdrawal->user?->email }}</dd></div>
                    <div><dt class="text-gray-400">Phone</dt><dd class="font-medium">{{ $withdrawal->user?->phone }}</dd></div>
                    <div><dt class="text-gray-400">Available Balance</dt><dd class="font-medium">{{ money($withdrawal->user?->availableBalance() ?? 0) }}</dd></div>
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card h-fit p-6">
            <h2 class="mb-4 font-serif text-lg font-semibold">Process</h2>
            @if(in_array($withdrawal->status, ['pending','approved']))
                @can('withdrawals.manage')
                    <form method="POST" action="{{ route('admin.withdrawals.update', $withdrawal) }}" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="label">Note (optional)</label>
                            <textarea name="admin_note" rows="3" class="input">{{ $withdrawal->admin_note }}</textarea>
                        </div>
                        <div class="space-y-2">
                            @if($withdrawal->status === 'pending')
                                <button name="action" value="approve" class="btn-dark w-full">Approve</button>
                            @endif
                            <button name="action" value="pay" class="btn-gold group w-full"><span>Mark as Paid</span></button>
                            <button name="action" value="reject" onclick="return confirm('Reject this request?')" class="btn w-full bg-red-50 text-red-600 hover:bg-red-100">Reject</button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-400">No permission to process withdrawals.</p>
                @endcan
            @else
                <p class="text-sm text-gray-500">This request is <strong>{{ $withdrawal->status }}</strong>.</p>
                @if($withdrawal->processed_at)<p class="mt-1 text-xs text-gray-400">Processed {{ $withdrawal->processed_at->format('d M Y H:i') }}</p>@endif
                @if($withdrawal->admin_note)<p class="mt-3 rounded bg-gray-50 p-3 text-sm text-gray-600">{{ $withdrawal->admin_note }}</p>@endif
            @endif
        </div>
    </div>

</x-admin>
