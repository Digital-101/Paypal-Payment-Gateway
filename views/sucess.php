<?php
require '../config/config.php';
require '../models/Payment.php';

$payment = new Payment();
$txn = $payment->getPaymentById($_GET['transaction'] ?? '');

require '../views/layout/header.php';
?>

<div class="success-page">
  <h1>Payment Successful!</h1>
  <p>Transaction ID: <?= htmlspecialchars($txn->payment_id) ?></p>
  <p>Amount: $<?= htmlspecialchars($txn->amount) ?></p>
  <a href="/" class="btn">Continue Shopping</a>
</div>

<?php require '../views/layout/footer.php'; ?>