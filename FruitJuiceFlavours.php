<?php

include('server/connection.php');
 $stmt=$conn->prepare("SELECT * FROM products WHERE product_category = 'Fruit-Juice Flavours'");
 $stmt->execute();
 $products= $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" />
    <style>
        .product img{
            width: 100%;
            height: auto;
            box-sizing: border-box;
            object-fit: cover;
        }
        .pagination a{
            color: coral;
        }
        .pagination li:hover a{
            color: white;
            background-color: coral;
        }
    </style>
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
                    <a href="Cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="Account.php"><i class="fa-solid fa-user"></i></a>
                    </li>
    
    
    
            </div>
        </div>
    </nav>


    <section id="shop" class="my-5 py-5 ">
        <div class="container mt-5 py-5 ">
            <h3>OUR PRODUCTS</h3>
            <hr>
            <p>Here you can check out our products </p>
        </div>
        <div class="row mx-auto container">
        
        <?php while($row=$products->fetch_assoc()) {?>
                <div onclick="window.location.href='pineapple_softdrink.html';" class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid" src="<?php echo $row['product_image1'];?>"/>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
      
                    <h4 class="p-price">₹<?php echo $row['product_price'];?></h4>
                    <a class="btn shop-buy-btn" href="pineapple_softdrink.php?product_id=<?php echo $row['product_id']; ?>">BUY NOW</a>


                </div>       
            <?php } ?>



            <nav aria-label="Page navigation example">
                <ul class="pagination mt-5">
                    <li class="page-item"><a class="page-link"  href="/">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="/">1</a></li>
                    <li class="page-item"><a class="page-link" href="/">2</a></li>
                    <li class="page-item"><a class="page-link" href="/">3</a></li>
                    <li class="page-item"><a class="page-link" href="/">next</a></li>
                </ul>
            </nav>
        </div>
        <hr>
    </section>


    <?php include('footer.php');?>