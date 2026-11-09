<x-admin title="Order #{{ $order->order_number }}" active="orders">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Order?' } }">

    <div class="mb-5 flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gold-600 hover:underline">← Back to orders</a>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.invoice', $order) }}" class="btn-outline px-5 py-2 text-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download Invoice
            </a>
            @can('orders.delete')
                <button type="button"
                        @click="confirm = { open:true, name: @js('#'.$order->order_number), action: @js(route('admin.orders.destroy', $order)), title:'Delete Order?' }"
                        class="btn px-5 py-2 text-sm bg-red-50 text-red-600 hover:bg-red-100">Delete</button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            {{-- Items --}}
            <div class="card overflow-hidden">
                <div class="border-b border-gray-100 p-5"><h2 class="font-serif text-lg font-semibold">Items</h2></div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-5">
                            <div>
                                <p class="font-medium text-ink">{{ $item->name }}</p>
                                <p class="text-sm text-gray-500">{{ money($item->price) }} × {{ $item->quantity }}
                                    @if($item->contributor_price > 0)<span class="ml-2 text-emerald-600">(commission {{ money($item->contributor_price) }}/unit)</span>@endif
                                </p>
                            </div>
                            <p class="font-semibold">{{ money($item->subtotal) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 p-5">
                    <dl class="ml-auto max-w-xs space-y-1 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd>{{ money($order->subtotal) }}</dd></div>
                        @if($order->shipping_amount > 0)<div class="flex justify-between"><dt class="text-gray-500">Shipping</dt><dd>{{ money($order->shipping_amount) }}</dd></div>@endif
                        @if($order->tax_amount > 0)<div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd>{{ money($order->tax_amount) }}</dd></div>@endif
                        <div class="flex justify-between border-t border-gray-100 pt-1 text-base font-bold"><dt>Total</dt><dd class="text-brand-700">{{ money($order->total) }}</dd></div>
                    </dl>
                </div>
            </div>

            {{-- Customer --}}
            <div class="card p-5">
                <h2 class="mb-3 font-serif text-lg font-semibold">Customer & Shipping</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><p class="text-gray-400">Name</p><p class="font-medium">{{ $order->customer_name }}</p></div>
                    <div><p class="text-gray-400">Phone</p><p class="font-medium">{{ $order->phone }}</p></div>
                    <div><p class="text-gray-400">Email</p><p class="font-medium">{{ $order->email }}</p></div>
                    <div><p class="text-gray-400">Payment</p><p class="font-medium">Cash on Delivery</p></div>
                    <div class="col-span-2"><p class="text-gray-400">Address</p><p class="font-medium">{{ $order->address }}, {{ $order->city }}{{ $order->state ? ', '.$order->state : '' }}, {{ $order->country }}</p></div>
                    @if($order->note)<div class="col-span-2"><p class="text-gray-400">Note</p><p>{{ $order->note }}</p></div>@endif
                    @if($order->contributor)<div class="col-span-2"><p class="text-gray-400">Referred by</p><p class="font-medium text-emerald-600">{{ $order->contributor->name }} ({{ $order->ref_code }})</p></div>@endif
                </div>
            </div>
        </div>

        {{-- Status panel --}}
        <div class="card h-fit p-6">
            <h2 class="mb-4 font-serif text-lg font-semibold">Update Status</h2>
            @can('orders.edit')
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="label">Order Status</label>
                        <select name="status" class="input">
                            @foreach(\App\Models\Order::STATUSES as $s)
                                <option value="{{ $s }}" @selected($order->status===$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Payment Status</label>
                        <select name="payment_status" class="input">
                            @foreach(['pending'=>'Pending','paid'=>'Paid','failed'=>'Failed'] as $v=>$l)
                                <option value="{{ $v }}" @selected($order->payment_status===$v)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn-gold group w-full"><span>Save</span></button>
                </form>
            @else
                <p class="text-sm text-gray-400">You don't have permission to edit orders.</p>
            @endcan
        </div>
    </div>

    <x-confirm-delete />
</div>

</x-admin>
