<x-dashboard title="Order #{{ $order->order_number }}" active="orders">

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('dashboard.orders') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gold-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            Back to orders
        </a>
        <a href="{{ route('dashboard.orders.invoice', $order) }}" target="_blank" class="btn-outline px-5 py-2 text-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Download Invoice
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 card overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 p-5">
                <h2 class="section-title">Items</h2>
                <x-status-badge :status="$order->status" :color="$order->statusColor()" />
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-4 p-5">
                        <div class="flex items-center gap-4">
                            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gold-500/10 text-sm font-bold text-gold-700">×{{ $item->quantity }}</span>
                            <div>
                                <p class="font-medium text-ink">{{ $item->name }}</p>
                                <p class="text-sm text-gray-500">{{ money($item->price) }} each</p>
                            </div>
                        </div>
                        <p class="font-semibold text-ink">{{ money($item->subtotal) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 bg-gray-50/50 p-5">
                <dl class="ml-auto max-w-xs space-y-1.5 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd class="font-medium text-ink">{{ money($order->subtotal) }}</dd></div>
                    @if($order->shipping_amount > 0)<div class="flex justify-between"><dt class="text-gray-500">Shipping</dt><dd class="font-medium text-ink">{{ money($order->shipping_amount) }}</dd></div>@endif
                    @if($order->tax_amount > 0)<div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd class="font-medium text-ink">{{ money($order->tax_amount) }}</dd></div>@endif
                    <div class="mt-1 flex justify-between border-t border-gray-200 pt-2 text-base font-bold"><dt class="text-ink">Total</dt><dd class="text-brand-700">{{ money($order->total) }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="card h-fit overflow-hidden">
            <div class="border-b border-gray-100 p-5"><h2 class="section-title">Shipping Details</h2></div>
            <dl class="space-y-4 p-5 text-sm">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg></span>
                    <div><dt class="text-xs text-gray-400">Name</dt><dd class="font-medium text-ink">{{ $order->customer_name }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg></span>
                    <div><dt class="text-xs text-gray-400">Phone</dt><dd class="font-medium text-ink">{{ $order->phone }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg></span>
                    <div class="min-w-0"><dt class="text-xs text-gray-400">Email</dt><dd class="truncate font-medium text-ink">{{ $order->email }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg></span>
                    <div><dt class="text-xs text-gray-400">Address</dt><dd class="font-medium text-ink">{{ $order->address }}, {{ $order->city }}{{ $order->state ? ', '.$order->state : '' }}, {{ $order->country }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg></span>
                    <div><dt class="text-xs text-gray-400">Payment</dt><dd class="font-medium text-ink">Cash on Delivery</dd></div>
                </div>
                @if($order->note)
                    <div class="rounded-lg bg-amber-50 p-3 text-sm text-amber-800"><dt class="text-xs font-semibold text-amber-600">Note</dt><dd>{{ $order->note }}</dd></div>
                @endif
            </dl>
        </div>
    </div>

</x-dashboard>
