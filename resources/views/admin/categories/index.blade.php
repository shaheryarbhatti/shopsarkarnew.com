<x-admin title="Categories" active="categories">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Category?' } }">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Add form --}}
        <div class="card h-fit p-6">
            <h2 class="mb-4 font-serif text-lg font-semibold">Add Category</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div><label class="label">Name</label><input name="name" class="input" required value="{{ old('name') }}"></div>
                <div><label class="label">Sort Order</label><input type="number" name="sort_order" class="input" value="0"></div>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="rounded text-gold-500"> Active</label>
                <button class="btn-gold group w-full"><span>Add Category</span></button>
            </form>
        </div>

        {{-- List --}}
        <div class="lg:col-span-2 card overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr><th class="px-5 py-3">Name</th><th class="px-5 py-3">Products</th><th class="px-5 py-3">Active</th><th class="px-5 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $cat)
                        <tr x-data="{ edit:false }">
                            <td class="px-5 py-3">
                                <span x-show="!edit" class="font-medium text-ink">{{ $cat->name }}</span>
                                <form x-show="edit" x-cloak method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <input name="name" value="{{ $cat->name }}" class="input py-1.5">
                                    <input type="hidden" name="is_active" value="1">
                                    <button class="btn-dark px-3 py-1.5 text-xs">Save</button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $cat->products_count }}</td>
                            <td class="px-5 py-3">{!! $cat->is_active ? '<span class="text-emerald-600">●</span>' : '<span class="text-gray-300">●</span>' !!}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" @click="edit=!edit" title="Edit" aria-label="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gold-600 transition hover:bg-gold-500/10 hover:text-gold-700">
                                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                    </button>
                                    <button type="button" title="Delete" aria-label="Delete"
                                            @click="confirm = { open:true, name: @js($cat->name), action: @js(route('admin.categories.destroy', $cat)), title:'Delete Category?' }"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-gray-400">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-confirm-delete />
</div>

</x-admin>
