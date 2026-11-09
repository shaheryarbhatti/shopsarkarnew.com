<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['nullable', 'string', 'max:2000'],
            'video'  => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-matroska', 'max:51200'], // 50 MB
        ], [
            'video.max' => 'The video may not be larger than 50 MB.',
            'video.mimetypes' => 'Please upload a valid video file (mp4, mov or webm).',
        ]);

        $user = Auth::user();

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('reviews/videos', 'public');
        }

        // Mark as a verified buyer if they completed an order with this product.
        $verified = $user
            && $user->orders()
                ->where('status', 'completed')
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->exists();

        Review::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'author_name' => $user?->name ?? 'Customer',
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'video_path' => $videoPath,
            'status' => 'pending',
            'is_verified_buyer' => (bool) $verified,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and will appear once approved.');
    }
}
