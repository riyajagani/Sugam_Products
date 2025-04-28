
<?php
    include('../server/connection.php'); 
    session_start();

    // Fetch Total Customers
    $customer_query = "SELECT COUNT(*) as total_customers FROM users";
    $customer_result = mysqli_query($conn, $customer_query);
    if (!$customer_result) {
        die("Customer Query Failed: " . mysqli_error($conn));
    }
    $customer_row = mysqli_fetch_assoc($customer_result);
    $total_customers = $customer_row['total_customers'];

    // Fetch Total Products
    $product_query = "SELECT COUNT(*) as total_products FROM products";
    $product_result = mysqli_query($conn, $product_query);
    if (!$product_result) {
        die("Product Query Failed: " . mysqli_error($conn));
    }
    $product_row = mysqli_fetch_assoc($product_result);
    $total_products = $product_row['total_products'];

    // Fetch Total Quantity
    $quantity_query = "SELECT SUM(product_quantity) as total_quantity FROM products";
    $quantity_result = mysqli_query($conn, $quantity_query);
    if (!$quantity_result) {
        die("Quantity Query Failed: " . mysqli_error($conn));
    }
    $quantity_row = mysqli_fetch_assoc($quantity_result);
    $total_quantity = $quantity_row['total_quantity'];

    // Fetch Total Orders
    $order_query = "SELECT COUNT(*) as total_orders FROM orders";
    $order_result = mysqli_query($conn, $order_query);
    if (!$order_result) {
        die("Order Query Failed: " . mysqli_error($conn));
    }
    $order_row = mysqli_fetch_assoc($order_result);
    $total_orders = $order_row['total_orders'];
?>
