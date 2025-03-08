

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

<?php include('header.php');?>

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
    <?php include('footer.php');?>