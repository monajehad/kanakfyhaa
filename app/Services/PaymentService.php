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

    /**
     * Check if Cash on Delivery is enabled
     */
    public static function isCodEnabled()
    {
        return (bool) Helpers::getSetting('enable_cod', '1');
    }

    /**
     * Get list of countries that support COD
     * Returns array of country ISO codes
     */
    public static function getCodSupportedCountries()
    {
        $countries = Helpers::getSetting('cod_supported_countries', 'PS,JO,SA,AE,EG,LB');
        return array_map('trim', explode(',', $countries));
    }

    /**
     * Check if COD is supported in a specific country
     *
     * @param string $countryCode ISO2 country code
     * @return bool
     */
    public static function isCodSupportedInCountry($countryCode)
    {
        if (!self::isCodEnabled()) {
            return false;
        }

        $supportedCountries = self::getCodSupportedCountries();
        return in_array(strtoupper($countryCode), array_map('strtoupper', $supportedCountries));
    }
}
