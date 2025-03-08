
<?php
    session_start();
   

?>

<?php include('header.php');?>

<!-- checkout -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="from-weight-bold">Payment</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container text-center">
        <p><?php if(isset($_GET['order_status'])) {echo $_GET['order_status'];}?></p>
        <p>Total Payments: ₹<?php  if(isset($_SESSION['total'])) {echo $_SESSION['total'];}?></p>
        <?php if(isset($_GET['order_status']) && $_GET['order_status']== "not paid"){?>
        <input class="btn btn-primary" value="Pay Now" type="submit"/>
        <?php }?>
    </div>
</section>


<?php include('footer.php');?>