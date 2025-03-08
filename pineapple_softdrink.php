<?php
include('server/connection.php');

if(isset($_GET['product_id']))
{
    $product_id=  $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id= ?");
    $stmt->bind_param("i",$product_id);
    $stmt->execute();
    $product = $stmt->get_result();
} 
else 
{
    header('location: index.php');
    exit(); // Added to prevent further execution after redirect
}
?>
<?php include('header.php');?>

<section class="container single-product my-5 pt-5">
    <div class="row mt-5">
        <?php while($row = $product->fetch_assoc()) { ?>
            
        <div class="col-lg-5 col-md-6 col-sm-12">
            <img class="img-fluid w-100 pb-1" src="<?php echo $row['product_image1']; ?>" id="mainImg">
            
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12">
            <h6>Soft Drink</h6>
            <h3 class="py-4"><?php echo $row['product_name']; ?></h3>
            
            <?php
    // Set the unit based on product category
             $unit = ($row['product_category'] == 'Ice-Cream Flavours') ? 'Litre' : 'Box';
             ?>
             <h2><?php echo $row['product_price']; ?>/<?php echo $unit; ?></h2>

            <form method="POST" action="Cart.php">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id'];?>"/>
            <input type="hidden" name= "product_image1" value="<?php echo $row['product_image1']; ?>"/>  
            <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>"/>
            <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>"/>
            <input type="number" name="product_quantity" value="1"/>
            <button class="buy-btn" type="submit" name="add_to_cart">Add To Cart</button>
            </form>
            
            <h4 class="mt-5 mb-5">Product Details</h4>
            <span><?php echo nl2br($row['product_description']);?> </span>
        </div>
            
        <?php } ?>
    </div>
</section>

<!-- Related Products -->
<section id="related-products">
    <div class="container text-center">
        <h3>Related Products</h3>
        <hr>
    </div>
    <div class="row max-auto container-fluid">
        <?php 
        $related_products = [
            ["image" => "ice-cream.png", "name" => "Ice-Cream", "price" => "80rs/pkt"],
            ["image" => "mixed.png", "name" => "Mixed Flavour", "price" => "100rs/pkt"],
            ["image" => "whiskey.png", "name" => "Whiskey", "price" => "150rs/pkt"],
            ["image" => "mngo.png", "name" => "Mango", "price" => "180rs/pkt"],
        ];

        foreach ($related_products as $product) { ?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid" src="<?php echo $product['image']; ?>">
            <div class="star">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h5 class="p-name"><?php echo $product['name']; ?></h5>
            <h4 class="p-price"><?php echo $product['price']; ?></h4>
            <button class="buy-btn">BUY NOW</button>
        </div>
        <?php } ?>
    </div>
    <hr>
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
                <li>COLA SOFT-DRINK</li>
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
    </div>
</footer>

<script>
var mainImg = document.getElementById("mainImg");
var smallImg = document.getElementsByClassName("small-img");

for (let i = 0; i < smallImg.length; i++) {
    smallImg[i].onclick = function() {
        mainImg.src = smallImg[i].src;
    }
}
</script>

</body>
</html>
