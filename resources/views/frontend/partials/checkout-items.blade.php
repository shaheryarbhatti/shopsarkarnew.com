{{-- Editable order line items (shared by mobile-top block and desktop summary card) --}}
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
