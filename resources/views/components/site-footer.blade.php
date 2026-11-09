@props(['variant' => 'full'])

@php
    $name = setting('site_name', 'Shop');
    $year = date('Y');
    $footerLogo = setting_file('footer_logo') ?: setting_file('site_logo');
@endphp

@if($variant === 'transparent')
    {{-- Minimal transparent footer for the home / hero page --}}
    <footer class="absolute bottom-0 left-0 z-40 w-full">
        <div class="container-x flex flex-col items-center justify-between gap-2 py-5 text-center sm:flex-row sm:text-left">
            <p class="text-xs text-white/60">© {{ $year }} {{ $name }}. All rights reserved.</p>
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1">
                @foreach($footerPages as $p)
                    <a href="{{ route('page.show', $p->slug) }}" class="text-xs text-white/70 hover:text-gold-400 transition">{{ $p->title }}</a>
                @endforeach
            </div>
        </div>
    </footer>
@else
    {{-- Full footer for interior pages --}}
    <footer class="mt-20 bg-brand-900 text-white/85">
        <div class="container-x grid grid-cols-1 gap-10 py-14 text-center md:grid-cols-4 md:text-left">
            <div class="md:col-span-2">
                @if($footerLogo)
                    <img src="{{ $footerLogo }}" alt="{{ $name }}" class="mx-auto h-12 w-auto md:mx-0">
                @else
                    <span class="font-serif text-2xl font-bold uppercase tracking-[0.2em] text-gold-400">{{ $name }}</span>
                @endif
                <p class="mx-auto mt-4 max-w-sm text-sm leading-relaxed text-white/70 md:mx-0">{{ setting('footer_about') }}</p>
                @if(setting('contact_email'))
                    <p class="mt-4 text-sm">✉ {{ setting('contact_email') }}</p>
                @endif
                @if(setting('contact_phone'))
                    <p class="text-sm">✆ {{ setting('contact_phone') }}</p>
                @endif
            </div>

            <div>
                <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gold-400">Shop</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition">Home</a></li>
                    @if(setting_bool('affiliate_enabled', true))
                        <li><a href="{{ route('earn') }}" class="hover:text-gold-400 transition">Earn With Us</a></li>
                    @endif
                    <li><a href="{{ route('checkout') }}" class="hover:text-gold-400 transition">Cart</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-gold-400 transition">My Account</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gold-400">Information</h4>
                <ul class="space-y-2 text-sm">
                    @foreach($footerPages as $p)
                        <li><a href="{{ route('page.show', $p->slug) }}" class="hover:text-gold-400 transition">{{ $p->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="container-x py-5 text-center text-xs text-white/60">
                © {{ $year }} {{ $name }}. All rights reserved.
            </div>
        </div>
    </footer>
@endif
