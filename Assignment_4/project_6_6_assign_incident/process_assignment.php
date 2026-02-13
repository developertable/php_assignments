<?php
require('database.php');

// Get form data
$incident_id = filter_input(INPUT_POST, 'incident_id', FILTER_VALIDATE_INT);
$tech_id = filter_input(INPUT_POST, 'tech_id', FILTER_VALIDATE_INT);

// Validate
if ($incident_id === false || $incident_id === null) {
    $error_message = 'Invalid incident ID.';
    include('error.php');
    exit();
}

if ($tech_id === false || $tech_id === null) {
    $error_message = 'Please select a technician.';
    include('error.php');
    exit();
}

// Verify incident exists and is unassigned
try {
    $query = 'SELECT incidentID FROM incidents 
              WHERE incidentID = :incident_id AND techID IS NULL';
    $statement = $db->prepare($query);
    $statement->bindValue(':incident_id', $incident_id);
    $statement->execute();
    $incident = $statement->fetch();
    $statement->closeCursor();
    
    if (!$incident) {
        $error_message = 'Incident not found or already assigned.';
        include('error.php');
        exit();
    }
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}

// Assign technician to incident
try {
    $query = 'UPDATE incidents 
              SET techID = :tech_id 
              WHERE incidentID = :incident_id';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':tech_id', $tech_id);
    $statement->bindValue(':incident_id', $incident_id);
    $statement->execute();
    $statement->closeCursor();
    
    // Get technician details for confirmation
    $query = 'SELECT firstName, lastName, email FROM technicians WHERE techID = :tech_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':tech_id', $tech_id);
    $statement->execute();
    $technician = $statement->fetch();
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
    <title>Incident Assigned Successfully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-check-circle"></i> Incident Assigned Successfully
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="alert alert-success">
                            <h5>Assignment Complete!</h5>
                            <p class="mb-0">The incident has been assigned to a technician.</p>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Assignment Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Incident Number:</strong></div>
                                    <div class="col-md-8">
                                        <span class="badge bg-primary">#<?php echo $incident_id; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>Assigned To:</strong></div>
                                    <div class="col-md-8">
                                        <?php echo htmlspecialchars($technician['firstName'] . ' ' . 
                                                                   $technician['lastName']); ?>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($technician['email']); ?></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><strong>Status:</strong></div>
                                    <div class="col-md-8">
                                        <span class="badge bg-info">Assigned - In Progress</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><strong>Next Steps:</strong></h6>
                            <ul class="mb-0">
                                <li>The technician can now view this incident in their dashboard</li>
                                <li>The technician will work on resolving the issue</li>
                                <li>Updates can be added to track progress</li>
                                <li>The incident can be closed once resolved</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-danger">
                                Assign Another Incident
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
