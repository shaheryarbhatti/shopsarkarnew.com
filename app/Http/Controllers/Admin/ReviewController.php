<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\YouTubeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $reviews = Review::with('product', 'user')
            ->when($filter !== 'all', fn ($q) => $q->where('status', $filter))
            ->when($request->search, fn ($q, $s) => $q->where('author_name', 'like', "%{$s}%")->orWhere('body', 'like', "%{$s}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'filter' => $filter,
            'counts' => $counts,
            'youtubeReady' => app(YouTubeService::class)->isConfigured(),
        ]);
    }

    public function updateStatus(Request $request, Review $review)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $review->update(['status' => $data['status']]);

        return back()->with('success', 'Review marked as ' . $data['status'] . '.');
    }

    /** Save a YouTube video ID/URL manually pasted by the admin. */
    public function saveLink(Request $request, Review $review)
    {
        $data = $request->validate([
            'youtube' => ['required', 'string', 'max:255'],
        ]);

        $review->update(['youtube_id' => $this->extractYoutubeId($data['youtube'])]);

        return back()->with('success', 'YouTube link saved.');
    }

    /** Upload the server video to the store's YouTube channel via the API. */
    public function uploadYoutube(Review $review, YouTubeService $youtube)
    {
        if (! $review->video_path) {
            return back()->with('error', 'This review has no uploaded video.');
        }
        if (! $youtube->isConfigured()) {
            return back()->with('error', 'YouTube API is not configured yet. Add your credentials in .env, or paste a YouTube link manually.');
        }

        try {
            $title = 'Customer Review — ' . ($review->product?->name ?? 'Product') . ' | ' . setting('site_name', 'ShopSarkar');
            $description = trim(($review->title ? $review->title . "\n\n" : '') . ($review->body ?? ''))
                . "\n\nReviewed by {$review->author_name} on " . setting('site_name', 'ShopSarkar') . '.';

            $videoId = $youtube->uploadVideo(
                Storage::disk('public')->path($review->video_path),
                $title,
                $description
            );

            $review->update(['youtube_id' => $videoId]);

            return back()->with('success', 'Video uploaded to YouTube successfully. You can now delete it from the server.');
        } catch (\Throwable $e) {
            return back()->with('error', 'YouTube upload failed: ' . $e->getMessage());
        }
    }

    /** Delete the video from the store's YouTube channel (via API). */
    public function deleteYoutube(Review $review, YouTubeService $youtube)
    {
        if (! $review->youtube_id) {
            return back()->with('error', 'This review has no YouTube video.');
        }
        if (! $youtube->isConfigured()) {
            return back()->with('error', 'YouTube API is not configured.');
        }

        try {
            $youtube->deleteVideo($review->youtube_id);
            $review->update(['youtube_id' => null]);

            return back()->with('success', 'Video deleted from YouTube.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete from YouTube: ' . $e->getMessage());
        }
    }

    /** Delete the uploaded video from our server (keeps the YouTube copy). */
    public function deleteVideo(Review $review)
    {
        if ($review->video_path) {
            Storage::disk('public')->delete($review->video_path);
            $review->update(['video_path' => null]);
        }

        return back()->with('success', 'Video removed from the server.');
    }

    public function destroy(Review $review)
    {
        if ($review->video_path) {
            Storage::disk('public')->delete($review->video_path);
        }
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }

    /** Accepts a full URL or a bare ID and returns the 11-char video ID. */
    protected function extractYoutubeId(string $input): string
    {
        $input = trim($input);
        if (preg_match('~(?:youtu\.be/|v=|/embed/|/shorts/)([A-Za-z0-9_-]{11})~', $input, $m)) {
            return $m[1];
        }
        return $input;
    }
}
