@props(['variant' => 'full'])

@php
    $name = setting('site_name', 'Shop');
    $year = date('Y');
    $footerLogo = setting_file('footer_logo') ?: setting_file('site_logo');
    $socials = array_filter([
        'facebook'  => setting('social_facebook'),
        'instagram' => setting('social_instagram'),
        'tiktok'    => setting('social_tiktok'),
        'youtube'   => setting('social_youtube'),
    ]);
@endphp

{{-- Reusable elegant social icon row (shown only when at least one link is set) --}}
@php($socialPaths = [
    'facebook'  => 'M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.85c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z',
    'instagram' => 'M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.43.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.43.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.72 3.72 0 0 1-1.38-.9 3.72 3.72 0 0 1-.9-1.38c-.16-.43-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.43-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 3.68A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm6.41-10.4a1.44 1.44 0 1 0 1.44 1.44 1.44 1.44 0 0 0-1.44-1.44Z',
    'tiktok'    => 'M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.08-.14 1.62.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07Z',
    'youtube'   => 'M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.5A3.02 3.02 0 0 0 .5 6.19C0 8.08 0 12 0 12s0 3.92.5 5.81a3.02 3.02 0 0 0 2.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 0 0 2.12-2.14C24 15.92 24 12 24 12s0-3.92-.5-5.81ZM9.55 15.57V8.43L15.82 12l-6.27 3.57Z',
])

@if($variant === 'transparent')
    {{-- Minimal transparent footer for the home / hero page --}}
    <footer class="absolute bottom-0 left-0 z-40 w-full">
        <div class="container-x flex flex-col items-center justify-between gap-2 py-5 text-center sm:flex-row sm:text-left">
            <p class="text-xs text-white/60">© {{ $year }} {{ $name }}. All rights reserved.</p>
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1">
                @foreach($footerPages as $p)
                    <a href="{{ route('page.show', $p->slug) }}" class="text-xs text-white/70 hover:text-gold-400 transition">{{ $p->title }}</a>
                @endforeach
                @if(count($socials))
                    <span class="hidden h-4 w-px bg-white/20 sm:inline-block"></span>
                    @foreach($socials as $net => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($net) }}"
                           class="text-white/70 transition hover:-translate-y-0.5 hover:text-gold-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $socialPaths[$net] }}"/></svg>
                        </a>
                    @endforeach
                @endif
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

                @if(count($socials))
                    <div class="mt-6 flex items-center justify-center gap-3 md:justify-start">
                        @foreach($socials as $net => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($net) }}"
                               class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition duration-200 hover:-translate-y-0.5 hover:border-gold-400 hover:bg-gold-400 hover:text-brand-900 hover:shadow-lg hover:shadow-gold-400/20">
                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $socialPaths[$net] }}"/></svg>
                            </a>
                        @endforeach
                    </div>
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
