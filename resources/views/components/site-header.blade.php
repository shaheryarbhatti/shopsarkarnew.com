@props(['transparent' => false])

@php
    $logo = setting_file('site_logo');
    $name = setting('site_name', 'Shop');
    $count = cart()->count();
    $scrollClass = $transparent
        ? "scrolled ? 'bg-brand-900/95 backdrop-blur shadow-md' : 'bg-transparent'"
        : "''";
@endphp

<header
    x-data="{ scrolled: false, open: false }"
    @scroll.window="scrolled = (window.scrollY > 40)"
    class="top-0 z-50 w-full transition-colors duration-300 {{ $transparent ? 'fixed' : 'sticky bg-brand-900 shadow-md' }}"
    :class="{{ $scrollClass }}">

    <div class="relative container-x flex h-20 items-center justify-between">
        {{-- Logo (left) --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            @if($logo)
                <img src="{{ $logo }}" alt="{{ $name }}" class="h-10 w-auto">
            @else
                <span class="font-serif text-2xl font-bold tracking-[0.2em] text-gold-400 uppercase">{{ $name }}</span>
            @endif
        </a>

        {{-- Nav (centered) --}}
        <nav class="absolute left-1/2 hidden -translate-x-1/2 items-center gap-8 md:flex">
            <a href="{{ route('home') }}" class="text-sm font-medium text-white hover:text-gold-400 transition">Home</a>
            @if(setting_bool('affiliate_enabled', true))
                <a href="{{ route('earn') }}" class="text-sm font-medium text-white hover:text-gold-400 transition">Earn With Us</a>
            @endif
        </nav>

        {{-- Right: account + cart --}}
        <div class="flex items-center gap-5">
            @auth
                <a href="{{ route('dashboard.index') }}" title="My Account" class="text-white hover:text-gold-400 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" title="Login" class="hidden sm:inline text-sm font-medium text-white hover:text-gold-400 transition">Login</a>
            @endauth

            {{-- Cart icon → checkout --}}
            <a href="{{ route('checkout') }}" title="Cart" class="relative text-white hover:text-gold-400 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.1-.746 2.364-1.836l1.07-4.42a1.125 1.125 0 0 0-1.09-1.394H6.912m0 0L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                @if($count > 0)
                    <span data-cart-count class="absolute -right-2 -top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-gold-500 px-1 text-[11px] font-bold text-brand-900">{{ $count }}</span>
                @endif
            </a>

            {{-- Mobile menu toggle --}}
            <button @click="open = !open" class="md:hidden text-white" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile nav --}}
    <div x-show="open" x-collapse class="md:hidden bg-brand-900 border-t border-white/10">
        <div class="container-x flex flex-col gap-1 py-3">
            <a href="{{ route('home') }}" class="py-2 text-white">Home</a>
            @if(setting_bool('affiliate_enabled', true))
                <a href="{{ route('earn') }}" class="py-2 text-white">Earn With Us</a>
            @endif
            @auth
                <a href="{{ route('dashboard.index') }}" class="py-2 text-white">My Account</a>
            @else
                <a href="{{ route('login') }}" class="py-2 text-white">Login</a>
            @endauth
        </div>
    </div>
</header>
