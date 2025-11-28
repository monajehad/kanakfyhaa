<?php

namespace App\Services;

use App\Helpers\Helpers;

class EmailService
{
    /**
     * Get email configuration for sending emails
     */
    public static function getConfig()
    {
        return [
            'from_name' => Helpers::getSetting('mail_from_name', 'Kanak Fyhaa'),
            'from_address' => Helpers::getSetting('mail_from_address', 'noreply@kanakfyhaa.com'),
            'contact_email' => Helpers::getSetting('contact_email', 'contact@kanakfyhaa.com'),
            'support_email' => Helpers::getSetting('support_email', 'support@kanakfyhaa.com'),
            'order_notification_email' => Helpers::getSetting('order_notification_email', 'orders@kanakfyhaa.com'),
        ];
    }

    /**
     * Get the from email address
     */
    public static function getFromAddress()
    {
        return Helpers::getSetting('mail_from_address', 'noreply@kanakfyhaa.com');
    }

    /**
     * Get the from name
     */
    public static function getFromName()
    {
        return Helpers::getSetting('mail_from_name', 'Kanak Fyhaa');
    }

    /**
     * Get contact email
     */
    public static function getContactEmail()
    {
        return Helpers::getSetting('contact_email', 'contact@kanakfyhaa.com');
    }

    /**
     * Get support email
     */
    public static function getSupportEmail()
    {
        return Helpers::getSetting('support_email', 'support@kanakfyhaa.com');
    }

    /**
     * Get order notification email
     */
    public static function getOrderNotificationEmail()
    {
        return Helpers::getSetting('order_notification_email', 'orders@kanakfyhaa.com');
    }
}
