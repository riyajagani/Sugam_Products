


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3 fixed-top">
        <div class="container">
            <!-- add logo -->
            <!-- <img src="assets/imags/logo.png" /> -->
            <a class="navbar-brand" href="#">SUGAM PRODUCTS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link" href="contact.html">contact us</a>
                    </li>
                    <li class="nav-item">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <i class="fa-solid fa-user"></i>
                    </li>



            </div>
        </div>
    </nav>

    <!--Homw-->
    <section id="home">
        <div class="container">
            <h5>NEW VARIETY</h5>
            <h1><span>BEST PRICES</span> FOR THIS SEASONS</h1>
            <p>Sugam Products offers the best products for the most affordable prices</p>
            <button>SHOP NOW</button>
        </div>
    </section>

    <!--random-->
    <section id="product" class="container my-5 pb-5">
        <div class="row">
            <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="ice-cream.png">
            <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="mixed.png">
            <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="bag.png">
            <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="whiskey.png">

        </div>
        <hr>
    </section>

    <section id="new" class="w-100 my-5 pb-5">
        <div class="row p-0 m-0">
            <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
                <img class="img-fluid" src="falsa.png">
                <div class="details">
                    <h2>Fruit-Juice Flavours</h2>
                    <button class="text-uppercase">BUY NOW</button>
                </div>
            </div>

            <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
                <img class="img-fluid" src="ice-cream.png">
                <div class="details">
                    <h2>Ice-Cream Flavours</h2>
                    <button class="text-uppercase">BUY NOW</button>
                </div>
            </div>

            <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
                <img class="img-fluid" src="soft-drink.png">
                <div class="details">
                    <h2>Soft-Drink Flavours</h2>
                    <button class="text-uppercase">BUY NOW</button>
                </div>
            </div>

        </div>
    </section>

    <section id="featured" class=" ">
        <div class="container text-center ">
            <h3>OUR FEATURED</h3>
            <hr>
            <p>Here you can check out our featured products </p>
        </div>
        <div class="row max-auto container-fluid">
        <?php
            // Include the file that retrieves featured products
            include 'server/getFeaturedProducts.php';?>

        <?php    while ($row = $featured_products->fetch_assoc()) { ?>
                

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid" src="<?php echo $row['product_image1']; ?>" alt="<?php echo $row['product_name']; ?>">
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price"><?php echo $row['product_price']; ?>rs/pkt</h4>
               <a href=<?php echo "pineapple_softdrink.php?product_id=". $row['product_id'];?>><button class="buy-btn">BUY NOW</button></a> 
            </div>
            <?php }?>
        </div>
        <hr>
    </section>

    <section id="banner" class="my-5 py-5">
        <div class="container">
            <h4><span>SEASON's SALE</span> </h4>
            <h1><span>Autumn Collection <br> UP to 15% OFF</span></h1>
            <button class="text-uppercase">Shop Now</button>
        </div>
    </section>




    <footer class="footer">
        <div class="footer-content">
            <div>
                <h3>About Us</h3>
                <p>Sugam Products specializes in manufacturing high-quality beverages and ice creams, delivering refreshing experiences since establishment.</p>
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





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>