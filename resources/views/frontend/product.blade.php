<x-storefront
    :title="$product->meta_title ?: $product->name"
    footer="full"
    og-type="product"
    :og-image="$product->thumbnail_url"
    :meta-description="\Illuminate\Support\Str::of(strip_tags($product->meta_description ?: $product->short_description ?: $product->description))->squish()->limit(160)"
    :meta-keywords="$product->name.', buy '.$product->name.' online, '.$product->name.' price in Pakistan, '.($product->category->name ?? 'perfume').', perfume, fragrance, luxury perfume, long lasting perfume Pakistan'">

    <x-slot:jsonld>
        <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@'.'context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => \Illuminate\Support\Str::startsWith($product->thumbnail_url, 'http') ? $product->thumbnail_url : url($product->thumbnail_url),
            'description' => (string) \Illuminate\Support\Str::of(strip_tags($product->short_description ?: $product->description))->squish()->limit(300),
            'sku' => $product->sku,
            'category' => $product->category->name ?? null,
            'brand' => ['@type' => 'Brand', 'name' => setting('site_name', 'ShopSarkar')],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.show', $product->slug),
                'priceCurrency' => 'PKR',
                'price' => number_format((float) $product->final_price, 2, '.', ''),
                'availability' => $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    </x-slot:jsonld>


    <section class="container-x py-10 lg:py-14">

        {{-- Marketing: product view event (TikTok + Facebook) --}}
        <x-track-event event="ViewContent" :value="$product->final_price"
            :contents="[['id' => $product->id, 'name' => $product->name, 'quantity' => 1, 'price' => $product->final_price]]" />

        {{-- Breadcrumb --}}
        <nav class="mb-8 flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gold-600">Home</a>
            <span>/</span>
            @if($product->category)
                <span>{{ $product->category->name }}</span>
                <span>/</span>
            @endif
            <span class="text-ink">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-14"
             x-data="productGallery(@js($media))">

            {{-- ============ LEFT: Gallery ============ --}}
            <div class="lg:sticky lg:top-24 self-start">
                {{-- Main viewer --}}
                <div class="relative aspect-square w-full overflow-hidden rounded-2xl bg-white ring-1 ring-black/5">
                    <template x-for="(m, i) in media" :key="i">
                        <div x-show="active === i" class="absolute inset-0">

                            {{-- Image with hover zoom --}}
                            <template x-if="m.type === 'image'">
                                <div class="zoom-wrap h-full w-full cursor-zoom-in"
                                     @mousemove="onMove" @mouseleave="onLeave">
                                    <img :src="m.src" alt="{{ $product->name }}"
                                         class="h-full w-full object-cover"
                                         :style="(zoom.on && active === i) ? `transform: scale(2); transform-origin: ${zoom.x} ${zoom.y}` : 'transform: scale(1)'">
                                </div>
                            </template>

                            {{-- Uploaded video --}}
                            <template x-if="m.type === 'video'">
                                <video :src="m.src" controls playsinline
                                       :poster="m.poster"
                                       class="h-full w-full bg-black object-contain"></video>
                            </template>

                            {{-- YouTube --}}
                            <template x-if="m.type === 'youtube'">
                                <iframe :src="active === i ? ('https://www.youtube.com/embed/' + m.youtube) : ''"
                                        class="h-full w-full" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </template>
                        </div>
                    </template>

                    {{-- Discount badge --}}
                    @if($product->has_discount)
                        <span class="absolute left-4 top-4 z-10 rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">-{{ $product->discount_percent }}%</span>
                    @endif

                    {{-- Prev / Next --}}
                    <template x-if="media.length > 1">
                        <div>
                            <button @click="prev()" class="absolute left-3 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow hover:bg-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </button>
                            <button @click="next()" class="absolute right-3 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/80 p-2 shadow hover:bg-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Thumbnails --}}
                <template x-if="media.length > 1">
                    <div class="no-scrollbar mt-4 flex gap-3 overflow-x-auto pb-1">
                        <template x-for="(m, i) in media" :key="'t'+i">
                            <button @click="select(i)" type="button"
                                    class="relative h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg ring-1 ring-black/10 transition"
                                    :class="active === i ? 'ring-2 ring-gold-500' : 'opacity-70 hover:opacity-100'">
                                <template x-if="m.thumb">
                                    <img :src="m.thumb" class="h-full w-full object-cover">
                                </template>
                                <template x-if="!m.thumb">
                                    <div class="flex h-full w-full items-center justify-center bg-brand-900"></div>
                                </template>
                                {{-- play icon for video/youtube --}}
                                <template x-if="m.type !== 'image'">
                                    <span class="absolute inset-0 flex items-center justify-center bg-black/30">
                                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </span>
                                </template>
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            {{-- ============ RIGHT: Details ============ --}}
            <div>
                @if($product->category)
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-gold-600">{{ $product->category->name }}</p>
                @endif
                <h1 class="font-serif text-3xl font-bold text-ink lg:text-4xl">{{ $product->name }}</h1>

                {{-- Rating summary (scrolls to reviews) --}}
                @if($product->reviews_count > 0)
                    <a href="#reviews" class="group mt-3 inline-flex items-center gap-2">
                        <x-stars :rating="$product->average_rating" size="h-5 w-5" />
                        <span class="text-sm font-medium text-gray-500 transition group-hover:text-gold-600 group-hover:underline">
                            {{ number_format($product->average_rating, 1) }} · {{ $product->reviews_count }} {{ \Illuminate\Support\Str::plural('review', $product->reviews_count) }}
                        </span>
                    </a>
                @endif

                {{-- Price --}}
                <div class="mt-5 flex items-end gap-3">
                    <span class="text-3xl font-bold text-brand-700">{{ money($product->final_price) }}</span>
                    @if($product->has_discount)
                        <span class="text-lg text-gray-400 line-through">{{ money($product->price) }}</span>
                        <span class="mb-1 rounded bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-600">Save {{ money($product->price - $product->discounted_price) }}</span>
                    @endif
                </div>

                {{-- Stock --}}
                <p class="mt-2 text-sm {{ $product->isInStock() ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $product->isInStock() ? '● In stock' : '● Out of stock' }}
                </p>

                @if($product->short_description)
                    <p class="mt-5 leading-relaxed text-gray-600">{{ $product->short_description }}</p>
                @endif

                <hr class="my-7 border-gray-200">

                {{-- Add to cart --}}
                @if($product->isInStock())
                    {{-- Marketing: AddToCart event helper (TikTok + Facebook) --}}
                    <script>
                        window.sarkarTrackAddToCart = function (qty) {
                            qty = parseInt(qty) || 1;
                            var price = {{ (float) $product->final_price }};
                            var pid = @json((string) $product->id);
                            var tt = { contents: [{ content_id: pid, content_type: 'product', content_name: @json($product->name), quantity: qty, price: price }], value: price * qty, currency: 'PKR' };
                            var fb = { content_ids: [pid], content_type: 'product', contents: [{ id: pid, quantity: qty }], value: price * qty, currency: 'PKR' };
                            try { if (window.ttq) ttq.track('AddToCart', tt); } catch (e) {}
                            try { if (window.fbq) fbq('track', 'AddToCart', fb); } catch (e) {}
                        };
                    </script>
                    <form method="POST" action="{{ route('cart.add') }}" x-data="{ qty: 1 }" class="space-y-4"
                          @submit="window.sarkarTrackAddToCart && window.sarkarTrackAddToCart(qty)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" :value="qty">

                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-4">
                            {{-- Quantity stepper --}}
                            <div class="flex items-center w-full sm:w-auto justify-between sm:justify-center rounded-full border border-gray-300">
                                <button type="button" @click="qty = Math.max(1, qty-1)" class="px-4 py-2.5 text-lg text-gray-500 hover:text-ink">−</button>
                                <span class="w-10 text-center font-semibold" x-text="qty"></span>
                                <button type="button" @click="qty++" class="px-4 py-2.5 text-lg text-gray-500 hover:text-ink">+</button>
                            </div>

                            <button type="submit" name="action" value="cart" class="btn-gold group flex-1 min-w-[180px] px-8 py-3.5 text-base">
                                <span>Add to Cart</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.1-.746 2.364-1.836l1.07-4.42a1.125 1.125 0 0 0-1.09-1.394H6.912m0 0L5.106 5.272"/></svg>
                            </button>
                        </div>

                        {{-- Shop Now → adds to cart and goes straight to checkout --}}
                        <button type="submit" name="action" value="buy" class="flex w-full items-center justify-center gap-2 rounded-full bg-brand-900 px-8 py-3.5 text-base font-semibold text-gold-300 shadow-sm transition hover:bg-brand-800">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/></svg>
                            Shop Now
                        </button>
                    </form>
                @else
                    <button disabled class="btn w-full bg-gray-200 text-gray-500">Out of Stock</button>
                @endif

                {{-- Description --}}
                @if($product->description)
                    <div class="mt-10">
                        <h3 class="mb-4 flex items-center gap-2.5 font-serif text-xl font-semibold text-ink">
                            <span class="h-6 w-1.5 rounded-full bg-gradient-to-b from-gold-400 to-gold-600"></span>
                            Description
                        </h3>
                        <div class="product-desc max-w-none">
                            {!! $product->description !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============ Reviews ============ --}}
        <div id="reviews" class="mt-16 scroll-mt-24 border-t border-gray-200 pt-12">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">

                {{-- Summary + write review --}}
                <div class="lg:col-span-1">
                    <h2 class="font-serif text-2xl font-bold text-ink">Customer Reviews</h2>

                    @if($product->reviews_count > 0)
                        <div class="mt-4 flex items-end gap-3">
                            <span class="text-5xl font-bold leading-none text-ink">{{ number_format($product->average_rating, 1) }}</span>
                            <div class="pb-1">
                                <x-stars :rating="$product->average_rating" size="h-5 w-5" />
                                <p class="mt-1 text-sm text-gray-500">{{ $product->reviews_count }} {{ \Illuminate\Support\Str::plural('review', $product->reviews_count) }}</p>
                            </div>
                        </div>
                        <div class="mt-5 space-y-1.5">
                            @for($i = 5; $i >= 1; $i--)
                                @php $c = $ratingCounts[$i] ?? 0; $pct = $product->reviews_count ? round($c / $product->reviews_count * 100) : 0; @endphp
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-7 text-gray-500">{{ $i }} ★</span>
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full bg-gradient-to-r from-gold-400 to-gold-600" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-6 text-right text-gray-400">{{ $c }}</span>
                                </div>
                            @endfor
                        </div>
                    @else
                        <p class="mt-3 text-gray-500">No reviews yet — be the first to review this product!</p>
                    @endif

                    @auth
                        <div class="mt-6" x-data="{ open: {{ $errors->any() ? 'true' : 'false' }}, rating: {{ old('rating', 5) }} }">
                            <button type="button" @click="open = true" class="btn-gold">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                <span>Write a Review</span>
                            </button>

                            {{-- Review modal --}}
                            <div x-show="open" x-cloak @keydown.escape.window="open = false"
                                 class="fixed inset-0 z-[80] flex items-center justify-center p-4">
                                <div x-show="open" x-transition.opacity.duration.200ms @click="open = false"
                                     class="absolute inset-0 bg-brand-900/60 backdrop-blur-sm"></div>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-90 translate-y-3"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto overflow-x-hidden rounded-3xl bg-white text-left shadow-2xl ring-1 ring-black/5">

                                    {{-- Gradient header --}}
                                    <div class="relative overflow-hidden bg-gradient-to-r from-brand-900 to-brand-800 px-6 py-5">
                                        <div aria-hidden="true" class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-gold-500/15 blur-2xl"></div>
                                        <div class="relative flex items-center gap-3">
                                            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300 ring-1 ring-gold-400/30">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                            </span>
                                            <div class="flex-1">
                                                <h3 class="font-serif text-xl font-bold text-white">Write a Review</h3>
                                                <p class="text-sm text-gold-200/60">Share your experience with {{ $product->name }}</p>
                                            </div>
                                            <button type="button" @click="open = false" aria-label="Close" class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg text-white/60 transition hover:bg-white/10 hover:text-white">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('product.reviews.store', $product->slug) }}"
                                          enctype="multipart/form-data" class="space-y-4 p-6">
                                        @csrf
                                        @if($errors->any())
                                            <div class="rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>
                                        @endif
                                        <div>
                                            <label class="label">Your Rating</label>
                                            <div class="flex gap-1">
                                                <template x-for="i in 5" :key="i">
                                                    <button type="button" @click="rating = i" class="text-4xl leading-none transition" :class="i <= rating ? 'text-gold-500' : 'text-gray-300 hover:text-gold-300'">★</button>
                                                </template>
                                            </div>
                                            <input type="hidden" name="rating" :value="rating">
                                        </div>
                                        <div>
                                            <label class="label">Your Review</label>
                                            <textarea name="body" rows="3" class="input" placeholder="What did you like?">{{ old('body') }}</textarea>
                                        </div>
                                        <div>
                                            <label class="label">Add a Video <span class="font-normal text-gray-400">(optional, max 50MB)</span></label>
                                            <input type="file" name="video" accept="video/mp4,video/quicktime,video/webm" class="input py-2">
                                        </div>
                                        <div class="flex gap-3 border-t border-gray-100 pt-5">
                                            <button type="button" @click="open = false" class="flex-1 rounded-full border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Cancel</button>
                                            <button type="submit" class="btn-gold group flex-1"><span>Submit Review</span></button>
                                        </div>
                                        <p class="text-center text-xs text-gray-400">Your review will appear once approved.</p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline mt-6">Login to write a review</a>
                    @endauth
                </div>

                {{-- Review list --}}
                <div class="lg:col-span-2 space-y-5">
                    @forelse($reviews as $review)
                        <div class="card p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-700 to-brand-900 text-sm font-bold text-gold-400">{{ strtoupper(substr($review->author_name, 0, 1)) }}</span>
                                    <div>
                                        <p class="flex items-center gap-2 font-semibold text-ink">
                                            {{ $review->author_name }}
                                            @if($review->is_verified_buyer)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-600">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                                                    Verified Buyer
                                                </span>
                                            @endif
                                        </p>
                                        <x-stars :rating="$review->rating" class="mt-0.5" />
                                    </div>
                                </div>
                                <span class="whitespace-nowrap text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                            </div>

                            @if($review->body)<p class="mt-3 leading-relaxed text-gray-600">{{ $review->body }}</p>@endif

                            @if($review->youtube_id)
                                <div class="mt-4 aspect-video max-w-md overflow-hidden rounded-xl ring-1 ring-black/5">
                                    <iframe class="h-full w-full" src="https://www.youtube.com/embed/{{ $review->youtube_id }}" title="Review video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @elseif($review->video_url)
                                <video controls playsinline class="mt-4 max-h-96 w-full max-w-md rounded-xl bg-black ring-1 ring-black/5" src="{{ $review->video_url }}"></video>
                            @endif
                        </div>
                    @empty
                        @if($product->reviews_count === 0)
                            <div class="card flex flex-col items-center p-12 text-center text-gray-400">
                                <svg class="h-12 w-12 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                                <p class="mt-3">No reviews yet.</p>
                            </div>
                        @endif
                    @endforelse

                    @if($reviews->hasPages())
                        <div class="pt-2">{{ $reviews->links() }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Related products --}}
        @if($related->count())
            <div class="mt-20">
                <h2 class="mb-8 text-center font-serif text-2xl font-bold text-ink">You May Also Like</h2>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    @foreach($related as $r)
                        <a href="{{ route('product.show', $r->slug) }}" class="card group overflow-hidden">
                            <div class="aspect-square overflow-hidden bg-gray-50">
                                <img src="{{ $r->thumbnail_url }}" alt="{{ $r->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                            <div class="p-4">
                                <h3 class="truncate text-sm font-medium text-ink">{{ $r->name }}</h3>
                                <p class="mt-1 font-semibold text-brand-700">{{ money($r->final_price) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

</x-storefront>
