<?php
require '../config/config.php';
require '../vendor/autoload.php';

use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

$data = json_decode(file_get_contents('php://input'), true);
$orderID = $data['orderID'] ?? '';

if (empty($orderID)) die(json_encode(['error' => 'Missing Order ID']));

$request = new OrdersCaptureRequest($orderID);

try {
  $client = PayPalClient::client();
  $response = $client->execute($request);
  
  // Save to database
  $paymentData = [
    'payment_id' => $response->result->id,
    'status' => $response->result->status,
    'amount' => $response->result->purchase_units[0]->amount->value
  ];
  // ... (Insert into database)
  
  echo json_encode($response->result);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}