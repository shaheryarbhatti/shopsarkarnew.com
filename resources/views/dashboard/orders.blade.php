<x-dashboard title="My Orders" active="orders">

    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-5">
            <h2 class="section-title">Order History</h2>
            @if($orders->count())
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">{{ $orders->total() }} total</span>
            @endif
        </div>
        @if($orders->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Order #</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Items</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="transition hover:bg-gold-500/[0.04]">
                                <td class="px-5 py-3.5"><a href="{{ route('dashboard.orders.show', $order) }}" class="font-semibold text-gold-700 hover:underline">#{{ $order->order_number }}</a></td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $order->items()->count() }}</td>
                                <td class="px-5 py-3.5 font-semibold text-ink">{{ money($order->total) }}</td>
                                <td class="px-5 py-3.5"><x-status-badge :status="$order->status" :color="$order->statusColor()" /></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('dashboard.orders.show', $order) }}" class="inline-flex items-center gap-1 rounded-lg bg-gold-500/10 px-3 py-1.5 text-xs font-medium text-gold-700 transition hover:bg-gold-500/20">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                            View
                                        </a>
                                        <a href="{{ route('dashboard.orders.invoice', $order) }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-brand-500/10 px-3 py-1.5 text-xs font-medium text-brand-700 transition hover:bg-brand-500/20">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                            Invoice
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $orders->links() }}</div>
        @else
            <div class="flex flex-col items-center px-6 py-14 text-center">
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300 ring-1 ring-gray-100">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                </span>
                <p class="mt-4 font-medium text-gray-600">You haven't placed any orders yet</p>
                <p class="mt-1 text-sm text-gray-400">Browse the store and your orders will appear here.</p>
                <a href="{{ route('home') }}" class="btn-gold mt-5">Start Shopping</a>
            </div>
        @endif
    </div>

</x-dashboard>
