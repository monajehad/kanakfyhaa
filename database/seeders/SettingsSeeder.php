<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'Kanak Fyhaa',
                'group' => 'general',
                'label' => 'App Name',
                'description' => 'The name of your application',
                'type' => 'text',
            ],
            [
                'key' => 'app_logo',
                'value' => '',
                'group' => 'general',
                'label' => 'App Logo',
                'description' => 'Logo URL or file path',
                'type' => 'text',
            ],
            [
                'key' => 'app_description',
                'value' => 'Experience the cultural heritage and natural beauty',
                'group' => 'general',
                'label' => 'App Description',
                'description' => 'Short description of your app',
                'type' => 'textarea',
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'group' => 'general',
                'label' => 'Timezone',
                'description' => 'Application timezone',
                'type' => 'select',
            ],
            [
                'key' => 'currency',
                'value' => 'USD',
                'group' => 'general',
                'label' => 'Currency',
                'description' => 'Default currency',
                'type' => 'text',
            ],
            [
                'key' => 'language',
                'value' => 'en',
                'group' => 'general',
                'label' => 'Default Language',
                'description' => 'Default application language',
                'type' => 'select',
                'options' => ['en' => 'English', 'ar' => 'Arabic'],
            ],

            // Appearance Settings
            [
                'key' => 'primary_color',
                'value' => '#eab308',
                'group' => 'appearance',
                'label' => 'Primary Color',
                'description' => 'Primary theme color (hex)',
                'type' => 'text',
            ],
            [
                'key' => 'secondary_color',
                'value' => '#0ea5e9',
                'group' => 'appearance',
                'label' => 'Secondary Color',
                'description' => 'Secondary theme color (hex)',
                'type' => 'text',
            ],
            [
                'key' => 'accent_color',
                'value' => '#10b981',
                'group' => 'appearance',
                'label' => 'Accent Color',
                'description' => 'Accent theme color (hex)',
                'type' => 'text',
            ],
            [
                'key' => 'dark_mode_enabled',
                'value' => '0',
                'group' => 'appearance',
                'label' => 'Enable Dark Mode',
                'description' => 'Allow users to toggle dark mode',
                'type' => 'boolean',
            ],
            [
                'key' => 'site_logo_width',
                'value' => '200',
                'group' => 'appearance',
                'label' => 'Logo Width (px)',
                'description' => 'Logo width in pixels',
                'type' => 'number',
            ],

            // Email Settings
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'group' => 'email',
                'label' => 'Mail Driver',
                'description' => 'Mail driver to use',
                'type' => 'select',
                'options' => ['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => 'Log'],
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.gmail.com',
                'group' => 'email',
                'label' => 'SMTP Host',
                'description' => 'SMTP server hostname',
                'type' => 'text',
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'group' => 'email',
                'label' => 'SMTP Port',
                'description' => 'SMTP server port (usually 587 for TLS or 465 for SSL)',
                'type' => 'number',
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'group' => 'email',
                'label' => 'SMTP Username',
                'description' => 'SMTP username (usually your email)',
                'type' => 'email',
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'group' => 'email',
                'label' => 'SMTP Password',
                'description' => 'SMTP password or App Password (for Gmail)',
                'type' => 'password',
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'email',
                'label' => 'SMTP Encryption',
                'description' => 'Encryption method',
                'type' => 'select',
                'options' => ['tls' => 'TLS', 'ssl' => 'SSL', null => 'None'],
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Kanak Fyhaa',
                'group' => 'email',
                'label' => 'From Name',
                'description' => 'Email from name',
                'type' => 'text',
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@kanakfyhaa.com',
                'group' => 'email',
                'label' => 'From Address',
                'description' => 'Email from address',
                'type' => 'email',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@kanakfyhaa.com',
                'group' => 'email',
                'label' => 'Contact Email',
                'description' => 'General contact email',
                'type' => 'email',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@kanakfyhaa.com',
                'group' => 'email',
                'label' => 'Support Email',
                'description' => 'Support team email',
                'type' => 'email',
            ],
            [
                'key' => 'order_notification_email',
                'value' => 'orders@kanakfyhaa.com',
                'group' => 'email',
                'label' => 'Order Notification Email',
                'description' => 'Email for order notifications',
                'type' => 'email',
            ],

            // Payment Settings
            [
                'key' => 'payment_gateway',
                'value' => 'paypal',
                'group' => 'payment',
                'label' => 'Payment Gateway',
                'description' => 'Primary payment gateway',
                'type' => 'select',
                'options' => ['paypal' => 'PayPal', 'stripe' => 'Stripe'],
            ],
            [
                'key' => 'paypal_mode',
                'value' => 'sandbox',
                'group' => 'payment',
                'label' => 'PayPal Mode',
                'description' => 'Sandbox or Live mode',
                'type' => 'select',
                'options' => ['sandbox' => 'Sandbox', 'live' => 'Live'],
            ],
            [
                'key' => 'paypal_client_id',
                'value' => '',
                'group' => 'payment',
                'label' => 'PayPal Client ID',
                'description' => 'Your PayPal client ID',
                'type' => 'text',
            ],
            [
                'key' => 'paypal_secret',
                'value' => '',
                'group' => 'payment',
                'label' => 'PayPal Secret',
                'description' => 'Your PayPal secret key',
                'type' => 'text',
            ],
            [
                'key' => 'stripe_key',
                'value' => '',
                'group' => 'payment',
                'label' => 'Stripe API Key',
                'description' => 'Your Stripe API key',
                'type' => 'text',
            ],
            [
                'key' => 'stripe_secret',
                'value' => '',
                'group' => 'payment',
                'label' => 'Stripe Secret Key',
                'description' => 'Your Stripe secret key',
                'type' => 'text',
            ],

            // SEO Settings
            [
                'key' => 'seo_title',
                'value' => 'Kanak Fyhaa - Experience Cultural Heritage',
                'group' => 'seo',
                'label' => 'Meta Title',
                'description' => 'Default meta title for pages',
                'type' => 'text',
            ],
            [
                'key' => 'seo_description',
                'value' => 'Discover the beauty and culture of our city through immersive experiences',
                'group' => 'seo',
                'label' => 'Meta Description',
                'description' => 'Default meta description for pages',
                'type' => 'textarea',
            ],
            [
                'key' => 'seo_keywords',
                'value' => 'culture, heritage, experience, tourism, city',
                'group' => 'seo',
                'label' => 'Meta Keywords',
                'description' => 'Default meta keywords',
                'type' => 'text',
            ],
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'group' => 'seo',
                'label' => 'Google Analytics ID',
                'description' => 'Your Google Analytics tracking ID',
                'type' => 'text',
            ],
            [
                'key' => 'robots_txt',
                'value' => "User-agent: *\nAllow: /\nSitemap: /sitemap.xml",
                'group' => 'seo',
                'label' => 'Robots.txt',
                'description' => 'Robots.txt content',
                'type' => 'textarea',
            ],

            // Social Media Settings
            [
                'key' => 'facebook_url',
                'value' => '',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Your Facebook page URL',
                'type' => 'text',
            ],
            [
                'key' => 'twitter_url',
                'value' => '',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Your Twitter profile URL',
                'type' => 'text',
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Your Instagram profile URL',
                'type' => 'text',
            ],
            [
                'key' => 'linkedin_url',
                'value' => '',
                'group' => 'social',
                'label' => 'LinkedIn URL',
                'description' => 'Your LinkedIn profile URL',
                'type' => 'text',
            ],
            [
                'key' => 'youtube_url',
                'value' => '',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'Your YouTube channel URL',
                'type' => 'text',
            ],
            [
                'key' => 'tiktok_url',
                'value' => '',
                'group' => 'social',
                'label' => 'TikTok URL',
                'description' => 'Your TikTok profile URL',
                'type' => 'text',
            ],

            // API Settings
            [
                'key' => 'api_rate_limit',
                'value' => '100',
                'group' => 'api',
                'label' => 'Rate Limit',
                'description' => 'API requests per minute',
                'type' => 'number',
            ],
            [
                'key' => 'api_timeout',
                'value' => '30',
                'group' => 'api',
                'label' => 'Timeout (seconds)',
                'description' => 'API request timeout in seconds',
                'type' => 'number',
            ],
            [
                'key' => 'openai_api_key',
                'value' => '',
                'group' => 'api',
                'label' => 'OpenAI API Key',
                'description' => 'Your OpenAI API key for AI features',
                'type' => 'text',
            ],

            // Security Settings
            [
                'key' => 'force_https',
                'value' => '1',
                'group' => 'security',
                'label' => 'Force HTTPS',
                'description' => 'Force all connections to use HTTPS',
                'type' => 'boolean',
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'group' => 'security',
                'label' => 'Minimum Password Length',
                'description' => 'Minimum characters for user passwords',
                'type' => 'number',
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'group' => 'security',
                'label' => 'Session Timeout (minutes)',
                'description' => 'Minutes before session expires',
                'type' => 'number',
            ],
            [
                'key' => 'enable_two_factor',
                'value' => '0',
                'group' => 'security',
                'label' => 'Enable Two-Factor Authentication',
                'description' => 'Require 2FA for admin users',
                'type' => 'boolean',
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'group' => 'security',
                'label' => 'Max Login Attempts',
                'description' => 'Maximum failed login attempts before lockout',
                'type' => 'number',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
