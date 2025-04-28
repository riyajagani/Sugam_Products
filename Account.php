<?php
session_start();

// Add no-cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('server/connection.php');

// If user is not logged in, redirect to login page
if (!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

// Handle password change
if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    if ($password !== $confirmPassword) {
        header('location: Account.php?error=Passwords do not match');
        exit;
    } else if (strlen($password) < 6) {
        header('location: Account.php?error=Password must be at least 6 characters');
        exit;
    } else {
        $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
        $stmt->bind_param('ss', $password, $user_email);

        if ($stmt->execute()) {
            header('location: Account.php?message=Password has been updated successfully');
            exit;
        } else {
            header('location: Account.php?error=Could not update password');
            exit;
        }
    }
}

// Fetch user orders
if (isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();
}
?>

<?php include('header.php'); ?>

<section class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
            <h3 class="font-weight-bold">Account Info</h3>
            <hr class="mx-auto">
            <div class="account-info">
                <p>Name <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span></p>
                <p>Email <span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span></p>
                <p><a href="#orders" id="orders-btn">Your Orders</a></p>
                <p><a href="logout.php" id="logout-btn">Log Out</a></p>
            </div>
        </div>

        <div class="col-lg-6 col-md-10 col-sm-12">
            <form method="POST" action="Account.php">
                <p class="text-center text-danger">
                    <?php if (isset($_GET['error'])) { echo htmlspecialchars($_GET['error']); } ?>
                </p>
                <p class="text-center text-success">
                    <?php if (isset($_GET['message'])) { echo htmlspecialchars($_GET['message']); } ?>
                </p>
                <h3>Change Password</h3>
                <hr class="mx-auto">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="confirmPassword" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Change Password" name="change_password" class="btn">
                </div>
            </form>
        </div>
    </div>
</section>

<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Your Orders</h2>
        <hr class="mx-auto">
        <table class="mt-5 pt-5">
            <tr>
                <th>Order ID</th>
                <th>Order Cost</th>
                <th>Order Status</th>
                <th>Order Date</th>
                <th>Order Details</th>
            </tr>
            <?php while ($row = $orders->fetch_assoc()) { ?>
            <tr>
                <td><span><?php echo $row['order_id']; ?></span></td>
                <td><span><?php echo $row['order_cost']; ?></span></td>
                <td><span><?php echo $row['order_status']; ?></span></td>
                <td><span><?php echo $row['order_date']; ?></span></td>
                <td>
                    <form method="GET" action="order_details.php">
                        <input type="hidden" name="order_status" value="<?php echo $row['order_status']; ?>">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <input class="btn order-details-btn" type="submit" value="Details">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</section>

<?php include('footer.php'); ?>
