
<?php
    session_start();
    if(isset($_POST['order_pay_btn'])){
        $order_status = $_POST['order_status'];
        $order_total_price = $_POST['order_total_price'];
    }

?>

<?php include('header.php');?>

<!-- checkout -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="from-weight-bold">Payment</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container text-center">

        <?php if(isset($_SESSION['total']) && $_SESSION['total'] != 0 ){ ?>
            <p>Total Payments: ₹<?php echo $_SESSION['total']; ?></p>
        <p><?php if(isset($_GET['order_status'])) {echo $_GET['order_status'];}?></p>
        
        <?php if(isset($_SESSION['total']) && $_SESSION['total'] != 0){?>
        <input class="btn btn-primary" value="Pay Now" type="submit"/>
        
        <?php } else {?>
            
            <p>You don't have any order</p>
        <?php } ?>
        
        <p><?php if(isset($_POST['order_status'])){echo $_POST['order_status'];}?></p>

        <?php if(isset($_POST['order_status']) && $_POST['order_status'] == "not paid"){?>
        <input class="btn btn-primary" value="Pay Now" type="submit"/>
        <?php }?>
    </div>
</section>


<?php include('footer.php');?>