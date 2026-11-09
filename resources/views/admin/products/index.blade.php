<x-admin title="Products" active="products">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Product?' } }">

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="Search products..." class="input w-56">
            <select name="status" class="input w-36" onchange="this.form.submit()">
                <option value="">All status</option>
                @foreach(['active','inactive','draft'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn-dark px-5">Search</button>
        </form>
        @can('products.create')
            <a href="{{ route('admin.products.create') }}" class="btn-gold group"><span>+ Add Product</span></a>
        @endcan
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Price</th>
                        <th class="px-5 py-3">Stock</th>
                        <th class="px-5 py-3">Commission</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->thumbnail_url }}" class="h-12 w-12 rounded-lg object-cover ring-1 ring-black/5">
                                    <div>
                                        <p class="font-medium text-ink">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->category?->name ?? 'Uncategorized' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-medium">{{ money($product->final_price) }}</span>
                                @if($product->has_discount)<span class="ml-1 text-xs text-gray-400 line-through">{{ money($product->price) }}</span>@endif
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $product->manage_stock ? $product->stock : '∞' }}</td>
                            <td class="px-5 py-3 text-emerald-600">{{ money($product->contributor_price) }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$product->status" :color="$product->status==='active'?'emerald':($product->status==='draft'?'amber':'gray')" /></td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-action-icon type="external" :href="route('product.show', $product->slug)" target="_blank" label="View on site" color="gray" />
                                    @can('products.edit')<x-action-icon type="edit" :href="route('admin.products.edit', $product)" label="Edit" color="gold" />@endcan
                                    @can('products.delete')
                                        <button type="button" title="Delete" aria-label="Delete"
                                                @click="confirm = { open:true, name: @js($product->name), action: @js(route('admin.products.destroy', $product)), title:'Delete Product?' }"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-10 text-center text-gray-400">No products found. <a href="{{ route('admin.products.create') }}" class="text-gold-600 underline">Add one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $products->links() }}</div>
    </div>

    <x-confirm-delete />
</div>

</x-admin>
