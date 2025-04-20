<?php
require_once __DIR__ . '/../models/Payment.php';

class WebhookController {
    public function handle() {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            die('Invalid JSON');
        }
        
        // Verify webhook signature (implementation depends on your PayPal setup)
        // This is a simplified version - in production, implement proper verification
        
        $eventType = $data['event_type'] ?? '';
        $resource = $data['resource'] ?? [];
        $paymentId = $resource['id'] ?? '';
        
        if (empty($paymentId)) {
            http_response_code(400);
            die('Missing payment ID');
        }
        
        $payment = new Payment();
        $existingPayment = $payment->getPaymentById($paymentId);
        
        if (!$existingPayment) {
            http_response_code(404);
            die('Payment not found');
        }
        
        switch ($eventType) {
            case 'PAYMENT.CAPTURE.COMPLETED':
                $payment->updateStatus($paymentId, 'COMPLETED');
                break;
            case 'PAYMENT.CAPTURE.DENIED':
                $payment->updateStatus($paymentId, 'DENIED');
                break;
            case 'PAYMENT.CAPTURE.PENDING':
                $payment->updateStatus($paymentId, 'PENDING');
                break;
            case 'PAYMENT.CAPTURE.REFUNDED':
                $payment->updateStatus($paymentId, 'REFUNDED');
                break;
            default:
                // Log unhandled event types
                break;
        }
        
        http_response_code(200);
        echo 'Webhook processed';
    }
}