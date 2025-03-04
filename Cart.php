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
    foreach ($_SESSION['Cart'] as $product) {
        $total += $product['product_price'] * $product['product_quantity'];
    }
    $_SESSION['total'] = $total;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">SUGAM PRODUCTS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.html">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                <li class="nav-item"><i class="fa-solid fa-cart-shopping"></i> <i class="fa-solid fa-user"></i></li>
            </ul>
        </div>
    </div>
</nav>

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

<footer class="footer">
    <div class="footer-content">
        <div>
            <h3>About Us</h3>
            <p>Sugam Products specializes in manufacturing high-quality beverages and ice creams.</p>
        </div>
        <div>
            <h3>Variety</h3>
            <ul>
                <li>Food-Flavour</li>
                <li>Ice-Cream Flavour</li>
                <li>Soft-Drink</li>
                <li>Cola Soft-Drink</li>
                <li>Whiskey Flavour</li>
            </ul>
        </div>
        <div>
            <h3>Contact</h3>
            <p>Metoda, Rajkot, Gujarat</p>
            <p>Phone: 08048966407</p>
            <p>GST No. 24ABZFS3887K1Z3</p>
        </div>
        <div>
            <h3>Quick Links</h3>
            <ul>
                <li>Profile</li>
                <li>Products</li>
                <li>Contact Us</li>
            </ul>
        </div>
        <div>
            <p>SugamProducts @ 2025 ALL Right Reserved</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
