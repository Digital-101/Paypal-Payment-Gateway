<?php
require_once __DIR__ . '/../config/PayPalClient.php';
require_once __DIR__ . '/../models/Payment.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentController {
    public function createOrder() {
        try {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [[
                    "reference_id" => "test_ref_id1",
                    "amount" => [
                        "value" => "100.00",
                        "currency_code" => "USD"
                    ]
                ]],
                "application_context" => [
                    "cancel_url" => BASE_URL . "/cancel.php",
                    "return_url" => BASE_URL . "/success.php"
                ] 
            ];

            $client = PayPalClient::client();
            $response = $client->execute($request);
            
            // Save payment to database
            $payment = new Payment();
            $paymentData = [
                'payment_id' => $response->result->id,
                'intent' => $response->result->intent,
                'status' => $response->result->status,
                'amount' => $response->result->purchase_units[0]->amount->value,
                'currency' => $response->result->purchase_units[0]->amount->currency_code,
                'payer_email' => '',
                'create_time' => $response->result->create_time,
                'update_time' => $response->result->update_time ?? $response->result->create_time
            ];
            $payment->create($paymentData);

            header('Content-Type: application/json');
            echo json_encode(['orderID' => $response->result->id]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function captureOrder($orderId) {
        try {
            $request = new OrdersCaptureRequest($orderId);
            $request->prefer('return=representation');
            
            $client = PayPalClient::client();
            $response = $client->execute($request);
            
            // Update payment status
            $payment = new Payment();
            $payment->updateStatus($orderId, $response->result->status);
            
            // Get payer email
            $payerEmail = '';
            if (isset($response->result->payer->email_address)) {
                $payerEmail = $response->result->payer->email_address;
            }
            
            // Update payer email
            $this->updatePayerEmail($orderId, $payerEmail);
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'orderID' => $orderId,
                'payerEmail' => $payerEmail,
                'details' => $response->result
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private function updatePayerEmail($orderId, $email) {
        $payment = new Payment();
        $payment->db->query('UPDATE payments SET payer_email = :email WHERE payment_id = :order_id');
        $payment->db->bind(':email', $email);
        $payment->db->bind(':order_id', $orderId);
        $payment->db->execute();
    }
}