<x-admin :title="$user->name" active="customers">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Customer?' } }">

    <div class="mb-5 flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gold-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            Back to customers
        </a>
        @can('customers.delete')
            <button type="button"
                    @click="confirm = { open:true, name: @js($user->name), action: @js(route('admin.customers.destroy', $user)), title:'Delete Customer?' }"
                    class="inline-flex items-center gap-2 rounded-full bg-red-50 px-5 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165"/></svg>
                Delete
            </button>
        @endcan
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Profile --}}
        <div class="card h-fit overflow-hidden">
            <div class="relative overflow-hidden bg-gradient-to-br from-brand-800 to-brand-900 p-6 text-center">
                <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gold-500/15 blur-2xl"></div>
                <div class="relative">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gold-300 to-gold-600 text-2xl font-bold text-brand-900 shadow-lg ring-4 ring-white/10">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <p class="mt-3 font-semibold text-white">{{ $user->name }}</p>
                    <p class="text-xs text-gold-200/60">Customer since {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>
            <dl class="space-y-4 p-6 text-sm">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg></span>
                    <div class="min-w-0"><dt class="text-xs text-gray-400">Email</dt><dd class="truncate font-medium text-ink">{{ $user->email }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg></span>
                    <div><dt class="text-xs text-gray-400">Phone</dt><dd class="font-medium text-ink">{{ $user->phone ?? '—' }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg></span>
                    <div><dt class="text-xs text-gray-400">Address</dt><dd class="font-medium text-ink">{{ $user->address ? $user->address.', ' : '' }}{{ $user->city ? $user->city.', ' : '' }}{{ $user->country ?? '—' }}</dd></div>
                </div>
                @if($user->referrer)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg></span>
                        <div><dt class="text-xs text-gray-400">Referred by</dt><dd class="font-medium text-emerald-600">{{ $user->referrer->name }} ({{ $user->referrer->referral_code }})</dd></div>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Stats + orders --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-3 gap-4">
                <div class="card p-5">
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="mt-1 text-2xl font-bold text-ink">{{ $stats['orders'] }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-sm text-gray-500">Completed</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-sm text-gray-500">Total Spent</p>
                    <p class="mt-1 text-2xl font-bold text-gold-600">{{ money($stats['spent']) }}</p>
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="border-b border-gray-100 p-5"><h2 class="section-title">Order History</h2></div>
                @if($orders->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                                <tr><th class="px-5 py-3">Order #</th><th class="px-5 py-3">Date</th><th class="px-5 py-3">Items</th><th class="px-5 py-3">Total</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($orders as $order)
                                    <tr class="transition hover:bg-gold-500/[0.04]">
                                        <td class="px-5 py-3.5 font-semibold text-ink">#{{ $order->order_number }}</td>
                                        <td class="px-5 py-3.5 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-3.5 text-gray-500">{{ $order->items_count }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-ink">{{ money($order->total) }}</td>
                                        <td class="px-5 py-3.5"><x-status-badge :status="$order->status" :color="$order->statusColor()" /></td>
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center justify-end gap-1">
                                                <x-action-icon type="manage" :href="route('admin.orders.show', $order)" label="View order" color="gold" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">{{ $orders->links() }}</div>
                @else
                    <div class="p-10 text-center text-gray-400">No orders from this customer yet.</div>
                @endif
            </div>
        </div>
    </div>

    <x-confirm-delete />
</div>

</x-admin>
