<?php 

session_start();
include('connection.php'); 

if(!isset($_SESSION['logged_in']))
{
    header('location: ../checkout.php?message=Please Login/Register to place an order');
    exit;
}
else
{
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $order_cost = $_SESSION['total'] ?? 0; 
    $order_status = "not paid";
    $user_id  = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');

    // Check if a similar order already exists to prevent duplicates
    $check_stmt = $conn->prepare("SELECT order_id FROM orders WHERE user_id = ? AND order_cost = ? AND order_status = ? LIMIT 1");
    $check_stmt->bind_param('ids', $user_id, $order_cost, $order_status);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    // if ($check_stmt->num_rows > 0) {
    //     echo "Duplicate order detected. Order not placed.";
    //     exit;
    // }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('dsiisss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        if (isset($_SESSION['Cart']) && is_array($_SESSION['Cart']) && count($_SESSION['Cart']) > 0) {
            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            foreach($_SESSION['Cart'] as $product) {
                $stmt1->bind_param('iissdiss', $order_id, $product['product_id'], $product['product_name'], $product['product_image1'], 
                                    $product['product_price'], $product['product_quantity'], $user_id, $order_date);
                $stmt1->execute();
            }
            $stmt1->close();
        } else {
            throw new Exception("Cart is empty! Cannot place order.");
        }
        $conn->commit();
        unset($_SESSION['Cart']);
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
    $stmt->close();
    $conn->close();
    header('Location: ../payment.php?order_status=order placed successfully');
    exit();
}
}?>
