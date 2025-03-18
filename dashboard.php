<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
include('db.php');

// Get total sales from sales table (sum of total field)
$salesQuery = "SELECT SUM(total) AS total FROM sales";
$salesResult = mysqli_query($conn, $salesQuery);
$salesRow = mysqli_fetch_assoc($salesResult);
$totalSales = $salesRow['total'] ?: 0;

// Get total products count from products table
$productsQuery = "SELECT COUNT(*) AS totalProducts FROM products";
$productsResult = mysqli_query($conn, $productsQuery);
$productsRow = mysqli_fetch_assoc($productsResult);
$totalProducts = $productsRow['totalProducts'] ?: 0;

// Get total customers (unique count from sales table)
$customersQuery = "SELECT COUNT(DISTINCT bill_id) AS totalCustomers FROM sales";
$customersResult = mysqli_query($conn, $customersQuery);
$customersRow = mysqli_fetch_assoc($customersResult);
$totalCustomers = $customersRow['totalCustomers'] ?: 0;

// Get recent sales
$recentSalesQuery = "SELECT * FROM sales ORDER BY date DESC LIMIT 5";
$recentSalesResult = mysqli_query($conn, $recentSalesQuery);

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dairy Farm Management</title>
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
                <a href="dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="product_manage.php">
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
            <h2 class="page-title">Dashboard Overview</h2>
            <div>
                <span><?php echo date('F d, Y'); ?></span>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-container">
            <!-- Sales Card -->
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-indian-rupee-sign"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value">₹<?php echo number_format($totalSales, 2); ?></h3>
                    <p class="stat-label">Total Sales</p>
                </div>
            </div>
            
            <!-- Products Card -->
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value"><?php echo $totalProducts; ?></h3>
                    <p class="stat-label">Total Products</p>
                </div>
            </div>
            
            <!-- Customers Card -->
            <div class="stat-card">
                <div class="stat-icon customers">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value"><?php echo $totalCustomers; ?></h3>
                    <p class="stat-label">Total Customers</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="container">
            <h3 class="page-title">Recent Sales</h3>
            <table>
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($recentSalesResult) > 0) {
                        while ($row = mysqli_fetch_assoc($recentSalesResult)) { 
                    ?>
                    <tr>
                        <td><?php echo $row['bill_id']; ?></td>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td>$<?php echo number_format($row['total'], 2); ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No recent sales</td>
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