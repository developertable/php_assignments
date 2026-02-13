<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

require('database.php');

// Get customer ID from session
$customer_id = $_SESSION['customer_id'];

// Get all products registered by this customer
try {
    $query = 'SELECT p.productID, p.productCode, p.productName, p.version, r.registrationDate
              FROM products p
              INNER JOIN registrations r ON p.productID = r.productID
              WHERE r.customerID = :customer_id
              ORDER BY p.productName, p.version';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->execute();
    $products = $statement->fetchAll();
    $statement->closeCursor();
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Product - Create Incident</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <!-- Customer Info Alert -->
                <div class="alert alert-info">
                    <strong>Logged in as:</strong> 
                    <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                    (<?php echo htmlspecialchars($_SESSION['customer_email']); ?>)
                </div>
                
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">Create Support Incident</h3>
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
                                                <td><?php echo htmlspecialchars($product['productCode']); ?></td>
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
                                                        Create Incident
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        <?php else: ?>
                            
                            <div class="alert alert-warning">
                                <h5>No Registered Products</h5>
                                <p>You haven't registered any products yet. Please register a product before creating an incident.</p>
                                <a href="../project_6_4_register_product/customer_login.php" class="btn btn-primary">
                                    Register a Product
                                </a>
                            </div>
                            
                        <?php endif; ?>
                        
                    </div>
                    <div class="card-footer">
                        <a href="customer_login.php" class="btn btn-secondary">← Back to Login</a>
                        <a href="../index.php" class="btn btn-outline-secondary">Home</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>