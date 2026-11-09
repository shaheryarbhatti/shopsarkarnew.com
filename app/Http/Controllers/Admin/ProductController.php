<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%"))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(['status' => 'active', 'manage_stock' => true]),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($request, $data) {
            $data['slug'] = $this->uniqueSlug($data['name']);

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            }

            $product = Product::create($data);
            $this->syncMedia($request, $product);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load('media');

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product);

        DB::transaction(function () use ($request, $data, $product) {
            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            } elseif ($request->input('remove_thumbnail') === '1') {
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $data['thumbnail'] = null;
            }

            if (empty($data['slug'])) {
                $data['slug'] = $product->slug;
            }

            $product->update($data);
            $this->syncMedia($request, $product);
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->media as $m) {
            if ($m->file_path) {
                Storage::disk('public')->delete($m->file_path);
            }
        }
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function deleteMedia(Product $product, ProductMedia $media)
    {
        abort_unless($media->product_id === $product->id, 404);

        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }
        $media->delete();

        return back()->with('success', 'Media removed.');
    }

    // ---------- helpers ----------

    protected function validateData(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'contributor_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,draft'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'max:8192'],
            'gallery_videos.*' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'youtube_links.*' => ['nullable', 'url'],
        ]);

        $data['contributor_price'] = $data['contributor_price'] ?? 0;
        $data['stock'] = $data['stock'] ?? 0;
        $data['manage_stock'] = $request->boolean('manage_stock');
        $data['is_featured'] = $request->boolean('is_featured');

        // these aren't columns
        unset($data['thumbnail'], $data['gallery_images'], $data['gallery_videos'], $data['youtube_links']);

        return $data;
    }

    protected function syncMedia(Request $request, Product $product): void
    {
        $order = (int) $product->media()->max('sort_order');

        foreach ((array) $request->file('gallery_images', []) as $file) {
            if (! $file) {
                continue;
            }
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => $file->store('products', 'public'),
                'sort_order' => ++$order,
            ]);
        }

        foreach ((array) $request->file('gallery_videos', []) as $file) {
            if (! $file) {
                continue;
            }
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'video',
                'file_path' => $file->store('products/videos', 'public'),
                'sort_order' => ++$order,
            ]);
        }

        foreach ((array) $request->input('youtube_links', []) as $url) {
            if (! trim((string) $url)) {
                continue;
            }
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'youtube',
                'url' => trim($url),
                'sort_order' => ++$order,
            ]);
        }
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
