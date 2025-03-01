<?php
    session_start();
    if(isset($_POST['add_to_cart'])){
        if(isset($_SESSION['Cart'])){
            $products_array_ids = array_column($_SESSION['Cart'], "product_id");
            if(!in_array($_POST['product_id'], $products_array_ids)) {
                $product_id = $_POST['product_id'];
                $product_array  = array(
                    'product_id'=>$_POST['product_id'],
                    'product_name'=>$_POST['product_name'],
                    'product_price'=>$_POST['product_price'],
                    'product_image1'=>$_POST['product_image1'],
                    'product_quantity'=>$_POST['product_quantity'],
                );

                $_SESSION['Cart'][$product_id] = $product_array;

            }else{
                echo '<script>alert("Product was already added to cart");</script>';
               
            }
        }else{
            $product_id=$_POST['product_id'];
            $product_name=$_POST['product_name'];
            $product_price=$_POST['product_price'];
            $product_image1=$_POST['product_image1'];
            $product_quantity=$_POST['product_quantity'];

            $product_array  = array(
                'product_id'=>$product_id,
                'product_name'=>$product_name,
                'product_price'=>$product_price,
                'product_image1'=>$product_image1,
                'product_quantity'=>$product_quantity,
            );

            $_SESSION['Cart'][$product_id] = $product_array;
        }
    }else if(isset($_POST['remove_product'])){
        $product_id = $_POST['product_id'];
        unset($_SESSION['Cart'][$product_id]);
    }else{
        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 fixed-top">
    <div class="container">
        <!-- add logo -->
        <!-- <img src="assets/imags/logo.png" /> -->
        <a class="navbar-brand" href="#">SUGAM PRODUCTS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shop.html">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">contact us</a>
                </li>
                <li class="nav-item">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <i class="fa-solid fa-user"></i>
                </li>



        </div>
    </div>
</nav>


<!-- cart -->
 <section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bolde">Your Cart</h2>
        <hr>
        <table class="mt-5 pt-5">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach($_SESSION['Cart'] as $key => $value){?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="<?php  echo $value['product_image1'];?>" >
                        <div>
                            <p><?php  echo $value['product_name'];?></p>
                            <small><span> Rupees </span><?php  echo $value['product_price'];?></small>
                            <br>
                            <form action="Cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $value['product_id']?>"/>
                                <input class="remove-btn" type="submit" name="remove_product" value="remove"/>
                            </form>
                            
                        </div>
                    </div>
                </td>
                <td>
                    <input type="number" value="<?php  echo $value['product_quantity'];?>" >
                    <!-- <a class="edit-btn" href="#">Edit</a> -->
                </td>

                <td>
                    <span>Rupees</span>
                    <span class="product-price"></span>
                </td>
                
            </tr>
            <?php } ?>
            
        </table>

        <div class="cart-total">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td>Rupees 200</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td>Rupees 200</td>
                </tr>
            </table>
        </div>
        
    </div>

    <div class="checkout-container">
        <button class="btn checkout-btn">Checkout</button>
    </div>
 </section>














<footer class="footer">
    <div class="footer-content">
        <div>
            <h3>About Us</h3>
            <p>Sugam Products specializes in manufacturing high-quality beverages and ice creams, delivering refreshing
                experiences since establishment.</p>
        </div>
        <div>
            <h3>Variety</h3>
            <ul>
                <li>FOOD-FLAVOUR</li>
                <li>ICE-CREAM FLAVOUR</li>
                <li>SOFT-DRINK</li>
                <LI>COLA SOFT-DRINK</LI>
                <li>WHISKEY FLAVOUR</li>
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
        <div class="col-lg-3 col-md-5 col-sm-12 mb-4 text-nowrap mb-2 ">
            <p>SugamProducts @ 2025 ALL Right Reserved</p>
        </div>
        <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
    </div>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
    
</html>