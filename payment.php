<?php
session_start();
$apikey = 'rzp_test_PjxtEigHqqGsZm';

// Get the total amount
$totalAmount = 0;
if (isset($_SESSION['total']) && $_SESSION['total'] != 0) {
    $totalAmount = $_SESSION['total'];
} if (isset($_POST['order_status']) && $_POST['order_status'] == "not paid") {
    $totalAmount = $_POST['order_total_price'];
}
?>

<?php include('header.php'); ?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="from-weight-bold">Payment</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container text-center">
        <?php if ($totalAmount > 0) { ?>
            <p>Total Payments: ₹ <?php echo $totalAmount; ?></p>
            <button id="rzp-button" class="btn btn-primary">Pay Now</button>
        <?php } else { ?>
            <p>You don't have an order</p>
        <?php } ?>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "<?php echo $apikey; ?>", // Enter the Key ID generated from the Dashboard
        "amount": "<?php echo $totalAmount * 100; ?>", // Amount is in currency subunits (i.e., paise)
        "currency": "INR",
        "name": "Sugam Products",
        "description": "Order Payment",
        "image": "https://tradiwe.com/img/web-design-development.png",
        "handler": function (response) {
            alert("Payment successful. Payment ID: " + response.razorpay_payment_id);
            // Optionally, redirect or send data to backend for order confirmation
        },
        "prefill": {
            "name": "<?php echo $_SESSION['name'] ?? ''; ?>",
            "email": "<?php echo $_SESSION['email'] ?? ''; ?>",
            "contact": "<?php echo $_SESSION['phone'] ?? ''; ?>"
        },
        "theme": {
            "color": "#0e90e4"
        }
    };

    var rzp1 = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function (e) {
        rzp1.open();
        e.preventDefault();
    };
</script>

<?php include('footer.php'); ?>
