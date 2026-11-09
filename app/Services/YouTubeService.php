<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\YouTube as GoogleYouTube;
use Google\Service\YouTube\Video;
use Google\Service\YouTube\VideoSnippet;
use Google\Service\YouTube\VideoStatus;

/**
 * Uploads review videos to the store's own YouTube channel.
 *
 * Requires (one-time setup):
 *  1. composer require google/apiclient
 *  2. A Google Cloud project with the "YouTube Data API v3" enabled
 *  3. OAuth 2.0 credentials (Client ID + Secret) for the channel owner
 *  4. A refresh token obtained after the owner authorises once
 *
 * Put these in .env:
 *  YOUTUBE_CLIENT_ID=...
 *  YOUTUBE_CLIENT_SECRET=...
 *  YOUTUBE_REFRESH_TOKEN=...
 */
class YouTubeService
{
    public function isConfigured(): bool
    {
        return class_exists(GoogleClient::class)
            && config('services.youtube.client_id')
            && config('services.youtube.client_secret')
            && config('services.youtube.refresh_token');
    }

    public function client(): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(config('services.youtube.client_id'));
        $client->setClientSecret(config('services.youtube.client_secret'));
        $client->refreshToken(config('services.youtube.refresh_token'));
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.upload',
            'https://www.googleapis.com/auth/youtube',
        ]);

        return $client;
    }

    /**
     * Upload a local video file and return the created YouTube video ID.
     */
    public function uploadVideo(string $filePath, string $title, string $description = '', string $privacy = 'public'): string
    {
        $youtube = new GoogleYouTube($this->client());

        $snippet = new VideoSnippet();
        $snippet->setTitle(mb_substr($title, 0, 100));
        $snippet->setDescription(mb_substr($description, 0, 4900));
        $snippet->setTags(['review', 'customer review', (string) setting('site_name', 'ShopSarkar')]);

        $status = new VideoStatus();
        $status->setPrivacyStatus($privacy); // public | unlisted | private

        $video = new Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        $response = $youtube->videos->insert('snippet,status', $video, [
            'data' => file_get_contents($filePath),
            'mimeType' => 'video/*',
            'uploadType' => 'multipart',
        ]);

        return $response->getId();
    }

    /** Delete a video from the connected YouTube channel. */
    public function deleteVideo(string $videoId): void
    {
        $youtube = new GoogleYouTube($this->client());
        $youtube->videos->delete($videoId);
    }

    /** Return the connected channel's title (used to verify credentials). */
    public function channelName(): string
    {
        $youtube = new GoogleYouTube($this->client());
        $channels = $youtube->channels->listChannels('snippet', ['mine' => true]);
        $channel = $channels->getItems()[0] ?? null;

        return $channel ? $channel->getSnippet()->getTitle() : 'Unknown channel';
    }

    /**
     * Set a channel branding watermark (e.g. the store logo) so it appears in a
     * corner of every video on the channel. Position options include the top-right
     * corner. Call this once after uploading your logo.
     */
    public function setChannelWatermark(string $logoPath, string $position = 'topRight'): void
    {
        $youtube = new GoogleYouTube($this->client());

        $channels = $youtube->channels->listChannels('id', ['mine' => true]);
        $channelId = $channels->getItems()[0]->getId();

        $watermark = new \Google\Service\YouTube\InvideoBranding();
        $timing = new \Google\Service\YouTube\InvideoTiming();
        $timing->setType('offsetFromStart');
        $timing->setOffsetMs('0');
        $watermark->setTiming($timing);

        $position_obj = new \Google\Service\YouTube\InvideoPosition();
        $position_obj->setType('corner');
        $position_obj->setCornerPosition($position); // topLeft | topRight | bottomLeft | bottomRight
        $watermark->setPosition($position_obj);

        $youtube->watermarks->set($channelId, $watermark, [
            'data' => file_get_contents($logoPath),
            'mimeType' => 'image/png',
            'uploadType' => 'multipart',
        ]);
    }

    /** Remove the channel branding watermark. */
    public function unsetChannelWatermark(): void
    {
        $youtube = new GoogleYouTube($this->client());
        $channels = $youtube->channels->listChannels('id', ['mine' => true]);
        $channelId = $channels->getItems()[0]->getId();

        $youtube->watermarks->unsetWatermarks($channelId);
    }
}
