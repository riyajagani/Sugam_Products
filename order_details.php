

<?php

    include('server/connection.php');
    $order_details = null; 
    if( isset($_GET['order_id'])){
        $order_id = $_GET['order_id'];
        $order_status = $_GET['order_status'];
        $stmt=$conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->bind_param('i',$order_id);
        $stmt->execute();
        $order_details=$stmt->get_result();
    }
    else{
       header('location: Account.php'); 
       exit;
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
            <a class="navbar-brand" href="#">SUGAM PRODUCTS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.html">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                    <li class="nav-item">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <i class="fa-solid fa-user"></i>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- order details -->
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
            <?php while($row = $order_details->fetch_assoc()){?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="<?php echo $row['product_image']?>">
                            <div>
                                <p class="mt-3"><?php echo $row['product_name']?></p>
                            </div>
                        </div>
                        
                    </td>
                    <td><span><?php echo $row['product_price']?></span></td>
                    <td><span><?php echo $row['product_quantity']?></span></td>
                    
                    
                </tr>
                
            <?php } ?>

            </table>
                
            <?php if($order_status == "not paid"){?>

                <form style = "float:right;" action="">
                    <input type="submit" class="btn btn-primary" value="Pay Now"/>
                </form>

            <?php } ?>

           
        </div>
    </section>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>