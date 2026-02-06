<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_id'])) {
    // Not logged in - redirect to login page
    header("Location: customer_login.php");
    exit();
}

require('database.php');

// Get all products for dropdown
try {
    $query = 'SELECT productID, productCode, productName, version 
              FROM products 
              ORDER BY productName, version';
    $statement = $db->prepare($query);
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
    <title>Register Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Customer Info Alert -->
                <div class="alert alert-info">
                    <strong>Logged in as:</strong> 
                    <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                    (<?php echo htmlspecialchars($_SESSION['customer_email']); ?>)
                    <a href="logout.php" class="btn btn-sm btn-outline-secondary float-end">Logout</a>
                </div>
                
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Register a Product</h3>
                    </div>
                    <div class="card-body">
                        
                        <p class="text-muted">
                            Select a product to register it to your account. 
                            This will allow you to create support incidents for this product.
                        </p>
                        <form action="process_registration.php" method="post">
                            
                            <div class="mb-4">
                                <label for="product_id" class="form-label">
                                    <strong>Select Product:</strong>
                                </label>
                                <select class="form-select" id="product_id" name="product_id" required>
                                    <option value="">-- Choose a Product --</option>
                                    <?php foreach ($products as $product) : ?>
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
                                    Register Product
                                </button>
                                <a href="customer_login.php" class="btn btn-secondary">
                                    Back to Login
                                </a>
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>