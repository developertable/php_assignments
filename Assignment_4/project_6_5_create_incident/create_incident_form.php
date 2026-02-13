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

// Get product ID from URL
$product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

// Validate product ID
if ($product_id === false || $product_id === null) {
    $error_message = 'Invalid product selection.';
    include('error.php');
    exit();
}

// Verify this customer has registered this product
try {
    $query = 'SELECT p.productID, p.productCode, p.productName, p.version
              FROM products p
              INNER JOIN registrations r ON p.productID = r.productID
              WHERE r.customerID = :customer_id AND p.productID = :product_id';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $product = $statement->fetch();
    $statement->closeCursor();
    
    // If no product found, customer doesn't own it or it doesn't exist
    if (!$product) {
        $error_message = 'You do not have access to create incidents for this product.';
        include('error.php');
        exit();
    }
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Incident - <?php echo htmlspecialchars($product['productName']); ?></title>
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
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">Create Support Incident</h3>
                        <small>Step 2: Describe Your Problem</small>
                    </div>
                    <div class="card-body">
                        
                        <!-- Product Information Box -->
                        <div class="alert alert-secondary">
                            <h5 class="mb-2">Product Information</h5>
                            <strong>Product:</strong> 
                            <?php echo htmlspecialchars($product['productName']) . ' ' . 
                                      htmlspecialchars($product['version']); ?>
                            <br>
                            <strong>Code:</strong> 
                            <?php echo htmlspecialchars($product['productCode']); ?>
                        </div>
                        
                        <p class="text-muted mb-4">
                            Please provide details about the issue you're experiencing. 
                            A support technician will be assigned to help resolve your problem.
                        </p>
                        
                        <form action="process_incident.php" method="post">
                            
                            <!-- Hidden field to pass product ID -->
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            
                            <!-- Title Field -->
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <strong>Incident Title:</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title"
                                       placeholder="Brief description of the problem"
                                       maxlength="100"
                                       required>
                                <div class="form-text">
                                    Example: "Unable to login", "Error when saving data", "Feature not working"
                                </div>
                            </div>
                            
                            <!-- Description Field -->
                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    <strong>Detailed Description:</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description"
                                          rows="6"
                                          placeholder="Please provide detailed information about the issue, including:&#10;- What were you trying to do?&#10;- What happened instead?&#10;- Steps to reproduce the problem&#10;- Any error messages you saw"
                                          required></textarea>
                                <div class="form-text">
                                    The more details you provide, the faster we can help resolve your issue.
                                </div>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="bi bi-exclamation-triangle"></i> Submit Incident
                                </button>
                                <a href="select_product.php" class="btn btn-secondary">
                                    ← Back to Product Selection
                                </a>
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>
</html>