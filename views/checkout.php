<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="checkout-container">
    <h1>Checkout</h1>
    <div class="product-info">
        <h2>Sample Product</h2>
        <p>Price: $100.00 USD</p>
    </div>
    
    <div id="paypal-button-container"></div>
    
    <div id="payment-status" style="display: none; margin-top: 20px;">
        <h3>Processing your payment...</h3>
        <div class="loader"></div>
    </div>
</div>
<!-- views/checkout.php -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT_ID ?>&currency=ZAR"></script>
<div id="paypal-button-container"></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch('<?php echo BASE_URL; ?>/create-order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            }).then(function(res) {
                return res.json();
            }).then(function(data) {
                return data.orderID;
            });
        },
        onApprove: function(data, actions) {
            document.getElementById('payment-status').style.display = 'block';
            
            return fetch('<?php echo BASE_URL; ?>/capture-order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            }).then(function(res) {
                return res.json();
            }).then(function(details) {
                window.location.href = '<?php echo BASE_URL; ?>/success.php?orderID=' + data.orderID;
            });
        },
        onError: function(err) {
            console.error('PayPal error:', err);
            window.location.href = '<?php echo BASE_URL; ?>/error.php';
        }
    }).render('#paypal-button-container');
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>