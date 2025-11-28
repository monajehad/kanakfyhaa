<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Helpers;
use App\Services\PaymentService;
use App\Services\EmailService;
use App\Services\SeoService;
use App\Services\SocialService;
use Illuminate\Support\Facades\View;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            // Share settings with all views
            View::share('appSettings', Helpers::getAllSettings());
            
            // Set app name from settings
            $appName = Helpers::getSetting('app_name', config('app.name'));
            if ($appName) {
                config(['app.name' => $appName]);
            }

            // Set app locale/language from settings
            $language = Helpers::getSetting('language', app()->getLocale());
            if ($language) {
                app()->setLocale($language);
            }

            // Set timezone from settings
            $timezone = Helpers::getSetting('timezone', config('app.timezone'));
            if ($timezone) {
                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);
            }

            // Apply primary color from settings
            $primaryColor = Helpers::getSetting('primary_color', '#eab308');
            if ($primaryColor) {
                // Store in cookie for frontend
                if (!isset($_COOKIE['admin-primaryColor'])) {
                    setcookie('admin-primaryColor', $primaryColor, time() + (86400 * 365), '/', null, false, true);
                }
            }

            // Share all services with views
            View::composer('*', function ($view) {
                $view->with([
                    'appSettings' => Helpers::getAllSettings(),
                    'appName' => Helpers::getSetting('app_name', config('app.name')),
                    'appLogo' => Helpers::getSetting('app_logo', ''),
                    'appDescription' => Helpers::getSetting('app_description', ''),
                    'primaryColor' => Helpers::getSetting('primary_color', '#eab308'),
                    'secondaryColor' => Helpers::getSetting('secondary_color', '#0ea5e9'),
                    'accentColor' => Helpers::getSetting('accent_color', '#10b981'),
                    // Services
                    'paymentConfig' => PaymentService::getConfig(),
                    'emailConfig' => EmailService::getConfig(),
                    'seoConfig' => SeoService::getConfig(),
                    'socialLinks' => SocialService::getActive(),
                ]);
            });

        } catch (\Exception $e) {
            // Silently fail if settings table doesn't exist yet
        }
    }
}

