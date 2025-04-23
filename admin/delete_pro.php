<?php
session_start();

include('../server/connection.php');


// Strict session check
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit();
    }

    if(isset($_GET['product_id'])){

        $product_id=$_GET['product_id'];
        $stmt = $conn->prepare("DELETE FROM products where product_id=?");
        $stmt->bind_param('i', $product_id);


        if($stmt->execute()){

            header('location: products.php?deleted_successfully= Product has been deleted successfully');
        }else
        {
            header('location: products.php?deleted_failure= could not delete product!');
        }

    }

?>