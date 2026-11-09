<x-admin title="Withdrawals" active="withdrawals">

    <div class="mb-5 flex flex-wrap gap-2">
        @foreach(['all'=>'All','pending'=>'Pending','matured'=>'Matured','approved'=>'Approved','paid'=>'Paid','rejected'=>'Rejected'] as $key=>$label)
            <a href="{{ route('admin.withdrawals.index', ['filter'=>$key]) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium {{ (request('filter','all')===$key) ? 'bg-brand-900 text-gold-300' : 'bg-white text-gray-600 ring-1 ring-black/5' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Contributor</th>
                        <th class="px-5 py-3">Amount</th>
                        <th class="px-5 py-3">Method</th>
                        <th class="px-5 py-3">Requested</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($withdrawals as $w)
                        @php $matured = $w->status==='pending' && $w->created_at->lte($matureBefore); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <p class="font-medium text-ink">{{ $w->user?->name }}</p>
                                <p class="text-xs text-gray-400">{{ $w->user?->email }}</p>
                            </td>
                            <td class="px-5 py-3 font-semibold">{{ money($w->amount) }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $w->bank_snapshot['type'] ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">
                                {{ $w->created_at->format('d M Y') }}
                                @if($matured)<span class="ml-1 rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-700">MATURED</span>@endif
                            </td>
                            <td class="px-5 py-3"><x-status-badge :status="$w->status" :color="$w->statusColor()" /></td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-action-icon type="review" :href="route('admin.withdrawals.show', $w)" label="Review request" color="gold" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-10 text-center text-gray-400">No withdrawal requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $withdrawals->links() }}</div>
    </div>

</x-admin>
