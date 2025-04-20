<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple router
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = str_replace('/public', '', BASE_URL);

// Remove base path from request
$request = str_replace($basePath, '', $request);

// Handle empty request
if ($request === '') {
    $request = '/';
}

switch ($request) {
    case '/':
    case '/checkout':
        require __DIR__ . '/../views/checkout.php';
        break;
    case '/create-order':
        $paymentController = new PaymentController();
        $paymentController->createOrder();
        break;
    case '/capture-order':
        $orderId = json_decode(file_get_contents('php://input'), true)['orderID'] ?? '';
        if ($orderId) {
            $paymentController = new PaymentController();
            $paymentController->captureOrder($orderId);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing order ID']);
        }
        break;
    case '/success':
        require __DIR__ . '/../views/payment-success.php';
        break;
    case '/webhook':
        $webhookController = new WebhookController();
        $webhookController->handle();
        break;
    default:
        http_response_code(404);
        if (file_exists(__DIR__ . '/../views/404.php')) {
            require __DIR__ . '/../views/404.php';
        } else {
            // Fallback error message if 404.php doesn't exist
            header('Content-Type: text/plain');
            echo '404 - Page Not Found';
        }
        break;
}