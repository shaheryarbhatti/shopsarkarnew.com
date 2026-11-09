<x-admin title="Reviews" active="reviews">

<div x-data="{ confirm: { open:false, name:'', action:'', title:'Delete Review?' } }">

    {{-- Filter tabs --}}
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $key => $label)
            <a href="{{ route('admin.reviews.index', ['filter' => $key]) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $filter === $key ? 'bg-brand-900 text-gold-300' : 'bg-white text-gray-600 ring-1 ring-black/5 hover:bg-gray-50' }}">
                {{ $label }}
                <span class="ml-1 rounded-full bg-black/10 px-1.5 text-xs">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </div>

    @unless($youtubeReady)
        <div class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
            <span><strong>YouTube auto-upload not configured.</strong> You can still approve reviews and paste a YouTube link manually. To enable one-click upload, add your YouTube API credentials in <code>.env</code> (see the setup note).</span>
        </div>
    @endunless

    <div class="space-y-5">
        @forelse($reviews as $review)
            <div class="card overflow-hidden">
                <div class="grid grid-cols-1 gap-5 p-5 lg:grid-cols-[1fr_320px]">
                    {{-- Left: review content --}}
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-brand-700 to-brand-900 text-sm font-bold text-gold-400">{{ strtoupper(substr($review->author_name, 0, 1)) }}</span>
                                <div>
                                    <p class="flex items-center gap-2 font-semibold text-ink">
                                        {{ $review->author_name }}
                                        @if($review->is_verified_buyer)
                                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-600">Verified Buyer</span>
                                        @endif
                                    </p>
                                    <x-stars :rating="$review->rating" class="mt-0.5" />
                                </div>
                            </div>
                            <x-status-badge :status="$review->status" :color="$review->statusColor()" />
                        </div>

                        <p class="mt-3 text-sm text-gray-400">
                            on <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="font-medium text-gold-600 hover:underline">{{ $review->product?->name ?? 'Product' }}</a>
                            · {{ $review->created_at->format('d M Y') }}
                        </p>

                        @if($review->body)<p class="mt-3 text-gray-600">{{ $review->body }}</p>@endif

                        {{-- Status actions --}}
                        @can('reviews.manage')
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                @if($review->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.reviews.status', $review) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Approve
                                        </button>
                                    </form>
                                @endif
                                @if($review->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.reviews.status', $review) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-200">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg> Reject
                                        </button>
                                    </form>
                                @endif
                                <button type="button"
                                        @click="confirm = { open:true, name: @js('review by '.$review->author_name), action: @js(route('admin.reviews.destroy', $review)), title:'Delete Review?' }"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-500 transition hover:bg-red-100">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397"/></svg> Delete
                                </button>
                            </div>
                        @endcan
                    </div>

                    {{-- Right: video + youtube controls --}}
                    <div>
                        @if($review->youtube_id)
                            <div class="aspect-video overflow-hidden rounded-xl ring-1 ring-black/5">
                                <iframe class="h-full w-full" src="https://www.youtube.com/embed/{{ $review->youtube_id }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <p class="mt-2 flex items-center gap-1 text-xs text-emerald-600"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg> On YouTube</p>
                        @elseif($review->video_url)
                            <video controls playsinline class="aspect-video w-full rounded-xl bg-black ring-1 ring-black/5" src="{{ $review->video_url }}"></video>
                        @else
                            <div class="flex aspect-video items-center justify-center rounded-xl bg-gray-50 text-sm text-gray-300 ring-1 ring-black/5">No video</div>
                        @endif

                        @can('reviews.manage')
                            <div class="mt-3 space-y-2">
                                {{-- Upload to YouTube --}}
                                @if($review->video_path && ! $review->youtube_id)
                                    @if($youtubeReady)
                                        <form method="POST" action="{{ route('admin.reviews.youtube', $review) }}" onsubmit="this.querySelector('button').disabled=true;this.querySelector('button span').textContent='Uploading...'">
                                            @csrf
                                            <button class="btn-dark w-full py-2 text-sm"><span>Upload to YouTube</span></button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Delete from server (available once uploaded or any time) --}}
                                @if($review->video_path)
                                    <form method="POST" action="{{ route('admin.reviews.video.delete', $review) }}" onsubmit="return confirm('Remove the video from the server? {{ $review->youtube_id ? 'The YouTube copy stays.' : 'This cannot be undone.' }}')">
                                        @csrf @method('DELETE')
                                        <button class="w-full rounded-full bg-red-50 py-2 text-sm font-semibold text-red-500 transition hover:bg-red-100">Delete video from server</button>
                                    </form>
                                @endif

                                {{-- Delete from YouTube (via API) --}}
                                @if($review->youtube_id && $youtubeReady)
                                    <form method="POST" action="{{ route('admin.reviews.youtube.delete', $review) }}" onsubmit="return confirm('Permanently delete this video from your YouTube channel? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button class="flex w-full items-center justify-center gap-1.5 rounded-full bg-red-600 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/></svg>
                                            Delete from YouTube
                                        </button>
                                    </form>
                                @endif

                                {{-- Manual YouTube link --}}
                                <form method="POST" action="{{ route('admin.reviews.link', $review) }}" class="flex gap-2">
                                    @csrf @method('PUT')
                                    <input name="youtube" value="{{ $review->youtube_id }}" placeholder="Paste YouTube link / ID" class="input flex-1 py-2 text-xs">
                                    <button class="rounded-lg bg-gray-900 px-3 text-xs font-semibold text-white transition hover:bg-gray-700">Save</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center text-gray-400">No reviews found.</div>
        @endforelse
    </div>

    <div class="mt-5">{{ $reviews->links() }}</div>

    <x-confirm-delete />
</div>

</x-admin>
