<x-admin title="Dashboard" active="dashboard">

    {{-- Matured withdrawal alert --}}
    @if($maturedWithdrawals->count())
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-5">
            <svg class="h-6 w-6 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <div class="flex-1">
                <p class="font-semibold text-amber-800">{{ $maturedWithdrawals->count() }} withdrawal request(s) matured</p>
                <p class="text-sm text-amber-700">These requests are older than {{ $maturityDays }} days and are ready to be processed.</p>
                <a href="{{ route('admin.withdrawals.index') }}" class="mt-2 inline-block text-sm font-semibold text-amber-800 underline">Review now →</a>
            </div>
        </div>
    @endif

    {{-- ===== Financial hero cards ===== --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Revenue --}}
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400">Revenue</p>
                    <p class="mt-1.5 text-2xl font-bold text-emerald-600">{{ money($stats['revenue']) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-600 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>
                </span>
            </div>
        </div>

        {{-- Expenses --}}
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-400 to-rose-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400">Expenses</p>
                    <p class="mt-1.5 text-2xl font-bold text-rose-500">{{ money($stats['expenses']) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-400/10 text-rose-500 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 5.206l.776 1.934m0 0 2.432-.811m-2.432.811-.811-2.432"/></svg>
                </span>
            </div>
        </div>

        {{-- Commissions --}}
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400">Commissions</p>
                    <p class="mt-1.5 text-2xl font-bold text-amber-500">{{ money($stats['commissions']) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-500 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                </span>
            </div>
        </div>

        {{-- Net Profit (highlighted) --}}
        @php $profitPositive = $stats['profit'] >= 0; @endphp
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-800 to-brand-900 p-5 text-white shadow-lg shadow-brand-900/20 transition hover:-translate-y-0.5">
            <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full {{ $profitPositive ? 'bg-gold-500/15' : 'bg-rose-500/20' }} blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gold-200/60">Net Profit</p>
                    <p class="mt-1.5 text-2xl font-bold {{ $profitPositive ? 'text-gold-400' : 'text-rose-300' }}">{{ money($stats['profit']) }}</p>
                    <p class="mt-1 inline-flex items-center gap-1 text-[11px] font-medium {{ $profitPositive ? 'text-emerald-300' : 'text-rose-300' }}">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $profitPositive ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/>
                        </svg>
                        {{ $profitPositive ? 'In profit' : 'In loss' }}
                    </p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
            </div>
        </div>
    </div>

    {{-- ===== Profit breakdown ===== --}}
    @php
        $rev = (float) $stats['revenue'];
        $exp = (float) $stats['expenses'];
        $com = (float) $stats['commissions'];
        $prof = (float) $stats['profit'];
        $base = max($rev, $exp + $com, 1);
        $pExp = round($exp / $base * 100, 1);
        $pCom = round($com / $base * 100, 1);
        $pProf = round(max($prof, 0) / $base * 100, 1);
    @endphp
    <div class="card mt-6 p-6">
        <h2 class="section-title">Profit Breakdown</h2>
        <p class="mt-1 text-sm text-gray-500">Net Profit = Revenue − Expenses − Affiliate Commissions</p>

        {{-- proportion bar --}}
        <div class="mt-5 flex h-4 w-full overflow-hidden rounded-full bg-gray-100">
            <div class="h-full bg-gradient-to-r from-gold-400 to-gold-600" style="width: {{ $pProf }}%"></div>
            <div class="h-full bg-gradient-to-r from-rose-400 to-rose-500" style="width: {{ $pExp }}%"></div>
            <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500" style="width: {{ $pCom }}%"></div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div>
                <p class="flex items-center gap-1.5 text-xs text-gray-500"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Revenue</p>
                <p class="mt-1 text-lg font-bold text-ink">{{ money($rev) }}</p>
            </div>
            <div>
                <p class="flex items-center gap-1.5 text-xs text-gray-500"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>Expenses</p>
                <p class="mt-1 text-lg font-bold text-rose-500">− {{ money($exp) }}</p>
            </div>
            <div>
                <p class="flex items-center gap-1.5 text-xs text-gray-500"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Commissions</p>
                <p class="mt-1 text-lg font-bold text-amber-500">− {{ money($com) }}</p>
            </div>
            <div class="border-l border-gray-100 pl-4">
                <p class="flex items-center gap-1.5 text-xs text-gray-500"><span class="h-2.5 w-2.5 rounded-full bg-gold-500"></span>Net Profit</p>
                <p class="mt-1 text-lg font-bold {{ $prof >= 0 ? 'text-brand-700' : 'text-rose-600' }}">{{ money($prof) }}</p>
            </div>
        </div>
    </div>

    {{-- ===== Operational stats ===== --}}
    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
        @foreach([
            ['Total Orders', $stats['orders'], 'brand', 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12A1.125 1.125 0 0 1 19.75 21H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 6h12.974c.576 0 1.059.435 1.119 1.007Z'],
            ['Pending Orders', $stats['pending_orders'], 'amber', 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ['Products', $stats['products'], 'brand', 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
            ['Customers', $stats['customers'], 'brand', 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
            ['Contributors', $stats['contributors'], 'brand', 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
            ['Pending Payouts', $stats['pending_withdrawals'], 'rose', 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z'],
        ] as [$label, $value, $tone, $icon])
            @php
                $toneMap = [
                    'brand' => ['text-ink', 'bg-brand-500/10 text-brand-600'],
                    'amber' => ['text-amber-500', 'bg-amber-400/10 text-amber-500'],
                    'rose'  => ['text-rose-500', 'bg-rose-400/10 text-rose-500'],
                ][$tone];
            @endphp
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-wider text-gray-400">{{ $label }}</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $toneMap[1] }}">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold {{ $toneMap[0] }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Recent orders --}}
        <div class="card overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 p-5">
                <h2 class="section-title">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gold-600 hover:underline">All</a>
            </div>
            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between gap-3 border-b border-gray-50 px-5 py-3.5 text-sm transition hover:bg-gold-500/[0.04]">
                    <span class="font-semibold text-ink">#{{ $order->order_number }}</span>
                    <span class="flex-1 truncate text-gray-500">{{ $order->customer_name }}</span>
                    <span class="font-semibold text-ink">{{ money($order->total) }}</span>
                    <x-status-badge :status="$order->status" :color="$order->statusColor()" />
                </a>
            @empty
                <p class="p-8 text-center text-gray-400">No orders yet.</p>
            @endforelse
        </div>

        {{-- Pending withdrawals --}}
        <div class="card overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 p-5">
                <h2 class="section-title">Pending Withdrawals</h2>
                <a href="{{ route('admin.withdrawals.index') }}" class="text-sm font-medium text-gold-600 hover:underline">All</a>
            </div>
            @forelse($pendingWithdrawals as $w)
                <a href="{{ route('admin.withdrawals.show', $w) }}" class="flex items-center justify-between gap-3 border-b border-gray-50 px-5 py-3.5 text-sm transition hover:bg-gold-500/[0.04]">
                    <span class="font-medium text-ink">{{ $w->user?->name }}</span>
                    <span class="font-semibold text-ink">{{ money($w->amount) }}</span>
                    <span class="text-gray-400">{{ $w->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <div class="flex flex-col items-center p-10 text-center text-gray-400">
                    <svg class="h-10 w-10 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <p class="mt-2">No pending withdrawals.</p>
                </div>
            @endforelse
        </div>
    </div>

</x-admin>
