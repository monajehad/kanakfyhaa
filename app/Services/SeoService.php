<?php

namespace App\Services;

use App\Helpers\Helpers;

class SeoService
{
    /**
     * Get SEO configuration
     */
    public static function getConfig()
    {
        return [
            'title' => Helpers::getSetting('seo_title', 'Kanak Fyhaa - Experience Cultural Heritage'),
            'description' => Helpers::getSetting('seo_description', 'Discover the beauty and culture of our city through immersive experiences'),
            'keywords' => Helpers::getSetting('seo_keywords', 'culture, heritage, experience, tourism, city'),
            'google_analytics_id' => Helpers::getSetting('google_analytics_id', ''),
            'robots_txt' => Helpers::getSetting('robots_txt', "User-agent: *\nAllow: /\nSitemap: /sitemap.xml"),
        ];
    }

    /**
     * Get meta title
     */
    public static function getTitle()
    {
        return Helpers::getSetting('seo_title', 'Kanak Fyhaa - Experience Cultural Heritage');
    }

    /**
     * Get meta description
     */
    public static function getDescription()
    {
        return Helpers::getSetting('seo_description', 'Discover the beauty and culture of our city through immersive experiences');
    }

    /**
     * Get meta keywords
     */
    public static function getKeywords()
    {
        return Helpers::getSetting('seo_keywords', 'culture, heritage, experience, tourism, city');
    }

    /**
     * Get Google Analytics ID
     */
    public static function getGoogleAnalyticsId()
    {
        return Helpers::getSetting('google_analytics_id', '');
    }

    /**
     * Get robots.txt content
     */
    public static function getRobotsTxt()
    {
        return Helpers::getSetting('robots_txt', "User-agent: *\nAllow: /\nSitemap: /sitemap.xml");
    }
}
