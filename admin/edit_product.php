<?php
ob_start(); // Start output buffering
session_start();
include('sidemenu.php');
include('header.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Include database connection
include('../server/connection.php');

// Check if product_id is provided
if (!isset($_GET['product_id'])) {
    header('Location: products.php?error=No product selected');
    exit();
}

$product_id = $_GET['product_id'];

// Fetch current product details
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('Location: products.php?error=Product not found');
    exit();
}

$current_image = $product['product_image1'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update product details
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $shelf_life = $_POST['shelf_life'];
    $form = $_POST['form'];

    // Update product details in the database
    $update_stmt = $conn->prepare("UPDATE products SET product_name = ?, product_description = ?, product_price = ?, product_category = ?, product_shelf_life = ?, product_form = ? WHERE product_id = ?");
    $update_stmt->bind_param('ssdsssi', $name, $description, $price, $category, $shelf_life, $form, $product_id);

    if ($update_stmt->execute()) {
        // Check if an image is being uploaded
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/products/';

            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Get file info
            $file_name = $_FILES['product_image']['name'];
            $file_tmp = $_FILES['product_image']['tmp_name'];
            $file_size = $_FILES['product_image']['size'];
            $file_error = $_FILES['product_image']['error'];

            // Check file type
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($file_ext, $allowed_ext)) {
                header("Location: edit_product.php?product_id=$product_id&error=Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.");
                exit();
            }

            // Check file size (2MB max)
            if ($file_size > 2097152) {
                header("Location: edit_product.php?product_id=$product_id&error=File size too large. Max 2MB allowed.");
                exit();
            }

            // Generate unique filename
            $new_file_name = 'product_' . $product_id . '_' . time() . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;

            // Move uploaded file
            if (move_uploaded_file($file_tmp, $destination)) {
                // Update database with new image path
                $update_image_stmt = $conn->prepare("UPDATE products SET product_image1 = ? WHERE product_id = ?");
                $update_image_stmt->bind_param('si', $destination, $product_id);

                if ($update_image_stmt->execute()) {
                    // Delete the old image file if it exists
                    if ($current_image && file_exists($current_image)) {
                        unlink($current_image);
                    }
                    header('Location: products.php?edit_success_message=Product updated successfully');
                    exit();
                } else {
                    // Delete the uploaded file if database update failed
                    unlink($destination);
                    header("Location: edit_product.php?product_id=$product_id&error=Failed to update image in database");
                    exit();
                }
            } else {
                header("Location: edit_product.php?product_id=$product_id&error=Failed to upload image");
                exit();
            }
        } else {
            header('Location: products.php?edit_success_message=Product updated successfully without image change');
            exit();
        }
    } else {
        header("Location: edit_product.php?product_id=$product_id&error=Failed to update product details");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Product - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .card {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            margin-top: 20px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .img-preview {
            max-height: 300px;
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
            object-fit: contain;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .btn {
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="content-wrapper">
            <h2>Edit Product</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger error-message">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <h5>Current Image</h5>
                <?php if (!empty($current_image) && file_exists($current_image)): ?>
                    <img src="<?php echo htmlspecialchars($current_image); ?>" alt="Product Image" class="img-preview" />
                <?php else: ?>
                    <div class="alert alert-warning">No image currently set for this product</div>
                <?php endif; ?>

                <form action="edit_product.php?product_id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($product['product_description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" id="price" value="<?php echo htmlspecialchars($product['product_price']); ?>" min="0" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" name="category" id="category" required>
                            <option value="<?php echo htmlspecialchars($product['product_category']); ?>"><?php echo htmlspecialchars($product['product_category']); ?></option>
                            <option value="Soft Drink Flavour">Soft Drink Flavour</option>
                            <option value="Ice Cream Flavour">Ice Cream Flavour</option>
                            <option value="Fruit Soda Flavour">Fruit Soda Flavour</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="shelf_life" class="form-label">Product Shelf Life</label>
                        <input type="text" class="form-control" name="shelf_life" id="shelf_life" value="<?php echo htmlspecialchars($product['product_shelf_life']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="form" class="form-label">Product Form</label>
                        <input type="text" class="form-control" name="form" id="form" value="<?php echo htmlspecialchars($product['product_form']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_image" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*" />
                        <div class="form-text">Recommended size: 800x800 pixels. Max file size: 2MB. Allowed formats: JPG, PNG, GIF, WEBP.</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" name="remove_image" value="1" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove the image?')">
                            Remove Image
                        </button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>