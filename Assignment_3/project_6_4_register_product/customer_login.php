<?php
session_start();
require('database.php');

// Initialize variables
$email = '';
$error_message = '';
$customer = null;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get email from form
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // Validate email was entered and is valid
    if ($email === false || $email === null) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Email is valid - check if customer exists in database
        try {
            $query = 'SELECT * FROM customers WHERE email = :email';
            $statement = $db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $customer = $statement->fetch();
            $statement->closeCursor();
            
            // Check if customer was found
            if ($customer) {
                // Customer exists - log them in!
                $_SESSION['customer_id'] = $customer['customerID'];
                $_SESSION['customer_email'] = $customer['email'];
                $_SESSION['customer_name'] = $customer['firstName'] . ' ' . $customer['lastName'];
                
                // Redirect to register product page
                header("Location: register_product.php");
                exit();
            } else {
                // Customer not found
                $error_message = 'No customer found with email: ' . htmlspecialchars($email);
            }
            
        } catch (PDOException $e) {
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Customer Login</h3>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="customer_login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address:</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email"
                                       value="<?php echo htmlspecialchars($email); ?>"
                                       required>
                                <div class="form-text">
                                    Enter your registered email address
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                Login
                            </button>
                        </form>
                        
                    </div>
                </div>
                
                <div class="mt-3 text-center text-muted">
                    <small>For testing: try john.smith@example.com</small>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>