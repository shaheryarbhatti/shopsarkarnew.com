<x-storefront :transparent-header="true" footer="none" body-class="bg-brand-900"
    :meta-description="setting('meta_description', 'ShopSarkar: premium luxury perfumes and long-lasting fragrances for men and women. Shop original scents online with cash on delivery across Pakistan.')">

    @php
        $buyUrl = $heroProduct ? route('product.show', $heroProduct->slug) : route('home');
    @endphp

    <section class="relative flex min-h-screen w-full items-center overflow-hidden bg-brand-900">

        {{-- Background video / fallback --}}
        <div class="absolute inset-0 z-0">
            {{-- Mobile-only poster (portrait) — shown on phones when uploaded --}}
            @if($heroPosterMobile)
                <img src="{{ $heroPosterMobile }}" alt="" class="h-full w-full object-cover hero-kenburns md:hidden">
            @endif

            {{-- Desktop media (also used on mobile when no mobile poster is set) --}}
            <div class="{{ $heroPosterMobile ? 'hidden md:block' : '' }} h-full w-full">
                @if($heroVideo)
                    <video class="h-full w-full object-cover hero-kenburns" autoplay muted loop playsinline
                           @if($heroPoster) poster="{{ $heroPoster }}" @endif>
                        <source src="{{ $heroVideo }}" type="video/mp4">
                    </video>
                @elseif($heroPoster)
                    <img src="{{ $heroPoster }}" alt="" class="h-full w-full object-cover hero-kenburns">
                @else
                    {{-- Elegant fallback gradient when no video uploaded yet --}}
                    <div class="h-full w-full bg-gradient-to-br from-brand-800 via-brand-900 to-black"></div>
                    <div class="hero-glow absolute inset-0"
                         style="background-image:radial-gradient(circle at 70% 40%, rgba(201,162,75,.6), transparent 55%)"></div>
                @endif
            </div>
            {{-- Readability overlay (darker on the left where the text sits) --}}
            <div class="absolute inset-0 bg-gradient-to-r from-brand-900/90 via-brand-900/60 to-transparent"></div>
        </div>

        {{-- Content (centered on mobile, left-aligned on desktop) --}}
        <div class="container-x relative z-10 w-full">
            <div class="mx-auto max-w-2xl py-28 text-center lg:mx-0 lg:text-left">
                <p class="reveal mb-5 text-xs font-semibold uppercase tracking-[0.35em] text-gold-400/90" style="animation-delay:.15s">
                    {{ setting('hero_eyebrow', setting('site_name', 'Shop')) }}
                </p>

                <div x-data="heroHeading" x-init="fit()" class="reveal mx-auto w-fit lg:mx-0" style="animation-delay:.3s">
                    <h1 x-ref="en" class="font-serif text-4xl font-bold leading-[1.1] text-gold-400 sm:text-5xl lg:text-6xl whitespace-pre-line w-fit">{{ setting('hero_heading_en') }}</h1>

                    @if(setting('hero_heading_ur'))
                        <p x-ref="ur" class="urdu mt-5 text-2xl font-semibold text-gold-200 sm:text-5xl"
                           style="text-align: justify; text-align-last: justify; font-weight: 600;">{{ setting('hero_heading_ur') }}</p>
                    @endif
                </div>

                <div class="reveal mt-7 space-y-1" style="animation-delay:.5s">
                    @if(setting('hero_subtext_en'))
                        <p class="mx-auto max-w-md text-base text-white/80 lg:mx-0">{{ setting('hero_subtext_en') }}</p>
                    @endif
                    @if(setting('hero_subtext_ur'))
                        <p class="urdu mx-auto max-w-md text-lg text-white/70 lg:mx-0">{{ setting('hero_subtext_ur') }}</p>
                    @endif
                </div>

                <div class="reveal mt-10 flex flex-wrap items-center justify-center gap-4 lg:justify-start" style="animation-delay:.65s">
                    <a href="{{ $buyUrl }}" class="btn-gold pulse-cta cta-shake-mobile group text-base px-9 py-3.5">
                        <span>{{ setting('hero_button_text', 'Buy Now') }}</span>
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                    @if($heroProduct && setting('hero_button_text_ur'))
                        <span class="urdu text-gold-300/80 text-lg">{{ setting('hero_button_text_ur') }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Transparent footer lives inside the hero --}}
        <x-site-footer variant="transparent" />
    </section>

</x-storefront>
