<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class MailConfigServiceProvider extends ServiceProvider
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
        // Only configure mail if database is available
        try {
            // Check if settings table exists
            if (Schema::hasTable('settings')) {
                $this->configureMailFromSettings();
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available (e.g., during migrations)
        }
    }

    /**
     * Configure mail settings from database
     */
    protected function configureMailFromSettings(): void
    {
        // Get mail settings from database
        $mailMailer = Setting::get('mail_mailer', config('mail.mailers.smtp.transport'));
        $mailHost = Setting::get('mail_host', config('mail.mailers.smtp.host'));
        $mailPort = Setting::get('mail_port', config('mail.mailers.smtp.port'));
        $mailUsername = Setting::get('mail_username', config('mail.mailers.smtp.username'));
        $mailPassword = Setting::get('mail_password', config('mail.mailers.smtp.password'));
        $mailEncryption = Setting::get('mail_encryption', config('mail.mailers.smtp.encryption'));
        $mailFromAddress = Setting::get('mail_from_address', config('mail.from.address'));
        $mailFromName = Setting::get('mail_from_name', config('mail.from.name'));

        // Only update config if settings are not empty
        if (!empty($mailHost) && !empty($mailUsername)) {
            Config::set('mail.default', $mailMailer);
            Config::set('mail.mailers.smtp.transport', $mailMailer);
            Config::set('mail.mailers.smtp.host', $mailHost);
            Config::set('mail.mailers.smtp.port', $mailPort);
            Config::set('mail.mailers.smtp.username', $mailUsername);
            Config::set('mail.mailers.smtp.password', $mailPassword);
            Config::set('mail.mailers.smtp.encryption', $mailEncryption);
        }

        // Always update from address and name if available
        if (!empty($mailFromAddress)) {
            Config::set('mail.from.address', $mailFromAddress);
        }
        if (!empty($mailFromName)) {
            Config::set('mail.from.name', $mailFromName);
        }
    }
}
