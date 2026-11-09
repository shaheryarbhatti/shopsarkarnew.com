<x-dashboard title="Overview" active="index">

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 gap-4 {{ $user->is_contributor ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }}">
        {{-- Total Orders --}}
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-500 to-brand-700"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="mt-1 text-3xl font-bold text-ink">{{ $stats['orders'] }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-500/10 text-brand-600 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12A1.125 1.125 0 0 1 19.75 21H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 6h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                </span>
            </div>
        </div>

        {{-- Pending --}}
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="mt-1 text-3xl font-bold text-amber-500">{{ $stats['pending_orders'] }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-500 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
            </div>
        </div>

        {{-- Completed --}}
        <div class="card group relative overflow-hidden p-5 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">Completed</p>
                    <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $stats['completed_orders'] }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-600 transition group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
            </div>
        </div>

        @if($user->is_contributor)
            {{-- Available Balance --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-800 to-brand-900 p-5 text-white shadow-lg shadow-brand-900/20 transition hover:-translate-y-0.5">
                <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gold-500/15 blur-2xl"></div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gold-200/70">Available Balance</p>
                        <p class="mt-1 text-2xl font-bold text-gold-400">{{ money($stats['available_balance'] ?? 0) }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300 transition group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                    </span>
                </div>
            </div>
        @endif
    </div>

    @if($user->is_contributor)
        {{-- Referral link --}}
        <div class="card mt-6 overflow-hidden" x-data="{ copied:false, link: @js($user->referralLink()) }">
            <div class="relative overflow-hidden bg-gradient-to-r from-brand-900 to-brand-800 p-6">
                <div aria-hidden="true" class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-gold-500/10 blur-3xl"></div>
                <div class="relative flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                    </span>
                    <div>
                        <h2 class="font-serif text-lg font-semibold text-white">Your Referral Link</h2>
                        <p class="text-sm text-gold-200/60">Earn a commission on every order placed through it.</p>
                    </div>
                </div>
                <div class="relative mt-5 flex gap-2">
                    <input readonly :value="link" class="flex-1 rounded-xl border border-white/10 bg-white/10 px-4 py-2.5 text-sm font-medium text-white placeholder:text-white/40 focus:outline-none">
                    <button @click="navigator.clipboard.writeText(link); copied=true; setTimeout(()=>copied=false,1500)"
                            class="btn-gold whitespace-nowrap px-5">
                        <svg x-show="!copied" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/></svg>
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-3 divide-x divide-gray-100">
                <div class="p-4 text-center">
                    <p class="text-xs text-gray-500">Total Earned</p>
                    <p class="mt-1 text-lg font-bold text-ink">{{ money($stats['total_earnings'] ?? 0) }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-xs text-gray-500">Pending</p>
                    <p class="mt-1 text-lg font-bold text-amber-500">{{ money($stats['pending_earnings'] ?? 0) }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-xs text-gray-500">Withdrawn</p>
                    <p class="mt-1 text-lg font-bold text-emerald-600">{{ money($stats['withdrawn'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent orders --}}
    <div class="card mt-6 overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-5">
            <h2 class="section-title">Recent Orders</h2>
            <a href="{{ route('dashboard.orders') }}" class="inline-flex items-center gap-1 text-sm font-medium text-gold-600 transition hover:gap-2 hover:text-gold-700">
                View all
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </a>
        </div>
        @if($recentOrders->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                        <tr><th class="px-5 py-3">Order</th><th class="px-5 py-3">Date</th><th class="px-5 py-3">Total</th><th class="px-5 py-3">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentOrders as $order)
                            <tr class="transition hover:bg-gold-500/[0.04]">
                                <td class="px-5 py-3.5"><a href="{{ route('dashboard.orders.show', $order) }}" class="font-semibold text-gold-700 hover:underline">#{{ $order->order_number }}</a></td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 font-semibold text-ink">{{ money($order->total) }}</td>
                                <td class="px-5 py-3.5"><x-status-badge :status="$order->status" :color="$order->statusColor()" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center px-6 py-14 text-center">
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-gray-50 to-gray-100 text-gray-300 ring-1 ring-gray-100">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                </span>
                <p class="mt-4 font-medium text-gray-600">No orders yet</p>
                <p class="mt-1 text-sm text-gray-400">Your orders will show up here once you start shopping.</p>
                <a href="{{ route('home') }}" class="btn-gold mt-5">Start Shopping</a>
            </div>
        @endif
    </div>

</x-dashboard>
