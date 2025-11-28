<?php

namespace App\Services;

use App\Helpers\Helpers;

class SocialService
{
    /**
     * Get all social media URLs
     */
    public static function getAll()
    {
        return [
            'facebook' => Helpers::getSetting('facebook_url', ''),
            'twitter' => Helpers::getSetting('twitter_url', ''),
            'instagram' => Helpers::getSetting('instagram_url', ''),
            'linkedin' => Helpers::getSetting('linkedin_url', ''),
            'youtube' => Helpers::getSetting('youtube_url', ''),
            'tiktok' => Helpers::getSetting('tiktok_url', ''),
        ];
    }

    /**
     * Get Facebook URL
     */
    public static function getFacebook()
    {
        return Helpers::getSetting('facebook_url', '');
    }

    /**
     * Get Twitter URL
     */
    public static function getTwitter()
    {
        return Helpers::getSetting('twitter_url', '');
    }

    /**
     * Get Instagram URL
     */
    public static function getInstagram()
    {
        return Helpers::getSetting('instagram_url', '');
    }

    /**
     * Get LinkedIn URL
     */
    public static function getLinkedIn()
    {
        return Helpers::getSetting('linkedin_url', '');
    }

    /**
     * Get YouTube URL
     */
    public static function getYouTube()
    {
        return Helpers::getSetting('youtube_url', '');
    }

    /**
     * Get TikTok URL
     */
    public static function getTikTok()
    {
        return Helpers::getSetting('tiktok_url', '');
    }

    /**
     * Get active social media links (non-empty URLs)
     */
    public static function getActive()
    {
        $all = self::getAll();
        return array_filter($all, function ($url) {
            return !empty($url);
        });
    }
}
