<?php
session_start();

if (!isset($_SESSION['Cart'])) {
    $_SESSION['Cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['Cart'][$product_id])) {
        $_SESSION['Cart'][$product_id] = [
            'product_id' => $product_id,
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price'],
            'product_image1' => $_POST['product_image1'],
            'product_quantity' => $_POST['product_quantity'],
        ];
    } else {
        echo '<script>alert("Product was already added to cart");</script>';
    }

    calculateTotalCart();
} elseif (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['Cart'][$product_id]);
    calculateTotalCart();
} elseif (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    if (isset($_SESSION['Cart'][$product_id])) {
        $_SESSION['Cart'][$product_id]['product_quantity'] = $product_quantity;
    }

    calculateTotalCart();
} else {
    // header('location: index.php');
    //exit;
}

function calculateTotalCart()
{
    if (!isset($_SESSION['Cart']) || empty($_SESSION['Cart'])) {
        $_SESSION['total'] = 0;
        return;
    }

    $total = 0;
    $total_quantity=0;
    foreach ($_SESSION['Cart'] as $product) {
        $total += $product['product_price'] * $product['product_quantity'];
       // $total_quantity=$total_quantity + $ $quantity;
    }
    $_SESSION['total'] = $total;
   // $_SESSION['quantity']=$total_quantity;
}
?>

<?php include('header.php');?>

<!-- cart -->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Your Cart</h2>
        <hr>
        <table class="mt-5 pt-5">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach ($_SESSION['Cart'] as $value) { ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="<?php echo $value['product_image1']; ?>" alt="Product Image">
                            <div>
                                <p><?php echo $value['product_name']; ?></p>
                                <small>Rupees <?php echo $value['product_price']; ?></small>
                                <br>
                                <form action="Cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                                    <input class="remove-btn" type="submit" name="remove_product" value="remove"/>
                                </form>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form method="POST" action="Cart.php">
                            <input type="hidden" value="<?php echo $value['product_id']; ?>" name="product_id"/>
                            <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>">
                            <input type="submit" class="edit_btn" value="edit" name="edit_quantity"/>
                        </form>
                    </td>
                    <td>
                        <span>₹</span>
                        <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price']; ?></span>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <div class="cart-total">
            <table>
                <tr>
                    <td>Total Amount</td>
                    <td>₹ <?php echo isset($_SESSION['total']) ? $_SESSION['total'] : 0; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="checkout-container">
        <form action="checkout.php" method="POST">
            <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout">
        </form>
        
    </div>
</section>

<?php include('footer.php');?>