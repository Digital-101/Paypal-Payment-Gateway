<?php
require '../config/config.php';
require '../vendor/autoload.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

$request = new OrdersCreateRequest();
$request->prefer('return=representation');
$request->body = [
  "intent" => "CAPTURE",
  "purchase_units" => [[
    "amount" => [
      "currency_code" => "ZAR",
      "value" => "100.00" // Set your amount
    ]
  ]],
  "application_context" => [
    "cancel_url" => BASE_URL . "/cancel.php",
    "return_url" => BASE_URL . "/success.php"
  ]
];

try {
  $client = PayPalClient::client();
  $response = $client->execute($request);
  echo json_encode(['id' => $response->result->id]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}