<?php 
require_once __DIR__ . '/../models/Payment.php';

$orderId = $_GET['orderID'] ?? '';
$payment = new Payment();
$paymentDetails = $payment->getPaymentById($orderId);

require_once __DIR__ . '/layout/header.php'; 
?>

<div class="payment-status success">
    <h1>Payment Successful!</h1>
    <div class="payment-details">
        <?php if ($paymentDetails): ?>
            <p>Order ID: <?php echo htmlspecialchars($paymentDetails->payment_id); ?></p>
            <p>Amount: $<?php echo htmlspecialchars($paymentDetails->amount); ?> <?php echo htmlspecialchars($paymentDetails->currency); ?></p>
            <p>Status: <?php echo htmlspecialchars($paymentDetails->status); ?></p>
            <?php if ($paymentDetails->payer_email): ?>
                <p>Payer Email: <?php echo htmlspecialchars($paymentDetails->payer_email); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>Payment details not found.</p>
        <?php endif; ?>
    </div>
    <a href="<?php echo BASE_URL; ?>" class="btn">Return Home</a>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>