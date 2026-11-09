<x-admin title="Orders" active="orders">

    <form method="GET" class="mb-5 flex gap-2">
        <input name="search" value="{{ request('search') }}" placeholder="Search order #, name, phone..." class="input w-72">
        <select name="status" class="input w-40" onchange="this.form.submit()">
            <option value="">All status</option>
            @foreach(\App\Models\Order::STATUSES as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="btn-dark px-5">Search</button>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Order #</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Referred By</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-ink">#{{ $order->order_number }}</td>
                            <td class="px-5 py-3">
                                <p class="text-ink">{{ $order->customer_name }}</p>
                                <p class="text-xs text-gray-400">{{ $order->phone }}</p>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-medium">{{ money($order->total) }}</td>
                            <td class="px-5 py-3">
                                @if($order->contributor)
                                    <p class="font-medium text-emerald-600">{{ $order->contributor->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->contributor->email }}</p>
                                    @if($order->contributor->phone)<p class="text-xs text-gray-400">{{ $order->contributor->phone }}</p>@endif
                                    <p class="mt-0.5 inline-block rounded bg-gray-100 px-1.5 py-0.5 text-[11px] font-medium text-gray-500">{{ $order->ref_code }}</p>
                                @elseif($order->ref_code)
                                    <p class="text-xs text-gray-400">{{ $order->ref_code }}</p>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3"><x-status-badge :status="$order->status" :color="$order->statusColor()" /></td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-action-icon type="manage" :href="route('admin.orders.show', $order)" label="Manage order" color="gold" />
                                    <x-action-icon type="external" :href="route('admin.orders.invoice', $order)" label="Download invoice" color="brand" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-gray-400">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>

</x-admin>
