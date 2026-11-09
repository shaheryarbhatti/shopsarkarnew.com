<x-admin title="Expenses" active="expenses">

<div x-data="{
    confirm: { open:false, name:'', action:'', title:'Delete Expense?' },
    edit: { open:false, id:null, title:'', category:'', amount:'', spent_on:'', note:'' }
}">

    {{-- Summary cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-800 to-brand-900 p-5 text-white shadow-lg shadow-brand-900/20">
            <div aria-hidden="true" class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gold-500/15 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm text-gold-200/70">Total Expenses (all time)</p>
                    <p class="mt-1 text-3xl font-bold text-gold-400">{{ money($totalAll) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                </span>
            </div>
        </div>
        <div class="card relative overflow-hidden p-5">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">This Month ({{ now()->format('M Y') }})</p>
                    <p class="mt-1 text-3xl font-bold text-amber-500">{{ money($totalMonth) }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Add form --}}
        <div class="card h-fit overflow-hidden lg:sticky lg:top-24">
            <div class="flex items-center gap-3 border-b border-gray-100 p-5">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold-500/10 text-gold-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </span>
                <h2 class="font-serif text-lg font-semibold text-ink">Add Expense</h2>
            </div>
            <form method="POST" action="{{ route('admin.expenses.store') }}" class="space-y-4 p-5">
                @csrf
                <div>
                    <label class="label">Title</label>
                    <input name="title" class="input" required placeholder="e.g. Facebook Ads" value="{{ old('title') }}">
                </div>
                <div>
                    <label class="label">Category</label>
                    <select name="category" class="input">
                        <option value="">— Select —</option>
                        @foreach($categories as $c)
                            <option value="{{ $c }}" @selected(old('category')===$c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Amount (Rs.)</label>
                        <input type="number" step="1" min="0" name="amount" class="input" required placeholder="0" value="{{ old('amount') }}">
                    </div>
                    <div>
                        <label class="label">Date</label>
                        <input type="date" name="spent_on" class="input" required value="{{ old('spent_on', now()->toDateString()) }}">
                    </div>
                </div>
                <div>
                    <label class="label">Note (optional)</label>
                    <textarea name="note" rows="2" class="input" placeholder="Details...">{{ old('note') }}</textarea>
                </div>
                <button class="btn-gold group w-full"><span>Add Expense</span></button>
            </form>
        </div>

        {{-- List --}}
        <div class="lg:col-span-2 card overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 p-5">
                <h2 class="section-title">Expense History</h2>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">{{ $expenses->total() }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/80 text-left text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Expense</th>
                            <th class="px-5 py-3">Category</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenses as $e)
                            <tr class="transition hover:bg-gold-500/[0.04]">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-ink">{{ $e->title }}</p>
                                    @if($e->note)<p class="text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($e->note, 50) }}</p>@endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($e->category)
                                        <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700">{{ $e->category }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $e->spent_on->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 font-semibold text-red-500">− {{ money($e->amount) }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        @can('expenses.manage')
                                            <button type="button" title="Edit" aria-label="Edit"
                                                    @click="edit = { open:true, id: {{ $e->id }}, title: @js($e->title), category: @js($e->category ?? ''), amount: '{{ $e->amount }}', spent_on: '{{ $e->spent_on->toDateString() }}', note: @js($e->note ?? '') }"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gold-600 transition hover:bg-gold-500/10 hover:text-gold-700">
                                                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                            </button>
                                            <button type="button" title="Delete" aria-label="Delete"
                                                    @click="confirm = { open:true, name: @js($e->title), action: @js(route('admin.expenses.destroy', $e)), title:'Delete Expense?' }"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-600">
                                                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-10 text-center text-gray-400">No expenses recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $expenses->links() }}</div>
        </div>
    </div>

    <x-confirm-delete />

    {{-- Edit Expense modal --}}
    <div x-show="edit.open" x-cloak @keydown.escape.window="edit.open = false"
         class="fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div x-show="edit.open" x-transition.opacity.duration.200ms @click="edit.open = false"
             class="absolute inset-0 bg-brand-900/60 backdrop-blur-sm"></div>

        <div x-show="edit.open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90 translate-y-3"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5">
            {{-- Gradient header --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-brand-900 to-brand-800 px-7 py-5">
                <div aria-hidden="true" class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, #c9a24b 1px, transparent 0); background-size: 16px 16px;"></div>
                <div aria-hidden="true" class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-gold-500/15 blur-2xl"></div>
                <div class="relative flex items-center gap-3">
                    <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gold-500/20 text-gold-300 ring-1 ring-gold-400/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                    </span>
                    <div class="flex-1">
                        <h3 class="font-serif text-xl font-bold text-white">Edit Expense</h3>
                        <p class="text-sm text-gold-200/60">Update the details and save your changes</p>
                    </div>
                    <button type="button" @click="edit.open = false" aria-label="Close" class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg text-white/60 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form method="POST" :action="@js(route('admin.expenses.index')) + '/' + edit.id" class="space-y-4 p-7">
                @csrf
                @method('PUT')

                <div>
                    <label class="label">Title</label>
                    <input name="title" class="input" x-model="edit.title" required>
                </div>
                <div>
                    <label class="label">Category</label>
                    <select name="category" class="input" x-model="edit.category">
                        <option value="">— Select —</option>
                        @foreach($categories as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Amount (Rs.)</label>
                        <input type="number" step="1" min="0" name="amount" class="input" x-model="edit.amount" required>
                    </div>
                    <div>
                        <label class="label">Date</label>
                        <input type="date" name="spent_on" class="input" x-model="edit.spent_on" required>
                    </div>
                </div>
                <div>
                    <label class="label">Note (optional)</label>
                    <textarea name="note" rows="2" class="input" x-model="edit.note"></textarea>
                </div>

                <div class="flex gap-3 border-t border-gray-100 pt-5">
                    <button type="button" @click="edit.open = false"
                            class="flex-1 rounded-full border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="btn-gold group flex-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-admin>
