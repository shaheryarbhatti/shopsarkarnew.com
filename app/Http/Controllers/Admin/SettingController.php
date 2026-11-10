<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\YouTubeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /** group => [key => type]  (type: text|textarea|boolean|number|file) */
    public static function schema(): array
    {
        return [
            'general' => [
                'site_name' => 'text',
                'site_logo' => 'file',
                'footer_logo' => 'file',
                'favicon' => 'file',
                'currency_symbol' => 'text',
                'contact_email' => 'text',
                'contact_phone' => 'text',
                'footer_about' => 'textarea',
            ],
            'hero' => [
                'hero_eyebrow' => 'text',
                'hero_heading_en' => 'textarea',
                'hero_heading_ur' => 'textarea',
                'hero_subtext_en' => 'textarea',
                'hero_subtext_ur' => 'textarea',
                'hero_button_text' => 'text',
                'hero_button_text_ur' => 'text',
                'hero_video' => 'file',
                'hero_poster' => 'file',
                'hero_poster_mobile' => 'file',
                'hero_product_id' => 'number',
            ],
            'auth' => [
                'auth_login_image' => 'file',
                'auth_register_image' => 'file',
            ],
            'shipping' => [
                'shipping_enabled' => 'boolean',
                'shipping_flat_rate' => 'number',
                'free_shipping_enabled' => 'boolean',
                'free_shipping_threshold' => 'number',
            ],
            'tax' => [
                'tax_enabled' => 'boolean',
                'tax_rate' => 'number',
                'tax_label' => 'text',
            ],
            'affiliate' => [
                'affiliate_enabled' => 'boolean',
                'withdraw_min_amount' => 'number',
                'earning_maturity_days' => 'number',
                'affiliate_terms' => 'textarea',
            ],
            'reviews' => [
                'youtube_watermark' => 'file',
                'youtube_watermark_position' => 'text',
            ],
            'marketing' => [
                'facebook_pixel_id' => 'text',
                'tiktok_pixel_id' => 'text',
                'custom_head_code' => 'textarea',
            ],
        ];
    }

    public function edit(string $group = 'general')
    {
        $schema = static::schema();
        abort_unless(isset($schema[$group]), 404);

        return view('admin.settings.edit', [
            'group' => $group,
            'groups' => array_keys($schema),
            'fields' => $schema[$group],
            'values' => Setting::all(),
            'products' => \App\Models\Product::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, string $group)
    {
        $schema = static::schema();
        abort_unless(isset($schema[$group]), 404);

        $request->validate([
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'footer_logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:2048'],
            'hero_poster' => ['nullable', 'image', 'max:4096'],
            'hero_poster_mobile' => ['nullable', 'image', 'max:4096'],
            'auth_login_image' => ['nullable', 'image', 'max:4096'],
            'auth_register_image' => ['nullable', 'image', 'max:4096'],
            'hero_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:102400'],
            'youtube_watermark' => ['nullable', 'image', 'mimes:png', 'max:1024'],
            'youtube_watermark_position' => ['nullable', 'in:topLeft,topRight,bottomLeft,bottomRight'],
        ], [
            'youtube_watermark.mimes' => 'The watermark must be a transparent PNG image.',
            'youtube_watermark.max' => 'The watermark PNG must be 1 MB or smaller (YouTube limit).',
        ]);

        foreach ($schema[$group] as $key => $type) {
            if ($type === 'boolean') {
                Setting::put($key, $request->boolean($key) ? '1' : '0', $group, 'boolean');
                continue;
            }

            if ($type === 'file') {
                // Replace with a new upload
                if ($request->hasFile($key)) {
                    $old = Setting::get($key);
                    if ($old) {
                        Storage::disk('public')->delete($old);
                    }

                    if ($key === 'favicon') {
                        // Always normalise the favicon to a 512x512 PNG.
                        $image = \Intervention\Image\Laravel\Facades\Image::read($request->file($key))
                            ->contain(512, 512, 'ffffff00');
                        $path = 'branding/favicon-' . uniqid() . '.png';
                        Storage::disk('public')->put($path, (string) $image->toPng());
                    } else {
                        $dir = $key === 'hero_video' ? 'hero' : 'branding';
                        $path = $request->file($key)->store($dir, 'public');
                    }

                    Setting::put($key, $path, $group, 'file');
                }
                // Remove existing file
                elseif ($request->input("remove_{$key}") === '1') {
                    $old = Setting::get($key);
                    if ($old) {
                        Storage::disk('public')->delete($old);
                    }
                    Setting::put($key, '', $group, 'file');
                }
                continue;
            }

            // text / textarea / number
            Setting::put($key, $request->input($key), $group, $type);
        }

        // Review videos: push the chosen logo to the connected YouTube channel so it
        // appears as a branding watermark in the corner of every channel video.
        if ($group === 'reviews') {
            return $this->syncYoutubeWatermark($request);
        }

        return back()->with('success', ucfirst($group) . ' settings saved.');
    }

    /** Apply / remove the channel watermark on YouTube based on saved settings. */
    protected function syncYoutubeWatermark(Request $request)
    {
        $youtube = app(YouTubeService::class);

        if (! $youtube->isConfigured()) {
            return back()->with('success', 'Saved. Connect YouTube to apply the watermark to your channel.');
        }

        $logo = Setting::get('youtube_watermark');

        try {
            // Removed the logo, or none set → clear the channel watermark.
            if ($request->input('remove_youtube_watermark') === '1' || ! $logo) {
                $youtube->unsetChannelWatermark();

                return back()->with('success', 'Watermark removed from your YouTube channel.');
            }

            $position = Setting::get('youtube_watermark_position') ?: 'topRight';
            $youtube->setChannelWatermark(Storage::disk('public')->path($logo), $position);

            return back()->with('success', 'Watermark applied to your YouTube channel — it will now appear in the corner of every video.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Settings saved, but updating the YouTube watermark failed: ' . $e->getMessage());
        }
    }
}
