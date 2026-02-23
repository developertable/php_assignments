<?php
/**
 * Select Product for Incident
 * Shows customer's registered products to select for creating incident
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

// Get products registered by this customer
$products = get_customer_products($db, $customer_id);

// Set page details
$page_title = 'Create Support Incident - Select Product';
$breadcrumbs = [
    ['name' => 'Customers', 'url' => 'customer_login.php'],
    ['name' => 'Create Incident', 'url' => ''],
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
    </div>
    
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">
                <i class="bi bi-exclamation-triangle"></i> Create Support Incident
            </h3>
            <small>Step 1: Select a Product</small>
        </div>
        <div class="card-body">
            
            <p class="text-muted mb-4">
                Select a product you've registered to create a support incident.
            </p>
            
            <?php if (count($products) > 0): ?>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Version</th>
                                <th>Registration Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($product['productCode']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['productName']); ?></td>
                                    <td><?php echo htmlspecialchars($product['version']); ?></td>
                                    <td>
                                        <?php 
                                            $date = new DateTime($product['registrationDate']);
                                            echo $date->format('M d, Y'); 
                                        ?>
                                    </td>
                                    <td>
                                        <a href="create_incident_form.php?product_id=<?php echo $product['productID']; ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-plus-circle"></i> Create Incident
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php else: ?>
                
                <div class="alert alert-warning">
                    <h5>
                        <i class="bi bi-exclamation-circle"></i> No Registered Products
                    </h5>
                    <p>You haven't registered any products yet. Please register a product before creating an incident.</p>
                    <a href="../project_6_4_register_product/customer_login.php" class="btn btn-primary">
                        <i class="bi bi-clipboard-check"></i> Register a Product
                    </a>
                </div>
                
            <?php endif; ?>
            
        </div>
        <div class="card-footer">
            <a href="customer_login.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
            <a href="../index.php" class="btn btn-outline-secondary">
                <i class="bi bi-house"></i> Home
            </a>
        </div>
    </div>
    
</div>

<?php include('../includes/footer.php'); ?>