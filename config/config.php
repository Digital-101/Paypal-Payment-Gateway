<?php
// PayPal API Configuration
define('PAYPAL_CLIENT_ID', 'YOUR_PAYPAL_CLIENT_ID');
define('PAYPAL_SECRET', 'YOUR_PAYPAL_SECRET');
define('PAYPAL_ENVIRONMENT', 'sandbox'); // or 'production'

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'payment_gateway');

// Base URL
define('BASE_URL', 'http://localhost/paypal-payment-gateway/public');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
