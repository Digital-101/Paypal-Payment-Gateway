<?php
require __DIR__ . '/../vendor/autoload.php';

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient {
    public static function client() {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment() {
        if (PAYPAL_ENVIRONMENT === 'sandbox') {
            return new SandboxEnvironment(PAYPAL_CLIENT_ID, PAYPAL_SECRET);
        } else {
            return new ProductionEnvironment(PAYPAL_CLIENT_ID, PAYPAL_SECRET);
        }
    }
}