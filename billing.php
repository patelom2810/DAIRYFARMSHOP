<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include('db.php');

$message = '';
$messageType = '';

// Fetch all products
$products_query = "SELECT * FROM products WHERE quantity > 0 ORDER BY name ASC";
$products_result = mysqli_query($conn, $products_query);

// Process the sale
if (isset($_POST['process_sale'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $customer_city = $_POST['customer_city'];
    
    // Check if product exists and has enough quantity
    $check_query = "SELECT * FROM products WHERE product_id = $product_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $product = mysqli_fetch_assoc($check_result);
        
        if ($product['quantity'] >= $quantity) {
            // Calculate total
            $total = $product['price'] * $quantity;
            
            // Begin transaction
            mysqli_begin_transaction($conn);
            
            try {
                // Insert into sales table
                $sale_query = "INSERT INTO sales (product_id, quantity, total, date, customer_name, customer_phone, customer_city) 
                              VALUES ($product_id, $quantity, $total, NOW(), '$customer_name', '$customer_phone', '$customer_city')";
                
                if (!mysqli_query($conn, $sale_query)) {
                    throw new Exception("Error creating sale: " . mysqli_error($conn));
                }
                
                // Get the bill ID
                $bill_id = mysqli_insert_id($conn);
                
                // Update product quantity
                $update_query = "UPDATE products SET quantity = quantity - $quantity WHERE product_id = $product_id";
                
                if (!mysqli_query($conn, $update_query)) {
                    throw new Exception("Error updating product quantity: " . mysqli_error($conn));
                }
                
                // Commit transaction
                mysqli_commit($conn);
                
                $message = "Sale processed successfully! Bill #$bill_id";
                $messageType = "success";
                
                // Redirect to print receipt
                header("Location: receipt.php?bill_id=$bill_id");
                exit;
                
            } catch (Exception $e) {
                // Rollback transaction on error
                mysqli_rollback($conn);
                $message = $e->getMessage();
                $messageType = "danger";
            }
        } else {
            $message = "Not enough stock available. Only " . $product['quantity'] . " units available.";
            $messageType = "danger";
        }
    } else {
        $message = "Product not found.";
        $messageType = "danger";
    }
}

// Get product details via AJAX
if (isset($_GET['get_product']) && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product_query = "SELECT * FROM products WHERE product_id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    
    if (mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing - Dairy Farm Management</title>
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
                <a href="product_manage.php">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            
            <li>
                <a href="billing.php" class="active">
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
            <h2 class="page-title">Billing</h2>
            <div>
                <span><?php echo date('F d, Y'); ?></span>
            </div>
        </div>
        
        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="two-column">
            <!-- Product Selection -->
            <div class="container">
                <h3>Create New Bill</h3>
                <form method="POST" id="billing-form">
                    <div class="form-group">
                        <label for="product_id">Select Product</label>
                        <select id="product_id" name="product_id" required onchange="getProductDetails()">
                            <option value="">-- Select Product --</option>
                            <?php while ($row = mysqli_fetch_assoc($products_result)) { ?>
                                <option value="<?php echo $row['product_id']; ?>" data-price="<?php echo $row['price']; ?>" data-stock="<?php echo $row['quantity']; ?>">
                                    <?php echo $row['name']; ?> - ₹<?php echo number_format($row['price'], 2); ?> (<?php echo $row['quantity']; ?> in stock)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" required onchange="updateTotal()">
                        <small id="stock-message"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="unit_price">Unit Price</label>
                        <input type="text" id="unit_price" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="total_price">Total Price</label>
                        <input type="text" id="total_price" readonly>
                    </div>
                    
                    <h3>Customer Information</h3>
                    
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_phone">Phone Number</label>
                        <input type="text" id="customer_phone" name="customer_phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_city">City</label>
                        <input type="text" id="customer_city" name="customer_city" required>
                    </div>
                    
                    <button type="submit" name="process_sale" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-check-circle"></i> Process Sale
                    </button>
                </form>
            </div>
            
            <!-- Product List -->
            <div class="container">
                <h3>Available Products</h3>
                <div class="product-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reset the result pointer
                            mysqli_data_seek($products_result, 0);
                            
                            while ($row = mysqli_fetch_assoc($products_result)) { 
                            ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <?php if ($row['quantity'] < 10) { ?>
                                        <span class="low-stock"><?php echo $row['quantity']; ?></span>
                                    <?php } else { ?>
                                        <span class="in-stock"><?php echo $row['quantity']; ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;" onclick="selectProduct(<?php echo $row['product_id']; ?>)">
                                        Select
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Get product details
        function getProductDetails() {
            const productSelect = document.getElementById('product_id');
            const unitPriceInput = document.getElementById('unit_price');
            const stockMessage = document.getElementById('stock-message');
            const submitBtn = document.getElementById('submit-btn');
            
            if (productSelect.value) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const stock = selectedOption.getAttribute('data-stock');
                
                unitPriceInput.value = '$' + parseFloat(price).toFixed(2);
                
                if (parseInt(stock) < 10) {
                    stockMessage.innerHTML = '<span class="low-stock">Only ' + stock + ' units available</span>';
                } else {
                    stockMessage.innerHTML = '<span class="in-stock">' + stock + ' units available</span>';
                }
                
                // Enable/disable submit button based on stock
                if (parseInt(stock) <= 0) {
                    submitBtn.disabled = true;
                    stockMessage.innerHTML = '<span class="low-stock">Out of stock</span>';
                } else {
                    submitBtn.disabled = false;
                }
                
                updateTotal();
            } else {
                unitPriceInput.value = '';
                stockMessage.innerHTML = '';
            }
        }
        
        // Update total price
        function updateTotal() {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const totalPriceInput = document.getElementById('total_price');
            const stockMessage = document.getElementById('stock-message');
            const submitBtn = document.getElementById('submit-btn');
            
            if (productSelect.value && quantityInput.value) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);
                
                // Check if quantity exceeds stock
                if (quantity > stock) {
                    stockMessage.innerHTML = '<span class="low-stock">Quantity exceeds available stock!</span>';
                    submitBtn.disabled = true;
                } else {
                    if (stock < 10) {
                        stockMessage.innerHTML = '<span class="low-stock">Only ' + stock + ' units available</span>';
                    } else {
                        stockMessage.innerHTML = '<span class="in-stock">' + stock + ' units available</span>';
                    }
                    submitBtn.disabled = false;
                }
                
                const total = price * quantity;
                totalPriceInput.value = '$' + total.toFixed(2);
            } else {
                totalPriceInput.value = '';
            }
        }
        
        // Select product from the list
        function selectProduct(productId) {
            document.getElementById('product_id').value = productId;
            getProductDetails();
            // Scroll to the form
            document.getElementById('billing-form').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Form validation
        document.getElementById('billing-form').addEventListener('submit', function(e) {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            
            if (productSelect.value && quantityInput.value) {
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);
                
                if (quantity > stock) {
                    e.preventDefault();
                    alert('Quantity exceeds available stock!');
                }
            }
        });
    </script>
</body>
</html>