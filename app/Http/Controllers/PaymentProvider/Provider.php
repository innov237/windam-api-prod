<?php

namespace App\Http\Controllers\PaymentProvider;

use App\Http\Controllers\Controller;
class Provider extends Controller
{
    public const PAYPAL=1;
    public const STRIPE=2;

    private function loadProvider(): BaseController
    {

        switch (\request()->provider_id) {
            case PAYPAL:
                return new PayPalController();
            case STRIPE:
                return new StripeController();
            default:
                return new PayPalController();
        }
    }
}
