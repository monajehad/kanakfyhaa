<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke()
    {
        $codEnabled = PaymentService::isCodEnabled();
        $codSupportedCountries = PaymentService::getCodSupportedCountries();
        $paymentConfig = PaymentService::getConfig();
        
        return view('website.layout.pages.checkout', [
            'codEnabled' => $codEnabled,
            'codSupportedCountries' => $codSupportedCountries,
            'paymentConfig' => $paymentConfig,
        ]);
    }
}
