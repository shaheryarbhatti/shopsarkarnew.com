<x-admin title="Customers" active="customers">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Customer?' } }">

    <form method="GET" class="mb-5 flex gap-2">
        <input name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="input w-72">
        <button class="btn-dark px-5">Search</button>
    </form>

    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-5">
            <h2 class="section-title">All Customers</h2>
            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">{{ $customers->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Location</th>
                        <th class="px-5 py-3">Orders</th>
                        <th class="px-5 py-3">Total Spent</th>
                        <th class="px-5 py-3">Referred By</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $c)
                        <tr class="transition hover:bg-gold-500/[0.04]">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-700 to-brand-900 text-sm font-bold text-gold-400">{{ strtoupper(substr($c->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="font-medium text-ink">{{ $c->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $c->email }}{{ $c->phone ? ' · '.$c->phone : '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $c->city ? $c->city.', ' : '' }}{{ $c->country ?? '—' }}</td>
                            <td class="px-5 py-3.5"><span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700">{{ $c->orders_count }}</span></td>
                            <td class="px-5 py-3.5 font-semibold text-ink">{{ money($c->orders_total_sum ?? 0) }}</td>
                            <td class="px-5 py-3.5">
                                @if($c->referrer)
                                    <span class="text-emerald-600">{{ $c->referrer->name }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $c->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <x-action-icon type="view" :href="route('admin.customers.show', $c)" label="View customer" color="gold" />
                                    @can('customers.delete')
                                        <button type="button" title="Delete customer" aria-label="Delete customer"
                                                @click="confirm = { open:true, name: @js($c->name), action: @js(route('admin.customers.destroy', $c)), title:'Delete Customer?' }"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-gray-400">No customers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $customers->links() }}</div>
    </div>

    <x-confirm-delete />
</div>

</x-admin>
