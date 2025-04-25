
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['razorpay_payment_id'])) {
    $payment_id = $_POST['razorpay_payment_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $amount = $_POST['amount'];

    // TODO: Save the order/payment info to DB if needed

    echo "<h2>Payment Successful!</h2>";
    echo "<p>Payment ID: $payment_id</p>";
    echo "<p>Name: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Phone: $phone</p>";
    echo "<p>Amount Paid: ₹" . ($order_total_price / 100) . "</p>";
} else {
    echo "Payment Failed!";
}
?>
