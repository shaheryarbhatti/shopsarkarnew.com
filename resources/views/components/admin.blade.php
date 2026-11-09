@props(['title' => 'Dashboard', 'active' => 'dashboard'])

@php
    $nav = [
        ['section' => null, 'items' => [
            ['key'=>'dashboard','label'=>'Dashboard','route'=>'admin.dashboard','perm'=>'access_admin','icon'=>'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75'],
        ]],
        ['section' => 'Catalog', 'items' => [
            ['key'=>'products','label'=>'Products','route'=>'admin.products.index','perm'=>'products.view','icon'=>'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
            ['key'=>'categories','label'=>'Categories','route'=>'admin.categories.index','perm'=>'categories.manage','icon'=>'M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6a2.25 2.25 0 011.5-2.122'],
        ]],
        ['section' => 'Sales', 'items' => [
            ['key'=>'orders','label'=>'Orders','route'=>'admin.orders.index','perm'=>'orders.view','icon'=>'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12A1.125 1.125 0 0119.75 21H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 6h12.974c.576 0 1.059.435 1.119 1.007z'],
            ['key'=>'customers','label'=>'Customers','route'=>'admin.customers.index','perm'=>'customers.view','icon'=>'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z'],
            ['key'=>'reviews','label'=>'Reviews','route'=>'admin.reviews.index','perm'=>'reviews.view','icon'=>'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
        ]],
        ['section' => 'Affiliate', 'items' => [
            ['key'=>'contributors','label'=>'Contributors','route'=>'admin.contributors.index','perm'=>'contributors.view','icon'=>'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z'],
            ['key'=>'withdrawals','label'=>'Withdrawals','route'=>'admin.withdrawals.index','perm'=>'withdrawals.view','icon'=>'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
        ]],
        ['section' => 'Finance', 'items' => [
            ['key'=>'expenses','label'=>'Expenses','route'=>'admin.expenses.index','perm'=>'expenses.view','icon'=>'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z'],
        ]],

        ['section' => 'Content', 'items' => [
            ['key'=>'pages','label'=>'Pages','route'=>'admin.pages.index','perm'=>'pages.manage','icon'=>'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
        ]],
        ['section' => 'System', 'items' => [
            ['key'=>'settings','label'=>'Settings','route'=>'admin.settings.edit','perm'=>'settings.manage','icon'=>'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.281z','extra'=>'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['key'=>'users','label'=>'Users / Staff','route'=>'admin.users.index','perm'=>'users.manage','icon'=>'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            ['key'=>'roles','label'=>'Roles','route'=>'admin.roles.index','perm'=>'roles.manage','icon'=>'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
        ]],
    ];
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Admin</title>
    <link rel="icon" href="{{ setting_file('favicon') ?: asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef1f0]" x-data="{ open: false }">

    <div class="flex min-h-screen">
        {{-- ============ Sidebar ============ --}}
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-40 flex w-[270px] -translate-x-full flex-col transition-transform duration-300 lg:translate-x-0"
               :class="open && 'translate-x-0'">
            {{-- gold glow --}}
            <div class="pointer-events-none absolute inset-x-0 top-0 h-48"
                 style="background:radial-gradient(70% 90% at 50% 0,rgba(201,162,75,.20),transparent 70%)"></div>

            {{-- Logo --}}
            <div class="relative flex h-[72px] items-center gap-3 px-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gold-400 to-gold-600 font-serif text-lg font-bold text-brand-900 shadow-lg shadow-gold-900/30">
                    {{ strtoupper(substr(setting('site_name','S'),0,1)) }}
                </div>
                <div class="leading-tight">
                    <span class="block font-serif text-lg font-bold tracking-[0.15em] text-gold-300">{{ strtoupper(setting('site_name','SHOP')) }}</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.35em] text-gold-200/40">Admin Panel</span>
                </div>
            </div>

            <div class="relative mx-6 mb-2 h-px bg-gradient-to-r from-transparent via-gold-500/30 to-transparent"></div>

            {{-- Nav --}}
            <nav class="relative flex-1 space-y-0.5 overflow-y-auto px-4 py-3">
                @foreach($nav as $group)
                    @php $visible = collect($group['items'])->filter(fn($i) => $user->can($i['perm'])); @endphp
                    @if($visible->count())
                        @if($group['section'])
                            <p class="px-3 pb-2 pt-5 text-[10px] font-bold uppercase tracking-[0.25em] text-gold-200/45">{{ $group['section'] }}</p>
                        @endif
                        @foreach($visible as $item)
                            @php $isActive = $active === $item['key']; @endphp
                            <a href="{{ route($item['route']) }}"
                               class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200
                                      {{ $isActive
                                          ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-brand-900 shadow-lg shadow-black/20'
                                          : 'text-gray-200 hover:bg-white/10 hover:text-white' }}">
                                @if($isActive)
                                    <span class="absolute -left-4 top-1/2 h-7 w-1.5 -translate-y-1/2 rounded-r-full bg-gold-400 shadow-[0_0_12px_2px_rgba(201,162,75,.6)]"></span>
                                @endif
                                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg transition
                                             {{ $isActive ? 'bg-brand-900/10' : 'bg-white/5 text-gold-200/70 group-hover:bg-gold-500/20 group-hover:text-gold-300' }}">
                                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                                        @isset($item['extra'])<path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['extra'] }}"/>@endisset
                                    </svg>
                                </span>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    @endif
                @endforeach
            </nav>

            {{-- Sidebar footer --}}
            <div class="relative border-t border-white/5 p-4">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-2.5 text-sm font-medium text-gray-200 transition hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5 text-gold-300" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    View Store
                </a>
            </div>
        </aside>

        {{-- Overlay (mobile) --}}
        <div x-show="open" @click="open=false" class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        {{-- ============ Main ============ --}}
        <div class="flex min-h-screen flex-1 flex-col lg:ml-[270px]">
            {{-- Topbar --}}
            <header class="sticky top-0 z-20 flex h-[72px] items-center justify-between border-b border-gray-200/70 bg-white/80 px-4 backdrop-blur-md lg:px-8">
                <div class="flex items-center gap-3">
                    <button @click="open=!open" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="Menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                    </button>
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-wider text-gold-600/80">{{ setting('site_name','Shop') }} Admin</p>
                        <h1 class="font-serif text-xl font-bold leading-tight text-ink">{{ $title }}</h1>
                    </div>
                </div>
                <div class="flex items-center gap-4" x-data="{ menu:false }">
                    <a href="{{ route('home') }}" target="_blank" class="hidden rounded-full bg-brand-50 px-4 py-2 text-sm font-medium text-brand-700 transition hover:bg-brand-100 sm:block">View Site ↗</a>
                    <div class="relative">
                        <button @click="menu=!menu" class="flex items-center gap-2.5 rounded-full py-1 pl-1 pr-3 transition hover:bg-gray-100">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-brand-700 to-brand-900 text-sm font-bold text-gold-400 ring-2 ring-gold-500/30">{{ strtoupper(substr($user->name,0,1)) }}</span>
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-semibold leading-tight text-ink">{{ $user->name }}</span>
                                <span class="block text-[11px] capitalize leading-tight text-gray-400">{{ $user->getRoleNames()->first() }}</span>
                            </span>
                            <svg class="hidden h-4 w-4 text-gray-400 sm:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="menu" @click.outside="menu=false" x-cloak x-transition.origin.top.right
                             class="absolute right-0 mt-2 w-52 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-xl">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="text-sm font-semibold text-ink">{{ $user->name }}</p>
                                <p class="truncate text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('dashboard.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50">My Dashboard</a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="block w-full px-4 py-2.5 text-left text-sm font-medium text-red-500 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 lg:p-8">
                @if(session('success') || session('error'))
                    <div x-data="{show:true}" x-show="show" x-transition
                         class="mb-5 flex items-center gap-3 rounded-xl border px-4 py-3 text-sm shadow-sm
                                {{ session('error') ? 'border-red-100 bg-red-50 text-red-600' : 'border-emerald-100 bg-emerald-50 text-emerald-700' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            @if(session('error'))<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                            @else<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>@endif
                        </svg>
                        <span class="flex-1">{{ session('success') ?? session('error') }}</span>
                        <button @click="show=false" class="opacity-50 hover:opacity-100">✕</button>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    <style>
        [x-cloak]{display:none!important}
        .admin-sidebar{
            background:
                radial-gradient(120% 60% at 0% 0%, rgba(47,107,85,.25), transparent 60%),
                linear-gradient(180deg,#0d2e24 0%,#081e18 55%,#04120d 100%);
            box-shadow: 1px 0 0 rgba(255,255,255,.04), 4px 0 24px rgba(0,0,0,.18);
        }
        .admin-sidebar nav::-webkit-scrollbar{width:5px}
        .admin-sidebar nav::-webkit-scrollbar-thumb{background:rgba(201,162,75,.25);border-radius:9px}
    </style>
</body>
</html>
