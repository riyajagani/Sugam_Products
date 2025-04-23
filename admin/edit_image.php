<?php
session_start();

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
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';

// Fetch current product details
$stmt = $conn->prepare("SELECT product_image1 FROM products WHERE product_id = ?");
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
    if (isset($_POST['remove_image'])) {
        // Handle image removal
        $default_image = 'images/default_product.jpg'; // Set your default image path

        $update_stmt = $conn->prepare("UPDATE products SET product_image1 = ? WHERE product_id = ?");
        $update_stmt->bind_param('si', $default_image, $product_id);

        if ($update_stmt->execute()) {
            // Delete the old image file if it's not the default
            if ($current_image != $default_image && file_exists($current_image)) {
                unlink($current_image);
            }
            header('Location: products.php?edit_success_message=Product image removed successfully');
            exit();
        } else {
            header("Location: edit_image.php?product_id=$product_id&error=Failed to remove image");
            exit();
        }
    } else {
        // Handle image upload
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/products/';

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
                header("Location: edit_image.php?product_id=$product_id&error=Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.");
                exit();
            }

            // Check file size (2MB max)
            if ($file_size > 2097152) {
                header("Location: edit_image.php?product_id=$product_id&error=File size too large. Max 2MB allowed.");
                exit();
            }

            // Generate unique filename
            $new_file_name = 'product_' . $product_id . '_' . time() . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;

            // Move uploaded file
            if (move_uploaded_file($file_tmp, $destination)) {
                // Update database with new image path
                $update_stmt = $conn->prepare("UPDATE products SET product_image1 = ? WHERE product_id = ?");
                $update_stmt->bind_param('si', $destination, $product_id);

                if ($update_stmt->execute()) {
                    // Delete the old image file if it exists and is not the default
                    $default_image = 'images/default_product.jpg';
                    if ($current_image != $default_image && file_exists($current_image)) {
                        unlink($current_image);
                    }
                    header('Location: products.php?edit_success_message=Product image updated successfully');
                    exit();
                } else {
                    // Delete the uploaded file if database update failed
                    unlink($destination);
                    header("Location: edit_image.php?product_id=$product_id&error=Failed to update database");
                    exit();
                }
            } else {
                header("Location: edit_image.php?product_id=$product_id&error=Failed to upload image");
                exit();
            }
        } else {
            header("Location: edit_image.php?product_id=$product_id&error=No image selected or upload error");
            exit();
        }
    }
}

// Include header and sidemenu
include('header.php');
include('sidemenu.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Product Image - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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

        .card-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
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

        .btn-danger:hover,
        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-primary {
            background-color: #1a73e8;
            border: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .d-grid.gap-2 {
            gap: 10px;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="content-wrapper">
            <div class="page-title">Edit Product Image</div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger error-message">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">Current Product: <?php echo htmlspecialchars($product_name); ?></div>
                <div class="mb-4">
                    <h6>Current Image</h6>
                    <?php if (!empty($current_image) && file_exists($current_image)): ?>
                        <img src="<?php echo htmlspecialchars($current_image); ?>" alt="Product Image" class="img-preview w-100" />
                    <?php else: ?>
                        <div class="alert alert-warning">No image currently set for this product</div>
                    <?php endif; ?>
                </div>

                <form action="edit_image.php?product_id=<?php echo $product_id; ?>&product_name=<?php echo urlencode($product_name); ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" />

                    <div class="mb-3">
                        <label for="product_image" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*" required />
                        <div class="form-text">Recommended size: 800x800 pixels. Max file size: 2MB. Allowed formats: JPG, PNG, GIF, WEBP.</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="remove_image" value="1" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove the image?')">
                            <i class="fas fa-trash-alt me-1"></i> Remove Image
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>