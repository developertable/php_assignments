<?php
session_start();
require('database.php');

// Initialize variables
$email = '';
$error_message = '';
$technician = null;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get email from form
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // Validate email was entered and is valid
    if ($email === false || $email === null) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Email is valid - check if technician exists in database
        try {
            $query = 'SELECT * FROM technicians WHERE email = :email';
            $statement = $db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $technician = $statement->fetch();
            $statement->closeCursor();
            
            // Check if technician was found
            if ($technician) {
                // Technician exists - log them in!
                $_SESSION['tech_id'] = $technician['techID'];
                $_SESSION['tech_email'] = $technician['email'];
                $_SESSION['tech_name'] = $technician['firstName'] . ' ' . $technician['lastName'];
                
                // Redirect to incident list
                header("Location: index.php");
                exit();
            } else {
                // Technician not found
                $error_message = 'No technician found with email: ' . htmlspecialchars($email);
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
    <title>Technician Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0">Technician Login</h3>
                        <small>View & Update Assigned Incidents</small>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="technician_login.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address:</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email"
                                       value="<?php echo htmlspecialchars($email); ?>"
                                       required>
                                <div class="form-text">
                                    Enter your technician email address
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-info w-100 text-white">
                                Login to View Incidents
                            </button>
                        </form>
                        
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="../index.php" class="btn btn-link">← Back to Home</a>
                </div>
                
                <div class="mt-2 text-center text-muted">
                    <small>For testing: Check technicians table for available emails</small>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>