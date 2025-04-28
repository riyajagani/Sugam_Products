<?php
    include('server/connection.php');

    $order_details = null; 

    if(isset($_GET['order_id'])){
        $order_id = $_GET['order_id'];
        $order_status = $_GET['order_status'];

        // Fix: Use DISTINCT to prevent duplicate entries
        $stmt = $conn->prepare("
            SELECT DISTINCT order_items.product_id, order_items.product_price, order_items.product_quantity, 
                            products.product_name, products.product_image1 
            FROM order_items 
            JOIN products ON order_items.product_id = products.product_id 
            WHERE order_items.order_id = ?
        ");
        

        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $order_details = $stmt->get_result();
        $order_total_price = calculateTotalOrderPrice($order_details);
    } else {
        header('location: Account.php'); 
        exit;
    }

    function calculateTotalOrderPrice($order_details)
    {
        $total = 0;
        foreach($order_details as $row){
            $product_price = $row['product_price'];
            $product_quantity = $row['product_quantity'];
            $total += ($product_price * $product_quantity);
        }
        return $total;
    }
?>

<?php include('header.php'); ?>

<!-- Order Details -->
<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Order Details</h2>
        <hr class="mx-auto">
        <table class="mt-5 pt-5 mx-auto">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>

            <?php foreach($order_details as $row) { ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="<?php echo $row['product_image1']; ?>" alt="Product Image"/>
                            <div>
                                <p class="mt-3"><?php echo $row['product_name']; ?></p>
                            </div>
                       </div>
                    </td>
                    <td><span>₹ <?php echo $row['product_price']; ?></span></td>
                    <td><span><?php echo $row['product_quantity']; ?></span></td>
                </tr>
            <?php } ?>
        </table>

        <?php if($order_status == "not paid") { ?>
            <form action="payment.php" method="POST">
                <input type="hidden" name="order_total_price" value="<?php echo $order_total_price; ?>"/>
                <input type="hidden" name="order_status" value="<?php echo $order_status; ?>"/>
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                 <input type="hidden" name="email" value="<?php echo $email; ?>">
                 <input type="hidden" name="phone" value="<?php echo $phone; ?>">
                 <input type="submit" class="btn checkout-btn" value="pay now" name="pay now">
            </form>
        <?php } ?> 
    </div>
</section>

<?php include('footer.php'); ?>
