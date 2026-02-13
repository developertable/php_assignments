<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//check if customer is logged in
if(!isset($_SESSION['customer_id'])) {
  header("Location: customer_login.php");
  exit();
}

require('database.php');

// Get customer ID from session
$customer_id = $_SESSION['customer_id'];

//Get form data
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$title = filter_input(INPUT_POST, 'title');
$description = filter_input(INPUT_POST, 'description');

//validate all fields
$errors = [];

if($product_id === false || $product_id === null) {
  $errors[] = 'Invalid product selection.';
}

if (empty($title)) {
    $errors[] = 'Incident title is required.';
}

if (empty($description)) {
    $errors[] = 'Incident description is required.';
}

// If there are validation errors, show them
if (count($errors) > 0) {
    $error_message = implode('<br>', $errors);
    include('error.php');
    exit();
}

// Verify customer owns this product
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

// Insert incident into database
try {
    $query = 'INSERT INTO incidents 
              (customerID, productID, techID, title, description, dateOpened, dateClosed) 
              VALUES 
              (:customer_id, :product_id, NULL, :title, :description, NOW(), NULL)';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->bindValue(':product_id', $product_id);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':description', $description);
    $statement->execute();
    
    // Get the ID of the incident we just created
    $incident_id = $db->lastInsertId();
    
    $statement->closeCursor();
    
    // Success!
    $success = true;
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Incident Created Successfully</title>
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
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-check-circle"></i> Incident Created Successfully
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="alert alert-success">
                            <h5>Your support incident has been submitted!</h5>
                            <p class="mb-0">A technician will be assigned to help resolve your issue.</p>
                        </div>
                        
                        <!-- Incident Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Incident Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Incident Number:</strong></div>
                                    <div class="col-md-8">
                                        <span class="badge bg-primary">#<?php echo $incident_id; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Product:</strong></div>
                                    <div class="col-md-8">
                                        <?php echo htmlspecialchars($product['productName']) . ' ' . 
                                                  htmlspecialchars($product['version']); ?>
                                        (<?php echo htmlspecialchars($product['productCode']); ?>)
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Title:</strong></div>
                                    <div class="col-md-8">
                                        <?php echo htmlspecialchars($title); ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Description:</strong></div>
                                    <div class="col-md-8">
                                        <?php echo nl2br(htmlspecialchars($description)); ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Date Opened:</strong></div>
                                    <div class="col-md-8">
                                        <?php 
                                            $now = new DateTime();
                                            echo $now->format('F j, Y \a\t g:i A'); 
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><strong>Status:</strong></div>
                                    <div class="col-md-8">
                                        <span class="badge bg-warning text-dark">Pending Assignment</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Next Steps -->
                        <div class="alert alert-info">
                            <h6><strong>What happens next?</strong></h6>
                            <ul class="mb-0">
                                <li>Your incident has been logged in our system</li>
                                <li>A support technician will be assigned shortly</li>
                                <li>You will be contacted via email with updates</li>
                                <li>Please reference incident #<?php echo $incident_id; ?> in all correspondence</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="select_product.php" class="btn btn-warning">
                                Create Another Incident
                            </a>
                            <a href="../index.php" class="btn btn-secondary">
                                Return to Home
                            </a>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>
</html>