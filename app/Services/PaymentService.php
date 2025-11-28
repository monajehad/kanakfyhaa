<?php

namespace App\Services;

use App\Helpers\Helpers;

class PaymentService
{
    /**
     * Get payment configuration
     */
    public static function getConfig()
    {
        return [
            'gateway' => self::getGateway(),
            'paypal' => self::getPayPalConfig(),
            'stripe' => self::getStripeConfig(),
        ];
    }

    /**
     * Get the active payment gateway
     */
    public static function getGateway()
    {
        return Helpers::getSetting('payment_gateway', 'paypal');
    }

    /**
     * Get PayPal configuration
     */
    public static function getPayPalConfig()
    {
        return [
            'mode' => Helpers::getSetting('paypal_mode', 'sandbox'),
            'client_id' => Helpers::getSetting('paypal_client_id', ''),
            'secret' => Helpers::getSetting('paypal_secret', ''),
        ];
    }

    /**
     * Get Stripe configuration
     */
    public static function getStripeConfig()
    {
        return [
            'key' => Helpers::getSetting('stripe_key', ''),
            'secret' => Helpers::getSetting('stripe_secret', ''),
        ];
    }

    /**
     * Check if PayPal is enabled
     */
    public static function isPayPalEnabled()
    {
        return self::getGateway() === 'paypal' && !empty(self::getPayPalConfig()['client_id']);
    }

    /**
     * Check if Stripe is enabled
     */
    public static function isStripeEnabled()
    {
        return self::getGateway() === 'stripe' && !empty(self::getStripeConfig()['key']);
    }

    /**
     * Get the active gateway configuration
     */
    public static function getActiveConfig()
    {
        $gateway = self::getGateway();

        if ($gateway === 'stripe') {
            return self::getStripeConfig();
        }

        return self::getPayPalConfig();
    }
}
