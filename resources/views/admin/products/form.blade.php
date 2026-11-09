<x-admin :title="$product->exists ? 'Edit Product' : 'Add Product'" active="products">

    <form method="POST"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          enctype="multipart/form-data" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf
        @if($product->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="lg:col-span-3 rounded-lg bg-red-50 p-4 text-sm text-red-600">
                <ul class="list-inside list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- Main --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="card p-6">
                <h2 class="section-title mb-4">Basic Info</h2>
                <div class="space-y-4">
                    <div>
                        <label class="label">Name *</label>
                        <input name="name" class="input" required value="{{ old('name', $product->name) }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Slug (optional)</label>
                            <input name="slug" class="input" value="{{ old('slug', $product->slug) }}" placeholder="auto-generated">
                        </div>
                        <div>
                            <label class="label">SKU</label>
                            <input name="sku" class="input" value="{{ old('sku', $product->sku) }}">
                        </div>
                    </div>
                    <div>
                        <label class="label">Short Description</label>
                        <textarea name="short_description" rows="2" class="input">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div>
                        <label class="label">Full Description</label>
                        <textarea name="description" data-rich rows="6" class="input" placeholder="Describe the product...">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Media --}}
            <div class="card p-6" x-data="{ links: {{ old('youtube_links') ? json_encode(old('youtube_links')) : '[]' }} }">
                <h2 class="section-title mb-4">Media</h2>

                {{-- Thumbnail --}}
                <div class="mb-5" x-data="{ removed:false }">
                    <label class="label">Thumbnail</label>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img src="{{ $product->thumbnail_url }}" class="h-20 w-20 rounded-lg object-cover ring-1 ring-black/10" :class="removed && 'opacity-40'">
                            @if($product->exists && $product->thumbnail)
                                <input type="hidden" name="remove_thumbnail" :value="removed ? '1' : '0'">
                                <button type="button" @click="removed=!removed" :title="removed ? 'Undo' : 'Remove'"
                                        class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold text-white shadow"
                                        :class="removed ? 'bg-gray-500' : 'bg-red-600 hover:bg-red-700'" x-text="removed ? '↺' : '✕'"></button>
                            @endif
                        </div>
                        <input type="file" name="thumbnail" accept="image/*" class="text-sm">
                    </div>
                    <p x-show="removed" x-cloak class="mt-1 text-xs text-red-500">Thumbnail will be removed on save.</p>
                </div>

                {{-- Existing gallery --}}
                @if($product->exists && $product->media->count())
                    <div class="mb-5">
                        <label class="label">Current Gallery</label>
                        <div class="grid grid-cols-4 gap-3 sm:grid-cols-6">
                            @foreach($product->media as $m)
                                <div class="group relative aspect-square overflow-hidden rounded-lg ring-1 ring-black/10">
                                    @if($m->type === 'image')
                                        <img src="{{ $m->src }}" class="h-full w-full object-cover">
                                    @elseif($m->type === 'youtube')
                                        <img src="https://img.youtube.com/vi/{{ $m->youtube_id }}/hqdefault.jpg" class="h-full w-full object-cover">
                                        <span class="absolute inset-0 flex items-center justify-center bg-black/30 text-white">▶</span>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-brand-900 text-gold-400">▶ video</div>
                                    @endif
                                    <button type="button"
                                            onclick="if(confirm('Remove this media?')) document.getElementById('delm-{{ $m->id }}').submit()"
                                            class="absolute right-1 top-1 rounded-full bg-red-600 px-1.5 text-xs text-white opacity-0 group-hover:opacity-100">✕</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Upload images --}}
                <div class="mb-4">
                    <label class="label">Add Images (multiple)</label>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple class="text-sm">
                </div>

                {{-- Upload videos --}}
                <div class="mb-4">
                    <label class="label">Add Videos (mp4/webm, multiple)</label>
                    <input type="file" name="gallery_videos[]" accept="video/*" multiple class="text-sm">
                    <p class="mt-1 text-xs text-gray-400">Large videos may exceed PHP upload limits; or use YouTube links below.</p>
                </div>

                {{-- YouTube links --}}
                <div>
                    <label class="label">YouTube Links</label>
                    <template x-for="(link, i) in links" :key="i">
                        <div class="mb-2 flex gap-2">
                            <input type="url" :name="`youtube_links[]`" x-model="links[i]" placeholder="https://youtube.com/watch?v=..." class="input">
                            <button type="button" @click="links.splice(i,1)" class="rounded-lg bg-red-50 px-3 text-red-500">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="links.push('')" class="text-sm font-medium text-gold-600 hover:underline">+ Add YouTube link</button>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="card p-6">
                <h2 class="section-title mb-4">Pricing</h2>
                <div class="space-y-4">
                    <div>
                        <label class="label">Price (Rs.) *</label>
                        <input type="number" step="0.01" name="price" class="input" required value="{{ old('price', $product->price) }}">
                    </div>
                    <div>
                        <label class="label">Discounted Price (Rs.)</label>
                        <input type="number" step="0.01" name="discounted_price" class="input" value="{{ old('discounted_price', $product->discounted_price) }}">
                    </div>
                    <div>
                        <label class="label">Contributor Commission (Rs.)</label>
                        <input type="number" step="0.01" name="contributor_price" class="input" value="{{ old('contributor_price', $product->contributor_price ?? 0) }}">
                        <p class="mt-1 text-xs text-gray-400">Paid to the affiliate per unit sold via their link.</p>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h2 class="section-title mb-4">Inventory & Status</h2>
                <div class="space-y-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="manage_stock" value="1" class="rounded text-gold-500" @checked(old('manage_stock', $product->manage_stock))>
                        Track stock quantity
                    </label>
                    <div>
                        <label class="label">Stock Quantity</label>
                        <input type="number" name="stock" class="input" value="{{ old('stock', $product->stock ?? 0) }}">
                    </div>
                    <div>
                        <label class="label">Category</label>
                        <select name="category_id" class="input">
                            <option value="">— None —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select name="status" class="input">
                            @foreach(['active'=>'Active','inactive'=>'Inactive','draft'=>'Draft'] as $v=>$l)
                                <option value="{{ $v }}" @selected(old('status', $product->status)===$v)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_featured" value="1" class="rounded text-gold-500" @checked(old('is_featured', $product->is_featured))>
                        Featured product
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="btn-gold group flex-1"><span>{{ $product->exists ? 'Update' : 'Create' }} Product</span></button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
            </div>
        </div>
    </form>

    {{-- hidden media delete forms --}}
    @if($product->exists)
        @foreach($product->media as $m)
            <form id="delm-{{ $m->id }}" method="POST" action="{{ route('admin.products.media.delete', [$product, $m]) }}" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endif

</x-admin>
