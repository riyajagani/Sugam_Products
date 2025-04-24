<?php
session_start();

// Strict session check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Include necessary files
include('../server/connection.php');
include('header.php');
include('sidemenu.php');

// Connect to the database - using the existing connection from connection.php instead of creating a new one
if (!isset($conn)) {
    $conn = mysqli_connect("localhost", "root", "", "php_project");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

// Search functionality
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $query = "SELECT * FROM products WHERE 
              product_name LIKE '%$search_query%' OR 
              product_category LIKE '%$search_query%' OR 
              product_description LIKE '%$search_query%'";
} else {
    $query = "SELECT * FROM products";
}

// Execute query
$result = mysqli_query($conn, $query);

// Check for query success
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --body-bg: #f4f6f9;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
            --text-primary: #212529;
            --text-secondary: #6c757d;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .card-text {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .table img {
            object-fit: cover;
            border-radius: 6px;
        }

        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Products Management</h1>
            <a href="add_product.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Product
            </a>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['edit_success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['edit_success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['edit_failure_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['edit_failure_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted_failure'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['deleted_failure']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted_successfully'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['deleted_successfully']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="products.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search products..."
                    value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
                <?php if (!empty($search_query)): ?>
                    <a href="products.php" class="btn btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Shelf Life</th>
                                    <th scope="col">Form</th>
                                    <th scope="col">Actions</th>
                                    <th scope="col">Edit Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td>
                                            <img src="<?php echo $product['product_image1']; ?>" alt="Product Image"
                                                width="70" height="70" class="border">
                                        </td>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td>Rs. <?php echo number_format($product['product_price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($product['product_category']); ?></td>
                                        <td>
                                            <?php
                                            // Truncate description if too long
                                            $desc = $product['product_description'];
                                            echo (strlen($desc) > 50) ? htmlspecialchars(substr($desc, 0, 50) . '...') : htmlspecialchars($desc);
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['product_shelf_life']); ?></td>
                                        <td><?php echo htmlspecialchars($product['product_form']); ?></td>
                                        <td class="action-buttons">
                                            <a href="edit_image.php?product_id=<?php echo $product['product_id']; ?>&product_name=<?php echo $product['product_name']; ?>"
                                                class="btn btn-sm btn-warning mb-1">
                                                <i class="bi bi-pencil-square"></i> Edit Image
                                            </a>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>"
                                                class="btn btn-sm btn-primary mb-1">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </td>
                                        <td>
                                            <a href="delete_pro.php?product_id=<?php echo $product['product_id']; ?>"
                                                class="btn btn-sm btn-danger mb-1"
                                                onclick="return confirm('Are you sure you want to delete this product?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No products found. <?php echo !empty($search_query) ? 'Try a different search term.' : 'Add your first product!'; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>