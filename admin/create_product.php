<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Include database connection
include('../server/connection.php');

// Check if form was submitted
if (isset($_POST['create_product'])) {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $shelf_life = $_POST['shelf_life'];
    $form = $_POST['form'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];

   

    if ($image_error === 0) {
        // Create unique image name
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

        // Check if extension is allowed
        if (in_array(strtolower($image_ext), $allowed_ext)) {
            // Create unique filename
            $new_image_name = uniqid('product_') . '.' . $image_ext;

            // Make sure the directory exists - Create it if it doesn't
            $upload_dir = '';
            $image_upload_path = $new_image_name;
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $image_upload_path = $upload_dir . $new_image_name;
            $image_db_path = $upload_dir . $new_image_name; // Store the full path


            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                // Store only the image filename in the database
                $image_db_path = $new_image_name;

                $stmt = $conn->prepare("INSERT INTO products (product_name, product_description, product_price, product_category, product_shelf_life, product_form, product_image1) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdssss", $name, $description, $price, $category, $shelf_life, $form, $image_db_path);

                if ($stmt->execute()) {
                    header('Location: products.php?message=Product added successfully');
                    exit();
                } else {
                    header('Location: add_product.php?error_message=Failed to add product: ' . $stmt->error);
                    exit();
                }
            } else {
                // If move_uploaded_file fails, provide more details
                $error = error_get_last();
                header('Location: add_product.php?error_message=Failed to upload image. ' .
                    (isset($error['message']) ? $error['message'] : 'Check directory permissions.'));
                exit();
            }
        } else {
            header('Location: add_product.php?error_message=Invalid image format. Please use jpg, jpeg, png, or gif');
            exit();
        }
    } else {
        // Provide more specific upload error messages
        $upload_errors = [
            1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
            2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            3 => "The uploaded file was only partially uploaded",
            4 => "No file was uploaded",
            6 => "Missing a temporary folder",
            7 => "Failed to write file to disk",
            8 => "A PHP extension stopped the file upload"
        ];

        $error_message = isset($upload_errors[$image_error]) ? $upload_errors[$image_error] : "Unknown upload error";
        header('Location: add_product.php?error_message=Error uploading image: ' . $error_message);
        exit();
    }
} else {
    // If form not submitted, redirect to add product page
    header('Location: add_product.php');
    exit();
}
