<?php
session_start();

// Check if technician is logged in
if (!isset($_SESSION['tech_id'])) {
    header("Location: technician_login.php");
    exit();
}

require('database.php');

// Get technician ID from session
$tech_id = $_SESSION['tech_id'];

// Get incident ID from form
$incident_id = filter_input(INPUT_POST, 'incident_id', FILTER_VALIDATE_INT);

if ($incident_id === false || $incident_id === null) {
    $error_message = 'Invalid incident ID.';
    include('error.php');
    exit();
}

// Verify incident exists, is assigned to this tech, and is not already closed
try {
    $query = 'SELECT incidentID, title FROM incidents 
              WHERE incidentID = :incident_id 
              AND techID = :tech_id 
              AND dateClosed IS NULL';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':incident_id', $incident_id);
    $statement->bindValue(':tech_id', $tech_id);
    $statement->execute();
    $incident = $statement->fetch();
    $statement->closeCursor();
    
    if (!$incident) {
        $error_message = 'Incident not found, not assigned to you, or already closed.';
        include('error.php');
        exit();
    }
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}

// Close the incident (set dateClosed to current timestamp)
try {
    $query = 'UPDATE incidents 
              SET dateClosed = NOW() 
              WHERE incidentID = :incident_id';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':incident_id', $incident_id);
    $statement->execute();
    $statement->closeCursor();
    
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
    <title>Incident Closed Successfully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-check-circle-fill"></i> Incident Closed Successfully
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="alert alert-success">
                            <h5>Great Work!</h5>
                            <p class="mb-0">
                                Incident #<?php echo $incident_id; ?> has been marked as resolved and closed.
                            </p>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Closure Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Incident Number:</strong></div>
                                    <div class="col-md-8">
                                        <span class="badge bg-primary">#<?php echo $incident_id; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Title:</strong></div>
                                    <div class="col-md-8">
                                        <?php echo htmlspecialchars($incident['title']); ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Closed By:</strong></div>
                                    <div class="col-md-8">
                                        <?php echo htmlspecialchars($_SESSION['tech_name']); ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Date Closed:</strong></div>
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
                                        <span class="badge bg-success">Closed - Resolved</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><strong>What happens next:</strong></h6>
                            <ul class="mb-0">
                                <li>This incident is now marked as resolved in the system</li>
                                <li>The incident will appear in your "Closed" incidents list</li>
                                <li>The customer can see that their issue has been resolved</li>
                                <li>Your statistics have been updated</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-info text-white btn-lg">
                                <i class="bi bi-clipboard-pulse"></i> Return to Dashboard
                            </a>
                            <a href="index.php?filter=closed" class="btn btn-outline-secondary">
                                View Closed Incidents
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