@props([
    'title' => 'Account',
    'heading' => 'Welcome',
    'subheading' => '',
])

@php
    $video = setting_file('hero_video');
    $poster = setting_file('hero_poster');
    $name = setting('site_name', 'SHOP');
    $logo = setting_file('site_logo');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ $name }}</title>
    <link rel="icon" href="{{ setting_file('favicon') ?: asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-tracking-scripts />
</head>
<body class="min-h-screen bg-cream">
    <div class="flex min-h-screen">

        {{-- ============ Brand panel ============ --}}
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-brand-900 p-12 lg:flex">
            {{-- Background media --}}
            <div class="absolute inset-0 z-0">
                @if($video)
                    <video class="h-full w-full object-cover hero-kenburns" autoplay muted loop playsinline @if($poster) poster="{{ $poster }}" @endif>
                        <source src="{{ $video }}" type="video/mp4">
                    </video>
                @elseif($poster)
                    <img src="{{ $poster }}" class="h-full w-full object-cover hero-kenburns" alt="">
                @else
                    <div class="h-full w-full bg-gradient-to-br from-brand-700 via-brand-900 to-black"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-brand-900 via-brand-900/70 to-brand-900/40"></div>
                <div class="hero-glow absolute inset-0" style="background-image:radial-gradient(60% 50% at 30% 20%, rgba(201,162,75,.35), transparent 60%)"></div>
            </div>

            {{-- Top: logo + back --}}
            <div class="relative z-10 flex items-center justify-between">
                <a href="{{ route('home') }}" class="inline-flex items-center">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $name }}" class="h-14 w-auto">
                    @else
                        <span class="font-serif text-2xl font-bold uppercase tracking-[0.25em] text-gold-400">{{ $name }}</span>
                    @endif
                </a>
                <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-sm text-white/70 transition hover:text-gold-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back to store
                </a>
            </div>

            {{-- Center: tagline --}}
            <div class="relative z-10 reveal" style="animation-delay:.1s">
                <div class="mb-6 flex items-center gap-3 text-gold-400/70">
                    <span class="h-px w-10 bg-gold-400/50"></span>
                    <span class="text-xs font-semibold uppercase tracking-[0.3em]">Luxury Fragrances</span>
                </div>
                <h2 class="font-serif text-4xl font-bold leading-tight text-gold-300 xl:text-5xl">The Scent of<br>Eternal Power.</h2>
                <p class="urdu mt-5 text-2xl text-gold-200/90">ریاستی شان اور شاندار وراثہ</p>
            </div>

            {{-- Bottom: trust --}}
            <div class="relative z-10 flex items-center gap-6 text-xs text-white/50">
                <span>✓ Secure checkout</span>
                <span>✓ Nationwide delivery</span>
                <span>✓ Cash on delivery</span>
            </div>
        </div>

        {{-- ============ Form panel ============ --}}
        <div class="relative flex w-full flex-col items-center justify-center px-6 py-12 lg:w-1/2">
            {{-- Mobile: back to store (top) --}}
            <a href="{{ route('home') }}" class="absolute left-5 top-5 inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gold-600 lg:hidden">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Back to store
            </a>
            <div class="w-full max-w-md reveal" style="animation-delay:.15s">

                {{-- Mobile logo --}}
                <a href="{{ route('home') }}" class="mb-10 flex justify-center lg:hidden">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $name }}" class="h-14 w-auto">
                    @else
                        <span class="font-serif text-2xl font-bold uppercase tracking-[0.25em] text-brand-900">{{ $name }}</span>
                    @endif
                </a>

                <div class="mb-8">
                    <h1 class="font-serif text-3xl font-bold text-ink">{{ $heading }}</h1>
                    @if($subheading)<p class="mt-2 text-gray-500">{{ $subheading }}</p>@endif
                </div>

                {{ $slot }}
            </div>

            <p class="mt-10 text-center text-xs text-gray-400">© {{ date('Y') }} {{ $name }}. All rights reserved.</p>
        </div>
    </div>

    @if(session('success') || session('error'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3500)"
             class="fixed bottom-6 right-6 z-[60] rounded-xl px-5 py-4 text-sm font-medium shadow-2xl {{ session('error') ? 'bg-red-600 text-white' : 'bg-brand-800 text-gold-200' }}">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
</body>
</html>
