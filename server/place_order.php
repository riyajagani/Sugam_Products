<?php 

session_start();
include('connection.php'); 

// Check if database connection is valid
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['place_order'])) {

    // Get user input from form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    // Ensure 'total' exists in session
    $order_cost = $_SESSION['total'] ?? 0; 
    $order_status = "not paid";
    $user_id  = $_SESSION['user_id']; // This should be dynamically set based on the logged-in user
    $order_date = date('Y-m-d H:i:s');

    // Start transaction to ensure consistency
    $conn->begin_transaction();

    try {
        // Insert order into orders table
        $stmt = $conn->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Query preparation failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param('dsiisss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

        // Execute query
        if (!$stmt->execute()) {
            throw new Exception("Error placing order: " . $stmt->error);
        }

        // Get the generated order_id
        $order_id = $stmt->insert_id;

        // Ensure the Cart is not empty before inserting items
        if (isset($_SESSION['Cart']) && is_array($_SESSION['Cart']) && count($_SESSION['Cart']) > 0) {
            
            // Prepare statement for inserting into order_items
            $stmt1 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt1) {
                throw new Exception("Query preparation failed: " . $conn->error);
            }

            // Loop through cart items and insert into order_items
            foreach($_SESSION['Cart'] as $product) {
                if (!isset($product['product_id'], $product['product_name'], $product['product_image1'], $product['product_price'], $product['product_quantity'])) {
                    continue; // Skip if any required field is missing
                }

                $product_id = $product['product_id'];
                $product_name = $product['product_name'];
                $product_image = $product['product_image1'];
                $product_price = $product['product_price'];
                $product_quantity = $product['product_quantity'];

                // Bind parameters and execute for each item
                $stmt1->bind_param('iissdiss', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);
                
                if (!$stmt1->execute()) {
                    throw new Exception("Error inserting order item: " . $stmt1->error);
                }
            }

            // Close statement
            $stmt1->close();

        } else {
            throw new Exception("Cart is empty! Cannot place order.");
        }

        // Commit transaction if everything is successful
        $conn->commit();

        echo "Order placed successfully! Order ID: " . $order_id;

        // Clear the cart after successful order placement
        unset($_SESSION['Cart']);

    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close statements and connection
    $stmt->close();
    $conn->close();

    //unset($_SESSION['Cart']);
    header('location: ../payment.php?order_status=Order Placed Successfully!');
}

?>