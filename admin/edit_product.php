<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include('../server/connection.php');

$product_id = $title = $description = $price = $category = $shelf_life = $form = $image = '';
$products = [];

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $products = $stmt->get_result();
}

else if (isset($_POST['edit_btn'])) {
   
    $product_id = $_POST['product_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $shelf_life = $_POST['shelf_life'];
    $form = $_POST['form'];

    $stmt = $conn->prepare("SELECT product_image1 FROM products WHERE product_id=?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_image = $row['product_image1'];

    // Handle image upload
    $image = $current_image; // Default to current image

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/products/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            header('Location: products.php?edit_failure_message=File is not an image');
            exit();
        }

        // Check file size (limit to 2MB)
        if ($_FILES["image"]["size"] > 2000000) {
            header('Location: products.php?edit_failure_message=Image size too large (max 2MB)');
            exit();
        }

        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            header('Location: products.php?edit_failure_message=Only JPG, JPEG, PNG & GIF files are allowed');
            exit();
        }

        // Generate unique filename to prevent overwriting
        $new_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // Try to upload the file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $new_filename;

            // Delete old image if it exists and is different from new one
            if (!empty($current_image) && $current_image != $new_filename && file_exists($target_dir . $current_image)) {
                unlink($target_dir . $current_image);
            }
        } else {
            header('Location: products.php?edit_failure_message=Error uploading image');
            exit();
        }
    }

    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_description=?, product_price=?, product_category=?, product_shelf_life=?, product_form=?, product_image1=? WHERE product_id=?");
    $stmt->bind_param('sssssssi', $title, $description, $price, $category, $shelf_life, $form, $image, $product_id);

    if ($stmt->execute()) {
        header('Location: products.php?edit_success_message=Product has been updated successfully');
        exit();
    } else {
        header('Location: products.php?edit_failure_message=Error occurred, Try again!!!');
        exit();
    }
} else {
    header('Location: products.php');
    exit();
}

// Include header and sidemenu AFTER all header() calls
include('header.php');
include('sidemenu.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Edit Product</h2>
                    <p>Please edit the input values and submit to update the product record.</p>
                    <form action="edit_product.php" method="post" enctype="multipart/form-data">
                        <?php foreach ($products as $product) { ?>
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">

                            <div class="form-group mb-3">
                                <label>Product Name</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control"><?php echo htmlspecialchars($product['product_description']); ?></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Price (Rs)</label>
                                <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($product['product_price']); ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label>Category</label>
                                <select name="category" class="form-control">
                                    <option value=""><?php echo $product['product_category'] ?></option>
                                    <option value="Ice Cream Flavours">Ice Cream Flavours</option>
                                    <option value="Soft Drink Concentrate">Soft Drink Concentrate</option>
                                    <option value="Fruit Soda Concentrate">Fruit Soda Concentrate</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Product Shelf Life</label>
                                <input type="text" name="shelf_life" class="form-control" value="<?php echo htmlspecialchars($product['product_shelf_life']); ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label>Product Form</label>
                                <input type="text" name="form" class="form-control" value="<?php echo htmlspecialchars($product['product_form']); ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label>Product Image</label>
                                <input type="file" name="image" class="form-control">
                                <div class="mt-2">
                                    <p>Current image:</p>
                                    <?php if (!empty($product['product_image1'])): ?>
                                        <img src="../assets/imgs/<?php echo htmlspecialchars($product['product_image1']); ?>" alt="Product Image" width="70px" height="70px">
                                    <?php else: ?>
                                        <p>No image available</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <input type="submit" class="btn btn-primary" name="edit_btn" value="Update">
                                <a href="products.php" class="btn btn-secondary ms-2">Cancel</a>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>