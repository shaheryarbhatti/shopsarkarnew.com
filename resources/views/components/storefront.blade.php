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
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3500)"
             class="fixed bottom-6 right-6 z-[60] max-w-sm rounded-xl px-5 py-4 text-sm font-medium shadow-2xl
                    {{ session('error') ? 'bg-red-600 text-white' : 'bg-brand-800 text-gold-200' }}">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
</body>
</html>
