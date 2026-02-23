<?php
/**
 * Register Product
 * Allows customer to register a product they own
 */

session_start();

// Include helpers
require_once('../includes/session_helper.php');
require_once('../includes/db_functions.php');
require('database.php');

// Require customer login
require_customer_login();

// Get customer ID
$customer_id = get_customer_id();

// Get all products
$products = get_all_products($db);

// Set page details
$page_title = 'Register Product';
$breadcrumbs = [
    ['name' => 'Customers', 'url' => 'customer_login.php'],
    ['name' => 'Register Product', 'url' => '']
];

include('../includes/header.php');
?>

<div class="container mt-5">
    
    <?php include('../includes/breadcrumb.php'); ?>
    
    <!-- Customer Info Alert -->
    <div class="alert alert-info">
        <strong>
            <i class="bi bi-person-circle"></i> Logged in as:
        </strong> 
        <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
        <small class="text-muted">(<?php echo htmlspecialchars($_SESSION['customer_email']); ?>)</small>
        <a href="logout.php" class="btn btn-sm btn-outline-secondary float-end">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">
                <i class="bi bi-clipboard-check"></i> Register a Product
            </h3>
        </div>
        <div class="card-body">
            
            <p class="text-muted mb-4">
                Select a product to register it to your account. 
                This will allow you to create support incidents for this product.
            </p>
            
            <form action="process_registration.php" method="post">
                
                <div class="mb-4">
                    <label for="product_id" class="form-label">
                        <strong>
                            <i class="bi bi-box-seam"></i> Select Product:
                        </strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select form-select-lg" id="product_id" name="product_id" required>
                        <option value="">-- Choose a Product --</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['productID']; ?>">
                                <?php 
                                    echo htmlspecialchars($product['productName']) . ' ' . 
                                         htmlspecialchars($product['version']) . 
                                         ' (' . htmlspecialchars($product['productCode']) . ')'; 
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">
                        Choose the product you want to register
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Register Product
                    </button>
                    <a href="customer_login.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
                
            </form>
            
        </div>
    </div>
    
</div>

<?php include('../includes/footer.php'); ?>