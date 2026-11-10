<x-storefront title="Checkout" footer="full" :noindex="true">

    <section class="w-full px-4 py-10 sm:px-6 lg:px-12 lg:py-14">

        @if(cart()->isEmpty())
            <div class="card flex flex-col items-center p-16 text-center">
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300 ring-1 ring-gray-100">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                </span>
                <p class="mt-5 text-lg font-medium text-gray-600">Your cart is empty</p>
                <p class="mt-1 text-sm text-gray-400">Add something you love and come back to check out.</p>
                <a href="{{ route('home') }}" class="btn-gold mt-6">Continue Shopping</a>
            </div>
        @else
        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            @csrf

            {{-- ===== LEFT: Billing form ===== --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card overflow-hidden">
                    <div class="flex items-center gap-3 border-b border-gray-100 p-6">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/10 text-gold-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        </span>
                        <div>
                            <h2 class="font-serif text-xl font-semibold text-ink">Billing &amp; Shipping</h2>
                            <p class="text-sm text-gray-500">Where should we deliver your order?</p>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($errors->any())
                            <div class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="label">Full Name *</label>
                                <input name="customer_name" class="input" required value="{{ old('customer_name', $user?->name) }}">
                            </div>
                            <div>
                                <label class="label">Email *</label>
                                <input type="email" name="email" class="input" required value="{{ old('email', $user?->email) }}">
                            </div>
                            <div>
                                <label class="label">Phone *</label>
                                <input name="phone" class="input" required value="{{ old('phone', $user?->phone) }}">
                            </div>
                            <div>
                                <label class="label">Country *</label>
                                <select name="country" class="input">
                                    @foreach(['Pakistan','United Arab Emirates','Saudi Arabia','United Kingdom','United States'] as $c)
                                        <option value="{{ $c }}" @selected(old('country', $user?->country ?? 'Pakistan') === $c)>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label">State / Province</label>
                                <input name="state" class="input" value="{{ old('state', $user?->state) }}">
                            </div>
                            <div>
                                <label class="label">City *</label>
                                <input name="city" class="input" required value="{{ old('city', $user?->city) }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label">Address *</label>
                                <input name="address" class="input" required value="{{ old('address', $user?->address) }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label">Order Note (optional)</label>
                                <textarea name="note" rows="3" class="input" placeholder="Any delivery instructions?">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment method --}}
                <div class="card overflow-hidden">
                    <div class="flex items-center gap-3 border-b border-gray-100 p-6">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/10 text-gold-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                        </span>
                        <h2 class="font-serif text-xl font-semibold text-ink">Payment Method</h2>
                    </div>
                    <div class="p-6">
                        <label class="flex items-center gap-4 rounded-xl border-2 border-gold-400 bg-gradient-to-r from-gold-200/30 to-transparent p-4 shadow-sm">
                            <input type="radio" name="payment_method" value="cod" checked class="text-gold-500 focus:ring-gold-400">
                            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </span>
                            <span class="flex-1">
                                <span class="block font-semibold text-ink">Cash on Delivery</span>
                                <span class="text-sm text-gray-500">Pay with cash when your order arrives.</span>
                            </span>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">Recommended</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: Order summary ===== --}}
            <div class="lg:col-span-1">
                <div class="card sticky top-24 overflow-hidden">
                    <div class="border-b border-gray-100 p-6">
                        <h2 class="section-title">Your Order</h2>
                    </div>

                    <div class="space-y-4 p-6">
                        @foreach($items as $line)
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="{{ $line->product->thumbnail_url }}" class="h-16 w-16 rounded-xl object-cover ring-1 ring-black/5">
                                    <span data-qty-badge="{{ $line->product->id }}" class="absolute -right-2 -top-2 flex h-6 min-w-6 items-center justify-center rounded-full bg-brand-900 px-1.5 text-xs font-bold text-gold-300 ring-2 ring-white">{{ $line->qty }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-ink">{{ $line->product->name }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ money($line->price) }} each</p>
                                    <div class="mt-1.5 flex items-center gap-3">
                                        {{-- Quantity stepper --}}
                                        <div class="flex items-center rounded-full border border-gray-300">
                                            <button type="button" data-minus="{{ $line->product->id }}" onclick="cartQty({{ $line->product->id }}, -1)" @disabled($line->qty <= 1) class="px-2.5 py-1 text-base leading-none text-gray-500 transition hover:text-ink disabled:opacity-40">−</button>
                                            <span data-qty="{{ $line->product->id }}" class="w-7 text-center text-sm font-semibold">{{ $line->qty }}</span>
                                            <button type="button" onclick="cartQty({{ $line->product->id }}, 1)" class="px-2.5 py-1 text-base leading-none text-gray-500 transition hover:text-ink">+</button>
                                        </div>
                                        <button type="button"
                                                onclick="cartRemove({{ $line->product->id }})"
                                                class="inline-flex items-center gap-1 text-xs font-medium text-red-500 transition hover:text-red-600">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <p data-line-subtotal="{{ $line->product->id }}" class="text-sm font-bold text-ink">{{ money($line->subtotal) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 px-6 py-4">
                        <dl class="space-y-2.5 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd data-cart-subtotal class="font-medium text-ink">{{ money($totals['subtotal']) }}</dd></div>

                            @if($totals['shipping_enabled'])
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Shipping</dt>
                                    <dd data-cart-shipping class="font-medium">
                                        @if($totals['free_shipping'] || $totals['shipping'] <= 0)
                                            <span class="text-emerald-600">FREE</span>
                                        @else
                                            <span class="text-ink">{{ money($totals['shipping']) }}</span>
                                        @endif
                                    </dd>
                                </div>
                            @endif

                            @if($totals['tax_enabled'])
                                <div class="flex justify-between"><dt class="text-gray-500">{{ $totals['tax_label'] }} ({{ rtrim(rtrim(number_format($totals['tax_rate'],2),'0'),'.') }}%)</dt><dd data-cart-tax class="font-medium text-ink">{{ money($totals['tax']) }}</dd></div>
                            @endif
                        </dl>
                    </div>

                    {{-- Gradient total + CTA --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-brand-800 to-brand-900 p-6">
                        <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gold-500/15 blur-2xl"></div>
                        <div class="relative flex items-center justify-between">
                            <span class="text-base font-semibold text-white">Total</span>
                            <span data-cart-total class="font-serif text-3xl font-bold text-gold-400">{{ money($totals['total']) }}</span>
                        </div>
                        <button type="submit" class="btn-gold group mt-5 w-full py-3.5 text-base">
                            <span>Place Order</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </button>
                        @guest
                            <p class="mt-3 text-center text-xs text-gold-200/50">An account will be created so you can track your order.</p>
                        @endguest
                    </div>

                    {{-- Trust badges --}}
                    <div class="grid grid-cols-3 divide-x divide-gray-100 border-t border-gray-100 text-center">
                        <div class="flex flex-col items-center gap-1 p-3">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                            <span class="text-[11px] font-medium text-gray-500">Secure</span>
                        </div>
                        <div class="flex flex-col items-center gap-1 p-3">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                            <span class="text-[11px] font-medium text-gray-500">Fast delivery</span>
                        </div>
                        <div class="flex flex-col items-center gap-1 p-3">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <span class="text-[11px] font-medium text-gray-500">COD</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- hidden remove + quantity-update forms --}}
        @foreach($items as $line)
            <form id="remove-{{ $line->product->id }}" method="POST" action="{{ route('cart.remove') }}" class="hidden">
                @csrf @method('DELETE')
                <input type="hidden" name="product_id" value="{{ $line->product->id }}">
            </form>
            <form id="update-{{ $line->product->id }}" method="POST" action="{{ route('cart.update') }}" class="hidden">
                @csrf @method('PATCH')
                <input type="hidden" name="product_id" value="{{ $line->product->id }}">
                <input type="hidden" name="quantity" id="qty-{{ $line->product->id }}" value="{{ $line->qty }}">
            </form>
        @endforeach

        <script>
            function showLoader() { document.getElementById('page-loader')?.classList.add('show'); }
            function hideLoader() { document.getElementById('page-loader')?.classList.remove('show'); }

            // Quantity +/- via AJAX — updates totals instantly, NO page reload.
            function cartQty(id, delta) {
                const span = document.querySelector('[data-qty="' + id + '"]');
                if (!span) return;
                const next = (parseInt(span.textContent, 10) || 1) + delta;
                if (next < 1) return;                       // use Remove to delete

                const form  = document.getElementById('update-' + id);
                const token = form?.querySelector('input[name=_token]')?.value;

                showLoader();
                fetch(form.getAttribute('action'), {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ product_id: id, quantity: next }),
                })
                .then(r => r.ok ? r.json() : Promise.reject(r))
                .then(applyCartUpdate)
                .catch(() => { window.location.reload(); })  // graceful fallback
                .finally(hideLoader);
            }

            function applyCartUpdate(d) {
                if (d.empty) { window.location.reload(); return; }
                const id = d.product_id;
                document.querySelectorAll('[data-qty="' + id + '"], [data-qty-badge="' + id + '"]')
                    .forEach(el => el.textContent = d.qty);
                const minus = document.querySelector('[data-minus="' + id + '"]');
                if (minus) minus.disabled = d.qty <= 1;
                const set = (sel, val) => { const el = document.querySelector(sel); if (el) el.textContent = val; };
                set('[data-line-subtotal="' + id + '"]', d.line_subtotal);
                set('[data-cart-subtotal]', d.subtotal);
                set('[data-cart-total]', d.total);
                if (d.tax_enabled) set('[data-cart-tax]', d.tax);
                const ship = document.querySelector('[data-cart-shipping]');
                if (ship) ship.innerHTML = d.shipping_free
                    ? '<span class="text-emerald-600">FREE</span>'
                    : '<span class="text-ink">' + d.shipping + '</span>';
                document.querySelectorAll('[data-cart-count]').forEach(el => el.textContent = d.count);
            }

            // Remove still reloads (with the loader for feedback).
            function cartRemove(id) {
                showLoader();
                document.getElementById('remove-' + id).submit();
            }
        </script>

        {{-- Marketing: InitiateCheckout event (TikTok + Facebook) --}}
        @php
            $icContents = $items->map(fn ($l) => [
                'id' => $l->product->id, 'name' => $l->product->name,
                'quantity' => $l->qty, 'price' => $l->product->final_price,
            ])->all();
        @endphp
        <x-track-event event="InitiateCheckout" :value="$totals['total']" :contents="$icContents" />
        @endif
    </section>

</x-storefront>
