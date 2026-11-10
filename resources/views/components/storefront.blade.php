@props([
    'title' => null,
    'transparentHeader' => false,
    'footer' => 'full',   // full | transparent | none
    'bodyClass' => '',
    'metaDescription' => null,
    'metaKeywords' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'noindex' => false,
    'jsonld' => null,
])

@php
    $siteName = setting('site_name', 'ShopSarkar');
    $tagline  = setting('tagline', 'Luxury Fragrances & Premium Perfumes');

    $pageTitle = $title
        ? $title . ' — ' . $siteName
        : $siteName . ' — ' . $tagline;

    $desc = $metaDescription
        ?: setting('meta_description', setting('footer_about'))
        ?: 'Shop premium luxury fragrances and perfumes at ' . $siteName . '. Long-lasting scents for men and women with cash on delivery and nationwide delivery across Pakistan.';
    $desc = \Illuminate\Support\Str::of(strip_tags($desc))->squish()->limit(160);

    $keywords = $metaKeywords
        ?: setting('meta_keywords', $siteName . ', perfume, fragrance, luxury perfume, men perfume, women perfume, unisex perfume, long lasting perfume, buy perfume online Pakistan, best perfume in Pakistan, attar, cologne, eau de parfum, premium fragrance, original perfume');

    $ogImg = $ogImage
        ?: setting_file('og_image')
        ?: setting_file('logo')
        ?: setting_file('hero_poster');

    $canonical = url()->current();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ===== Primary SEO ===== --}}
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $desc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="{{ $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large' }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="theme-color" content="#081e18">

    {{-- ===== Open Graph (Facebook / WhatsApp) ===== --}}
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="en_US">
    @if($ogImg)<meta property="og:image" content="{{ \Illuminate\Support\Str::startsWith($ogImg, 'http') ? $ogImg : url($ogImg) }}">@endif

    {{-- ===== Twitter Card ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $desc }}">
    @if($ogImg)<meta name="twitter:image" content="{{ \Illuminate\Support\Str::startsWith($ogImg, 'http') ? $ogImg : url($ogImg) }}">@endif

    <link rel="icon" href="{{ setting_file('favicon') ?: asset('favicon.ico') }}">

    {{-- ===== Organization / Store structured data ===== --}}
    <script type="application/ld+json">
    {!! json_encode(array_filter([
        '@'.'context' => 'https://schema.org',
        '@type' => 'Store',
        'name' => $siteName,
        'url' => url('/'),
        'image' => $ogImg ? (\Illuminate\Support\Str::startsWith($ogImg, 'http') ? $ogImg : url($ogImg)) : null,
        'description' => (string) $desc,
        'telephone' => setting('contact_phone'),
        'email' => setting('contact_email'),
        'address' => setting('contact_address') ? [
            '@type' => 'PostalAddress',
            'addressCountry' => 'PK',
            'streetAddress' => setting('contact_address'),
        ] : null,
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- Page-specific structured data (e.g. Product) --}}
    {!! $jsonld ?? '' !!}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-tracking-scripts />
</head>
<body class="min-h-screen flex flex-col {{ $bodyClass }}" x-data>

    <x-site-header :transparent="$transparentHeader" />

    <main class="flex-1 flex flex-col">
        {{ $slot }}
    </main>

    @if($footer === 'transparent')
        <x-site-footer variant="transparent" />
    @elseif($footer === 'full')
        <x-site-footer variant="full" />
    @endif

    {{-- Flash toast --}}
    @if(session('success') || session('error'))
        @php $isError = (bool) session('error'); @endphp
        <div x-data="{show:false}" x-init="$nextTick(() => show = true); setTimeout(() => show = false, 4000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-24 left-1/2 z-[70] w-[calc(100%-2rem)] max-w-md -translate-x-1/2"
             role="status" aria-live="polite">
            <div class="flex items-center gap-3.5 rounded-2xl px-5 py-4 shadow-2xl ring-1
                        {{ $isError ? 'bg-red-600 text-white ring-red-400/40' : 'bg-brand-800 text-gold-100 ring-gold-400/40' }}">
                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full
                             {{ $isError ? 'bg-white/15' : 'bg-gold-500/20' }}">
                    @if($isError)
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    @else
                        <svg class="h-6 w-6 text-gold-300" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    @endif
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-base font-semibold leading-tight">{{ session('success') ?? session('error') }}</p>
                    @unless($isError)
                        <a href="{{ route('checkout') }}" class="mt-0.5 inline-flex items-center gap-1 text-sm font-medium text-gold-300 underline-offset-2 hover:underline">
                            View cart
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endunless
                </div>
                <button type="button" @click="show = false" class="flex-shrink-0 rounded-full p-1 opacity-70 transition hover:opacity-100" aria-label="Dismiss">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif
</body>
</html>
