<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include('db.php');

if (!isset($_GET['bill_id'])) {
    header("Location: billing.php");
    exit;
}

$bill_id = $_GET['bill_id'];

// Get sale details
$query = "SELECT s.*, p.name as product_name, p.price as unit_price 
          FROM sales s 
          LEFT JOIN products p ON s.product_id = p.product_id 
          WHERE s.bill_id = $bill_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: billing.php");
    exit;
}

$sale = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo $bill_id; ?> - Dairy Farm Management</title>
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
        @media print {
            body {
                background-color: white;
                font-size: 12pt;
            }
            .sidebar, .page-header, .no-print {
                display: none !important;
            }
            .main-content {
                margin-left: 0;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .receipt-logo {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .receipt-title {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin-bottom: 5px;
        }
        
        .receipt-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .receipt-info-group {
            flex: 1;
        }
        
        .receipt-info-label {
            font-weight: 500;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .receipt-info-value {
            font-size: 1rem;
            color: var(--text-dark);
        }
        
        .receipt-items {
            margin-bottom: 20px;
        }
        
        .receipt-total {
            text-align: right;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
        }
        
        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
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
        <div class="page-header no-print">
            <button class="toggle-sidebar" id="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="page-title">Receipt</h2>
            <div>
                <span><?php echo date('F d, Y'); ?></span>
            </div>
        </div>
        
        <div class="receipt-container">
            <div class="receipt-header">
                <div class="receipt-logo">
                    <i class="fas fa-cow"></i>
                </div>
                <h2 class="receipt-title">Dairy Farm Management</h2>
                <p class="receipt-subtitle">Quality Dairy Products</p>
            </div>
            
            <div class="receipt-info">
                <div class="receipt-info-group">
                    <p class="receipt-info-label">Receipt #</p>
                    <p class="receipt-info-value"><?php echo $bill_id; ?></p>
                </div>
                
                <div class="receipt-info-group">
                    <p class="receipt-info-label">Date</p>
                    <p class="receipt-info-value"><?php echo date('M d, Y', strtotime($sale['date'])); ?></p>
                </div>
                
                <div class="receipt-info-group">
                    <p class="receipt-info-label">Customer</p>
                    <p class="receipt-info-value"><?php echo $sale['customer_name']; ?></p>
                </div>
            </div>
            
            <div class="receipt-items">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $sale['product_name']; ?></td>
                            <td>$<?php echo number_format($sale['unit_price'], 2); ?></td>
                            <td><?php echo $sale['quantity']; ?></td>
                            <td>$<?php echo number_format($sale['total'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="receipt-total">
                    <p>Total: $<?php echo number_format($sale['total'], 2); ?></p>
                </div>
            </div>
            
            <div class="receipt-footer">
                <p>Thank you for your purchase!</p>
                <p>Contact: info@dairyfarm.com | Phone: (123) 456-7890</p>
            </div>
            
            <div class="action-buttons no-print">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
                <a href="billing.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Billing
                </a>
            </div>
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