<x-storefront
    :title="$page->meta_title ?: $page->title"
    footer="full"
    :meta-description="$page->meta_description ?: \Illuminate\Support\Str::of(strip_tags($page->content))->squish()->limit(160)">
    <section class="container-x py-12 lg:py-16">
        <div class="mx-auto max-w-3xl">
            <h1 class="mb-8 font-serif text-3xl font-bold text-ink lg:text-4xl">{{ $page->title }}</h1>
            <div class="product-desc max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </section>
</x-storefront>
