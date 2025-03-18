<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include('db.php');  
$query = "SELECT s.*, p.name as product_name FROM sales s LEFT JOIN products p ON s.product_id = p.product_id ORDER BY s.date DESC"; 
$sales_result = mysqli_query($conn, $query);

// Calculate totals
$totalQuery = "SELECT SUM(total) as grand_total, COUNT(*) as total_transactions FROM sales";
$totalResult = mysqli_query($conn, $totalQuery);
$totals = mysqli_fetch_assoc($totalResult);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Dairy Farm Management</title>
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
                <a href="billing.php">
                    <i class="fas fa-file-invoice-dollar"></i> Billing
                </a>
            </li>
            
            <li>
                <a href="sales_report.php" class="active">
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
            <h2 class="page-title">Sales Report</h2>
            <div>
                <span><?php echo date('F d, Y'); ?></span>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-indian-rupee-sign"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value">₹<?php echo number_format($totals['grand_total'], 2); ?></h3>
                    <p class="stat-label">Total Revenue</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value"><?php echo $totals['total_transactions']; ?></h3>
                    <p class="stat-label">Total Transactions</p>
                </div>
            </div>
        </div>
        
        <!-- Sales Table -->
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Sales Transactions</h3>
                <button class="btn btn-primary" onclick="printReport()">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($sales_result) > 0) {
                        while ($row = mysqli_fetch_assoc($sales_result)) { 
                    ?>
                    <tr>
                        <td><?php echo $row['bill_id']; ?></td>
                        <td><?php echo $row['product_name'] ?? $row['product_id']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>₹<?php echo number_format($row['total'], 2); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['customer_phone']; ?></td>
                        <td><?php echo $row['customer_city']; ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No sales records found</td>
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
        
        // Print report function
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>