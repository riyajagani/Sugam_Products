<?php
session_start();
include('server/connection.php');
if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
}
if(isset($_GET['logout']))
{
    if(isset($_SESSION['logged_in']))
    {
        unset($_SESSION['loggen_in']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        header('location: login.php');
        exit;
    }
}

if(isset($_POST['change_password']))
{
    $password= $_POST['password'];
    $confirmPassword=$_POST['confirmPassword'];
    $user_email=$_SESSION['user_email'];
    if($password !== $confirmPassword){
        header('location: Account.php?error=Passwords dont match');
    }
    else if(strlen($password)<6){
        header('location: Account.php?error=Password must be at least 6 charachters');
    }
       
        else{
           $stmt= $conn->prepare("UPDATE users  SET user_password=? WHERE user_email =?");
            $stmt->bind_param('ss',$password,$user_email);
            if($stmt->execute())
            {
                header('location: Account.php?message=password has been updated successfully');
            }
            else{
                header('location: Account.php?error=could not update password');
            }
        }
    
}
if(isset($_SESSION['logged_in']))
{
    $user_id=$_SESSION['user_id'];
    $stmt= $conn->prepare("SELECT * FROM orders WHERE user_id=? ");
    $stmt->bind_param('i',$user_id);
    $stmt->execute();
    $orders=$stmt->get_result();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
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


    <!-- account -->
    <section class="my-5 py-5">
        <div class="row container mx-auto">
            <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                <h3 class="font-weight-bold">Account Info</h3>
                <hr class="mx-auto">
                <div class="account-info">
                    <p>Name <span><?php if(isset($_SESSION['user_name'])){echo $_SESSION['user_name'];}?></span></p>
                    <p>Email <span><?php if(isset($_SESSION['user_email'])) {echo $_SESSION['user_email'];}?></span></p>
                    <p><a href="#orders" id="orders-btn"> Your Orders</a></p>
                    <p><a href="Account.php?logout=1" id="logout-btn">Log Out</a></p>
                </div>
            </div>

            <div class="col-lg-6 col-md-10 col-sm-12">
                <form action="" id="account-form" method="POST" action="Account.php">
                    <p class="text-center" style="color: red"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                    <p class="text-center" style="color: green"><?php if(isset($_GET['message'])){echo $_GET['message'];}?></p>
                    <h3>Change Password</h3>
                    <hr class="mx-auto">
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" id="account-password" placeholder="Password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" class="form-control" id="account-password-confirm" placeholder="Password" name="confirmPassword" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn">
                    </div>
                </form>
            </div>
        </div>
    </section>





<!-- Orders -->
<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Your Orders</h2>
        <hr class="mx-auto">
        <table class="mt-5 pt-5">
            <tr>
                <th>Order id</th>
                <th>Order Cost</th>
                <th>Order Status</th>
                <th>Date</th>
            </tr>
            <?php while($orders->fetch_assoc()){?>
            <tr>
                <td>
                    <div class="product-info">
                        <!-- <img src="product3.png" alt=""> -->
                        <div>
                            <p class="mt-3"><?php echo $row['order_id'];?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <span><?php echo $row['order_cost'];?></span>
                </td>

                <td>
                    <span><?php echo $row['order_status'];?></span>
                </td>
                <td>
                    <span><?php echo $row['order_date'];?></span>
                </td>

              
            </tr>
            <?php } ?>
       
            
            
        </table>

        

    </div>

    
</section>














    <footer class="footer">
        <div class="footer-content">
            <div>
                <h3>About Us</h3>
                <p>Sugam Products specializes in manufacturing high-quality beverages and ice creams, delivering
                    refreshing
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