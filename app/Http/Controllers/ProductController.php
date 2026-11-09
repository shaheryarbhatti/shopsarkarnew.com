<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->status === 'active', 404);

        $product->loadMissing('media', 'category');
        $product->increment('views');

        $media = $this->buildMedia($product);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->take(4)
            ->get();

        if ($related->count() < 4) {
            $related = Product::active()->where('id', '!=', $product->id)->latest()->take(4)->get();
        }

        $reviews = $product->approvedReviews()->with('user')->paginate(5);

        $ratingCounts = $product->approvedReviews()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        return view('frontend.product', compact('product', 'media', 'related', 'reviews', 'ratingCounts'));
    }

    /**
     * Normalise product media into a JS-friendly array for the Alpine gallery.
     * @return array<int,array{type:string,src:?string,youtube:?string,thumb:?string,poster:?string}>
     */
    protected function buildMedia(Product $product): array
    {
        $items = [];

        foreach ($product->media as $m) {
            if ($m->type === 'youtube') {
                $yt = $m->youtube_id;
                if (! $yt) {
                    continue;
                }
                $items[] = [
                    'type' => 'youtube',
                    'src' => null,
                    'youtube' => $yt,
                    'thumb' => "https://img.youtube.com/vi/{$yt}/hqdefault.jpg",
                    'poster' => null,
                ];
            } elseif ($m->type === 'video') {
                if (! $m->file_path) {
                    continue;
                }
                $items[] = [
                    'type' => 'video',
                    'src' => Storage::url($m->file_path),
                    'youtube' => null,
                    'thumb' => $product->thumbnail ? Storage::url($product->thumbnail) : null,
                    'poster' => $product->thumbnail ? Storage::url($product->thumbnail) : null,
                ];
            } else { // image
                if (! $m->file_path) {
                    continue;
                }
                $url = Storage::url($m->file_path);
                $items[] = [
                    'type' => 'image',
                    'src' => $url,
                    'youtube' => null,
                    'thumb' => $url,
                    'poster' => null,
                ];
            }
        }

        // Fallback: thumbnail or placeholder as a single image
        if (empty($items)) {
            $items[] = [
                'type' => 'image',
                'src' => $product->thumbnail_url,
                'youtube' => null,
                'thumb' => $product->thumbnail_url,
                'poster' => null,
            ];
        }

        return $items;
    }
}
