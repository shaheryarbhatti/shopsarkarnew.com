<x-admin :title="$page->exists ? 'Edit Page' : 'Add Page'" active="pages">

    <form method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="card max-w-3xl p-6">
        @csrf
        @if($page->exists) @method('PUT') @endif
        @if($errors->any())<div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>@endif

        <div class="space-y-5">
            <div>
                <label class="label">Title *</label>
                <input name="title" class="input" required value="{{ old('title', $page->title) }}">
            </div>
            <div>
                <label class="label">Content</label>
                <textarea name="content" data-rich rows="12" class="input" placeholder="Write the page content...">{{ old('content', $page->content) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label">Sort Order</label><input type="number" name="sort_order" class="input" value="{{ old('sort_order', $page->sort_order ?? 0) }}"></div>
                <div><label class="label">Meta Title</label><input name="meta_title" class="input" value="{{ old('meta_title', $page->meta_title) }}"></div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" class="rounded text-gold-500" @checked(old('is_published', $page->is_published))> Published</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="show_in_footer" value="1" class="rounded text-gold-500" @checked(old('show_in_footer', $page->show_in_footer))> Show in Footer</label>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-gold group"><span>{{ $page->exists ? 'Update' : 'Create' }} Page</span></button>
            <a href="{{ route('admin.pages.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>

</x-admin>
