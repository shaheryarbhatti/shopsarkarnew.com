<?php

namespace App\Console\Commands;

use App\Services\YouTubeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class YoutubeWatermark extends Command
{
    protected $signature = 'youtube:watermark {path? : Path to a PNG logo (defaults to the store logo)} {--position=topRight : topLeft|topRight|bottomLeft|bottomRight}';

    protected $description = 'Set the store logo as a corner watermark on every video of the connected YouTube channel.';

    public function handle(YouTubeService $service): int
    {
        if (! $service->isConfigured()) {
            $this->error('YouTube is not configured. Add credentials in .env first.');

            return self::FAILURE;
        }

        $path = $this->argument('path');
        if (! $path) {
            $logo = setting('site_logo');
            if (! $logo) {
                $this->error('No logo found. Upload a logo in Settings, or pass a PNG path.');

                return self::FAILURE;
            }
            $path = Storage::disk('public')->path($logo);
        }

        if (! is_file($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        try {
            $service->setChannelWatermark($path, $this->option('position'));
            $this->info('✅ Watermark set at ' . $this->option('position') . '. It will appear on all channel videos.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
