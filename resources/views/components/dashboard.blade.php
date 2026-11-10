@props(['title' => 'Dashboard', 'active' => 'index'])

@php
    $user = auth()->user();
    $isContributor = $user?->is_contributor;

    $nav = [
        ['key' => 'index',    'label' => 'Overview',     'route' => 'dashboard.index',   'icon' => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm9.75 0A2.25 2.25 0 0 1 15.75 3.75H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Zm-9.75 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75 0a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z'],
        ['key' => 'orders',   'label' => 'My Orders',    'route' => 'dashboard.orders',  'icon' => 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12A1.125 1.125 0 0 1 19.75 21H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 6h12.974c.576 0 1.059.435 1.119 1.007Z'],
        ['key' => 'profile',  'label' => 'Profile',      'route' => 'dashboard.profile', 'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0'],
    ];
    $affNav = [
        ['key' => 'earnings',    'label' => 'Earnings',     'route' => 'dashboard.earnings',    'icon' => 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
        ['key' => 'withdrawals', 'label' => 'Withdrawals',  'route' => 'dashboard.withdrawals', 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z'],
        ['key' => 'bank',        'label' => 'Bank Details', 'route' => 'dashboard.bank',        'icon' => 'M3.75 21h16.5M4.5 3h15l-.75 3.75H5.25L4.5 3Zm.75 3.75V21m13.5-14.25V21M8.25 10.5v6.75m3.75-6.75v6.75m3.75-6.75v6.75'],
    ];

    $hour = (int) now()->format('H');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ setting('site_name', 'Shop') }}</title>
    <link rel="icon" href="{{ setting_file('favicon') ?: asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-tracking-scripts />
</head>
<body class="min-h-screen bg-cream" x-data="{ sidebar: false }">

    <x-site-header />

    {{-- Decorative page backdrop --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -left-40 top-20 h-96 w-96 rounded-full bg-brand-500/[0.06] blur-3xl"></div>
        <div class="absolute -right-32 top-80 h-80 w-80 rounded-full bg-gold-500/[0.08] blur-3xl"></div>
    </div>

    <div class="container-x py-8">
        <div class="flex flex-col gap-8 lg:flex-row">

            {{-- Sidebar --}}
            <aside class="lg:w-72 lg:flex-shrink-0">
                <div class="card sticky top-8 overflow-hidden">
                    {{-- Profile header with gradient + decorative pattern --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-brand-800 via-brand-900 to-brand-900 p-6 text-center">
                        <div aria-hidden="true" class="absolute inset-0 opacity-[0.07]"
                             style="background-image: radial-gradient(circle at 1px 1px, #c9a24b 1px, transparent 0); background-size: 16px 16px;"></div>
                        <div aria-hidden="true" class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-gold-500/10 blur-2xl"></div>

                        <div class="relative">
                            <div class="relative mx-auto h-16 w-16">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gold-300 to-gold-600 text-2xl font-bold text-brand-900 shadow-lg shadow-black/20 ring-4 ring-white/10">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="absolute bottom-0.5 right-0.5 h-4 w-4 rounded-full border-2 border-brand-900 bg-emerald-400"></span>
                            </div>
                            <p class="mt-3 font-semibold text-white">{{ $user->name }}</p>
                            <p class="truncate text-xs text-gold-200/60">{{ $user->email }}</p>
                            @if($isContributor)
                                <span class="mt-2.5 inline-flex items-center gap-1 rounded-full bg-gold-500/20 px-3 py-0.5 text-xs font-medium text-gold-300 ring-1 ring-gold-400/30">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z"/></svg>
                                    Contributor
                                </span>
                            @endif
                        </div>
                    </div>

                    <nav class="p-3">
                        @foreach($nav as $item)
                            <x-dash-link :item="$item" :active="$active" />
                        @endforeach

                        @if($isContributor)
                            <p class="px-3 pb-1.5 pt-5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Affiliate</p>
                            @foreach($affNav as $item)
                                <x-dash-link :item="$item" :active="$active" />
                            @endforeach
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t border-gray-100 pt-3">
                            @csrf
                            <button class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50">
                                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 transition group-hover:bg-red-500 group-hover:text-white">
                                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                                </span>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            {{-- Content --}}
            <main class="min-w-0 flex-1">
                {{-- Page header banner --}}
                <div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-brand-900 to-brand-800 px-6 py-5 shadow-lg shadow-brand-900/10">
                    <div aria-hidden="true" class="absolute inset-0 opacity-[0.06]"
                         style="background-image: radial-gradient(circle at 1px 1px, #c9a24b 1px, transparent 0); background-size: 18px 18px;"></div>
                    <div aria-hidden="true" class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-gold-500/10 blur-3xl"></div>
                    <div class="relative flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-gold-300/70">{{ $greeting }}, {{ \Illuminate\Support\Str::of($user->name)->before(' ') }}</p>
                            <h1 class="mt-0.5 font-serif text-2xl font-bold text-white">{{ $title }}</h1>
                        </div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-gold-200 ring-1 ring-white/15 backdrop-blur transition hover:bg-white/15">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 11.2 3.05a1.13 1.13 0 0 1 1.6 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg>
                            Store
                        </a>
                    </div>
                </div>

                {{ $slot }}
            </main>
        </div>
    </div>

    @if(session('success') || session('error'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3500)"
             x-transition.opacity.duration.300ms
             class="fixed bottom-6 right-6 z-[60] flex max-w-sm items-center gap-3 rounded-xl px-5 py-4 text-sm font-medium shadow-2xl
                    {{ session('error') ? 'bg-red-600 text-white' : 'bg-brand-800 text-gold-200' }}">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                @if(session('error'))
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                @endif
            </svg>
            {{ session('success') ?? session('error') }}
        </div>
    @endif
</body>
</html>
