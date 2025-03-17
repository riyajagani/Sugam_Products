<?php
include('header.php');
?>

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
        include 'server/getFeaturedProducts.php'; ?>

        <?php while ($row = $featured_products->fetch_assoc()) { ?>


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
                <a href=<?php echo "pineapple_softdrink.php?product_id=" . $row['product_id']; ?>><button class="buy-btn">BUY NOW</button></a>
            </div>
        <?php } ?>
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

<?php include('footer.php'); ?>