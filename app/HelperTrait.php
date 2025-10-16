<?php

namespace App;

use Illuminate\Support\Facades\Http;

trait HelperTrait
{
    function convertToEmbedUrl($url)
    {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);

        if (isset($matches[1])) {
             return "https://www.youtube.com/embed/{$matches[1]}";
            // return $url;
        }

        return null;
    }



    function getYoutubeVideoDetails($embedUrl)
    {
        // Convert Embed URL to Watch URL
        $videoId = '';
        if (preg_match('/embed\/([a-zA-Z0-9_-]+)/', $embedUrl, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/watch\?v=([a-zA-Z0-9_-]+)/', $embedUrl, $matches)) {
            $videoId = $matches[1];
        }

        if (!$videoId) {
            return null; // Invalid URL
        }

        $watchUrl = 'https://www.youtube.com/watch?v=' . $videoId;

        // YouTube oEmbed API
        $oEmbedUrl = 'https://www.youtube.com/oembed?url=' . urlencode($watchUrl) . '&format=json';

        $response = Http::get($oEmbedUrl);

        if ($response->successful()) {
            return $response->json(); // Returns title, author, thumbnail, etc.
        }

        return null;
    }

}
