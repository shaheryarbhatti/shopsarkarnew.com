<x-admin title="{{ $contributor->name }}" active="contributors">

    <a href="{{ route('admin.contributors.index') }}" class="mb-5 inline-block text-sm text-gold-600 hover:underline">← Back</a>

    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="card p-5"><p class="text-xs uppercase text-gray-400">Total Earned</p><p class="mt-1 text-xl font-bold">{{ money($summary['total']) }}</p></div>
        <div class="card bg-brand-900 p-5 text-white"><p class="text-xs uppercase text-gold-200/60">Available</p><p class="mt-1 text-xl font-bold text-gold-400">{{ money($summary['available']) }}</p></div>
        <div class="card p-5"><p class="text-xs uppercase text-gray-400">Pending</p><p class="mt-1 text-xl font-bold text-amber-500">{{ money($summary['pending']) }}</p></div>
        <div class="card p-5"><p class="text-xs uppercase text-gray-400">Withdrawn</p><p class="mt-1 text-xl font-bold text-emerald-600">{{ money($summary['withdrawn']) }}</p></div>
    </div>

    <div class="card mb-6 p-5">
        <div class="grid grid-cols-2 gap-4 text-sm lg:grid-cols-4">
            <div><p class="text-gray-400">Email</p><p class="font-medium">{{ $contributor->email }}</p></div>
            <div><p class="text-gray-400">Phone</p><p class="font-medium">{{ $contributor->phone }}</p></div>
            <div><p class="text-gray-400">Referral Code</p><code class="rounded bg-gray-100 px-2 py-0.5">{{ $contributor->referral_code }}</code></div>
            <div><p class="text-gray-400">Joined</p><p class="font-medium">{{ $contributor->created_at->format('d M Y') }}</p></div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card overflow-hidden">
            <div class="border-b border-gray-100 p-5"><h2 class="font-serif text-lg font-semibold">Recent Earnings</h2></div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @forelse($earnings as $e)
                        <tr><td class="px-5 py-3">{{ $e->product?->name ?? '—' }}</td><td class="px-5 py-3 text-gray-400">#{{ $e->order?->order_number }}</td><td class="px-5 py-3 text-right font-medium text-emerald-600">{{ money($e->amount) }}</td></tr>
                    @empty
                        <tr><td class="p-8 text-center text-gray-400">No earnings.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card overflow-hidden">
            <div class="border-b border-gray-100 p-5"><h2 class="font-serif text-lg font-semibold">Withdrawals</h2></div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @forelse($withdrawals as $w)
                        <tr><td class="px-5 py-3">{{ $w->created_at->format('d M Y') }}</td><td class="px-5 py-3 font-medium">{{ money($w->amount) }}</td><td class="px-5 py-3 text-right"><x-status-badge :status="$w->status" :color="$w->statusColor()" /></td></tr>
                    @empty
                        <tr><td class="p-8 text-center text-gray-400">No withdrawals.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin>
