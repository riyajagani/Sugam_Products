<?php
session_start();
ob_start(); // Start output buffering

// Admin login check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include('../server/connection.php'); // Database connection
include('header.php');
include('sidemenu.php');

// Handle product creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $shelf_life = $_POST['shelf_life'];
    $form = $_POST['form'];
    $image_path = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            $uploads_dir = '../uploads/'; // Upload folder (one level up)
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }

            $unique_name = time() . '_' . basename($_FILES['image']['name']);
            $target_file = $uploads_dir . $unique_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = '../uploads/' . $unique_name; // Save relative path
            } else {
                header('Location: add_product.php?error_message=Failed to upload image.');
                exit();
            }
        } else {
            header('Location: add_product.php?error_message=Invalid image format.');
            exit();
        }
    } else {
        header('Location: add_product.php?error_message=Image not uploaded.');
        exit();
    }

    // Insert product into database
    try {
        $stmt = $conn->prepare("INSERT INTO products (product_name, product_description, product_price, product_category, product_shelf_life, product_form, product_image1)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $name, $description, $price, $category, $shelf_life, $form, $image_path);

        if ($stmt->execute()) {
            header('Location: products.php?message=Product added successfully');
            exit();
        } else {
            header('Location: add_product.php?error_message=Failed to add product: ' . $stmt->error);
            exit();
        }
    } catch (PDOException $e) {
        header('Location: add_product.php?error_message=Database error: ' . $e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Product - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }
        .container-fluid { padding: 0; }
        .sidebar { background-color: #fff; border-right: 1px solid #dee2e6; min-height: 100vh; padding: 25px 15px; }
        .sidebar .nav-link { color: #444; font-weight: 500; padding: 10px 15px; margin-bottom: 10px; border-radius: 8px; transition: background 0.2s ease, color 0.2s ease; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #e9ecef; color: #000; }
        main { padding: 40px; background-color: #f4f7fa; }
        h1 { font-size: 1.75rem; font-weight: 600; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); background-color: #fff; }
        .form-label { font-weight: 500; margin-bottom: 5px; }
        .form-control, .form-select { border-radius: 8px; }
        .input-group-text { border-radius: 8px 0 0 8px; }
        .btn-primary { background-color: #4CAF50; border: none; padding: 10px 25px; font-weight: 500; }
        .btn-primary:hover { background-color: #43a047; }
        .btn-secondary { background-color: #6c757d; border: none; padding: 10px 25px; }
        .btn-secondary:hover { background-color: #5a6268; }
        @media (max-width: 768px) {
            main { padding: 20px; }
            .sidebar { padding: 20px 10px; }
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row g-0">

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10">
            <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <h1 class="h2">Add New Product</h1>
            </div>

            <!-- Messages -->
            <?php if (isset($_GET['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card p-4">
                <form id="create-form" enctype="multipart/form-data" method="POST">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="name" id="product_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="product_description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Rs.</span>
                            <input type="number" class="form-control" name="price" id="price" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" name="category" id="category" required>
                            <option value="">Select a category</option>
                            <option value="Soft Drink Flavour">Soft Drink Flavour</option>
                            <option value="Ice Cream Flavour">Ice Cream Flavour</option>
                            <option value="Fruit Soda Flavour">Fruit Soda Flavour</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="shelf_life" class="form-label">Product Shelf Life</label>
                        <input type="text" class="form-control" name="shelf_life" id="shelf_life" required>
                    </div>

                    <div class="mb-3">
                        <label for="form" class="form-label">Product Form</label>
                        <input type="text" class="form-control" name="form" id="form" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" name="image" id="product_image" accept="image/*" required>
                        <small class="text-muted">Please upload an image file (JPG, PNG, etc.). Max size: 2MB</small>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" name="create_product">Add Product</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
ob_end_flush(); // Flush the output buffer
?>