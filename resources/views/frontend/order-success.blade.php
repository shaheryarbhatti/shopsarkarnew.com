<x-storefront title="Order Placed" footer="full" :noindex="true">

    <section class="container-x py-16">
        <div class="mx-auto max-w-2xl">
            <div class="card overflow-hidden text-center">
                {{-- Success banner --}}
                <div class="bg-gradient-to-br from-brand-700 to-brand-900 px-8 py-12 text-white">
                    <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-gold-500/20 ring-4 ring-gold-500/30"
                         x-data x-init="$el.animate([{transform:'scale(.6)',opacity:0},{transform:'scale(1)',opacity:1}],{duration:500,easing:'cubic-bezier(.2,.8,.2,1)'})">
                        <svg class="h-10 w-10 text-gold-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    </div>
                    <h1 class="font-serif text-3xl font-bold text-gold-400">Thank You!</h1>
                    <p class="mt-2 text-white/80">Your order has been placed successfully.</p>
                    <p class="mt-4 inline-block rounded-full bg-white/10 px-4 py-1 text-sm">Order #{{ $order->order_number }}</p>
                </div>

                {{-- Summary --}}
                <div class="px-8 py-8 text-left">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400">Name</p>
                            <p class="font-medium text-ink">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Phone</p>
                            <p class="font-medium text-ink">{{ $order->phone }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-400">Shipping Address</p>
                            <p class="font-medium text-ink">{{ $order->address }}, {{ $order->city }}{{ $order->state ? ', '.$order->state : '' }}, {{ $order->country }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Payment</p>
                            <p class="font-medium text-ink">Cash on Delivery</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Total</p>
                            <p class="font-semibold text-brand-700">{{ money($order->total) }}</p>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item->name }} × {{ $item->quantity }}</span>
                                <span class="font-medium">{{ money($item->subtotal) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-6 border-gray-200">
                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd>{{ money($order->subtotal) }}</dd></div>
                        @if($order->shipping_amount > 0)
                            <div class="flex justify-between"><dt class="text-gray-500">Shipping</dt><dd>{{ money($order->shipping_amount) }}</dd></div>
                        @endif
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd>{{ money($order->tax_amount) }}</dd></div>
                        @endif
                        <div class="flex justify-between pt-1 text-base font-bold"><dt>Total</dt><dd class="text-brand-700">{{ money($order->total) }}</dd></div>
                    </dl>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('dashboard.index') }}" class="btn-gold group flex-1"><span>Go to Dashboard</span></a>
                        <a href="{{ route('home') }}" class="btn-outline flex-1">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-storefront>
