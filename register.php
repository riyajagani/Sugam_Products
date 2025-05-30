
<?php

session_start();
include('server/connection.php');

if(isset($_POST['register'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirmPassword=$_POST['confirmPassword'];
    if($password !== $confirmPassword){
        header('location: register.php?error=Passwords dont match');
    }
    else if(strlen($password)<6){
        header('location: register.php?error=Password must be at least 6 charachters');
    }

    else{
        $stmt1=$conn->prepare("SELECT count(*) FROM users where user_email=?");
        $stmt1->bind_param('s',$email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();
    
        if($num_rows !=0){
            header('location: register.php?error=User with this email already exists!');
        }
    
        else{
            $stmt=$conn->prepare("INSERT INTO users (user_name,user_email,user_password)
                        VALUES (?,?,?)");
    
            $stmt->bind_param('sss',$name,$email,$password);
            
            if($stmt->execute()){
                $user_id=$stmt->insert_id;
                $_SESSION['user_id']=$user_id;
                $_SESSION['user_email']=$email;
                $_SESSION['user_name']=$name;
                $_SESSION['logged_in']=true;
                header('location: Account.php?register=You registered successfully!');

            }else{
                header('location:register.php?error=Could not create an account at the moment!');
            }
        }
        
    }


   



}else if(isset($_SESSION['logged_in'])){
    header('location: Account.php');
    exit;
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">contact us</a>
                    </li>
                    <li class="nav-item">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <i class="fa-solid fa-user"></i>
                    </li>



            </div>
        </div>
    </nav>


    <!-- register -->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="from-weight-bold">Register</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form id="register-form" action="register.php" method="POST">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="form-group">
                    <label> Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label> Email</label>
                    <input type="text" class="form-control" id="register-email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label> Password</label>
                    <input type="password" class="form-control" id="register-password" name="password"
                        placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder=" confirmPassword" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" id="register-btn" name="register" value="Register">
                </div>
                <div class="form-group">
                    <a id="login-url" class="btn" href="login.php">Do you have an account? Login </a>
                </div>
            </form>
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