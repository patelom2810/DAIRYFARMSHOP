<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include('db.php');

// Check if updating a product
$editMode = false;
$editProduct = [
    'product_id' => '',
    'name' => '',
    'price' => '',
    'quantity' => ''
];

// Insert Product
if (isset($_POST['insert'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO products (name, price, quantity) VALUES ('$name', '$price', '$quantity')";
    if (!mysqli_query($conn, $query)) {
        die("Insert Query Failed: " . mysqli_error($conn));
    }
    header("Location: product_manage.php"); // Refresh page
}

// Edit Product - Load Data into Form
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = $_GET['edit'];
    $query = "SELECT * FROM products WHERE product_id=$id";
    $result = mysqli_query($conn, $query);
    $editProduct = mysqli_fetch_assoc($result);
}

// Update Product
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE products SET name='$name', price='$price', quantity='$quantity' WHERE product_id=$id";
    if (!mysqli_query($conn, $query)) {
        die("Update Query Failed: " . mysqli_error($conn));
    }
    header("Location: product_manage.php"); // Refresh page
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM products WHERE product_id=$id";
    if (!mysqli_query($conn, $query)) {
        die("Delete Query Failed: " . mysqli_error($conn));
    }
    header("Location: product_manage.php"); // Refresh page
}

// Fetch Products
$products_query = "SELECT * FROM products ORDER BY product_id DESC";
$products_result = mysqli_query($conn, $products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Dairy Farm Management</title>
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3 class="sidebar-brand">Dairy Farm</h3>
        </div>
        
        <ul class="sidebar-nav">
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="product_manage.php" class="active">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            
            <li>
                <a href="billing.php">
                    <i class="fas fa-file-invoice-dollar"></i> Billing
                </a>
            </li>
            
            <li>
                <a href="sales_report.php">
                    <i class="fas fa-chart-line"></i> Sales Report
                </a>
            </li>
         
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
        
        <div class="sidebar-user">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <div class="user-name"><?php echo $_SESSION['username']; ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <div class="page-header">
            <button class="toggle-sidebar" id="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="page-title">Product Management</h2>
            <div>
                <span><?php echo date('F d, Y'); ?></span>
            </div>
        </div>
        
        <!-- Product Form -->
        <div class="container">
            <h3><?php echo $editMode ? 'Edit Product' : 'Add New Product'; ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $editMode ? $editProduct['product_id'] : ''; ?>">
                
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter product name" required value="<?php echo $editMode ? $editProduct['name'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" placeholder="Enter price" required value="<?php echo $editMode ? $editProduct['price'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required value="<?php echo $editMode ? $editProduct['quantity'] : ''; ?>">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <?php if ($editMode) { ?>
                        <button type="submit" name="update" class="btn btn-primary">Update Product</button>
                        <a href="product_manage.php" class="btn btn-secondary">Cancel</a>
                    <?php } else { ?>
                        <button type="submit" name="insert" class="btn btn-primary">Add Product</button>
                    <?php } ?>
                </div>
            </form>
        </div>
        
        <!-- Products Table -->
        <div class="container">
            <h3>Product Inventory</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($products_result) > 0) {
                        while ($row = mysqli_fetch_assoc($products_result)) { 
                    ?>
                    <tr>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <?php if ($row['quantity'] <= 0) { ?>
                                <span class="low-stock">Out of Stock</span>
                            <?php } elseif ($row['quantity'] < 10) { ?>
                                <span class="low-stock">Low Stock</span>
                            <?php } else { ?>
                                <span class="in-stock">In Stock</span>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="product_manage.php?edit=<?php echo $row['product_id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="product_manage.php?delete=<?php echo $row['product_id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No products found</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>