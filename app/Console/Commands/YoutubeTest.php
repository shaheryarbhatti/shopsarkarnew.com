<?php

namespace App\Console\Commands;

use App\Services\YouTubeService;
use Illuminate\Console\Command;

class YoutubeTest extends Command
{
    protected $signature = 'youtube:test';

    protected $description = 'Verify the YouTube API credentials by fetching the connected channel.';

    public function handle(YouTubeService $service): int
    {
        if (! $service->isConfigured()) {
            $this->error('YouTube is not configured. Set YOUTUBE_CLIENT_ID, YOUTUBE_CLIENT_SECRET and YOUTUBE_REFRESH_TOKEN in .env, then run: php artisan config:clear');

            return self::FAILURE;
        }

        try {
            $this->info('✅ Connected to YouTube channel: ' . $service->channelName());

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Connection failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
