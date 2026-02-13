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

// Get product ID from form
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

// Validate product ID
if ($product_id === false || $product_id === null) {
    $error_message = 'Invalid product selection.';
    include('error.php');
    exit();
}

// Get product details for display
try {
    $query = 'SELECT productCode, productName, version 
              FROM products 
              WHERE productID = :product_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $product = $statement->fetch();
    $statement->closeCursor();
    
    // Check if product exists
    if (!$product) {
        $error_message = 'Product not found.';
        include('error.php');
        exit();
    }
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}

// Try to insert registration
try {
    $query = 'INSERT INTO registrations (customerID, productID, registrationDate) 
              VALUES (:customer_id, :product_id, NOW())';
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $statement->closeCursor();
    
    // Registration successful
    $success = true;
    $message = 'Product ' . htmlspecialchars($product['productCode']) . 
               ' registered successfully!';
    
} catch (PDOException $e) {
    // Check if it's a duplicate entry error
    if ($e->getCode() == 23000) {
        // Duplicate registration
        $success = false;
        $message = 'You have already registered product ' . 
                   htmlspecialchars($product['productCode']) . '.';
    } else {
        // Other database error
        $error_message = 'Database error: ' . $e->getMessage();
        include('error.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Result</title>
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
                </div>
                
                <div class="card">
                    <div class="card-header <?php echo $success ? 'bg-success' : 'bg-warning'; ?> text-white">
                        <h3 class="mb-0">
                            <?php echo $success ? 'Registration Successful' : 'Registration Failed'; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="alert <?php echo $success ? 'alert-success' : 'alert-warning'; ?>">
                            <h5><?php echo $message; ?></h5>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Product Details:</strong><br>
                            Name: <?php echo htmlspecialchars($product['productName']); ?><br>
                            Version: <?php echo htmlspecialchars($product['version']); ?><br>
                            Code: <?php echo htmlspecialchars($product['productCode']); ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="register_product.php" class="btn btn-primary">
                                Register Another Product
                            </a>
                            <a href="customer_login.php" class="btn btn-secondary">
                                Back to Login
                            </a>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>