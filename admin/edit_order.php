<?php
session_start();

// Database connection - REPLACE WITH YOUR CREDENTIALS
$conn = new mysqli('localhost', 'root', '', 'php_project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE Order_id=?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $orders = $stmt->get_result();
} else if (isset($_POST['edit_order'])) {
    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];

    $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE Order_id=?");
    $stmt->bind_param('si', $order_status, $order_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php?order_updated=Order has been updated successfully');
        exit();
    } else {
        header('Location: dashboard.php?order_failed=Error occurred, Try again!!!');
        exit();
    }
} else {
    header('location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Company Name</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your CSS styles here -->
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #4e73df;
            --light-bg: #f8f9fc;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-color);
            border-bottom: 1px solid #eee;
        }

        .sidebar-item {
            padding: 12px 20px;
            color: #6c757d;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            color: var(--primary-color);
            background: #f5f5f5;
            border-left: 4px solid var(--primary-color);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include('sidemenu.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Dashboard</h2>

        <div class="card mb-4">
            <div class="card-header">Edit Order</div>
            <div class="card-body">
                <form id="edit-order-form" method="POST" action="edit_order.php">
                    <input type="hidden" name="edit_order" value="1">
                    <?php foreach ($orders as $order) { ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Order ID</label>
                                <p class="my-4"><?php echo $order['order_id']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Cost</label>
                                <p class="my-4">₹<?php echo $order['order_cost']; ?></p>
                            </div>
                        </div>
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>" />

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Order Status</label>
                                <select class="form-select" required name="order_status">
                                    <option value="not paid" <?= $order['order_status'] == 'not paid' ? 'selected' : '' ?>>Not Paid</option>
                                    <option value="on_hold" <?= $order['order_status'] == 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                                    <option value="paid" <?= $order['order_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="shipped" <?= $order['order_status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Date</label>
                                <p class="my-4"><?php echo $order['order_date']; ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Customer Phone</label>
                                <p class="my-4"><?php echo $order['user_phone']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Delivery Address</label>
                                <p class="my-4"><?php echo $order['user_city'] . ', ' . $order['user_address']; ?></p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Order</button>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>