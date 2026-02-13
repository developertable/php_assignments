<?php
require('database.php');

// Get incident ID from URL
$incident_id = filter_input(INPUT_GET, 'incident_id', FILTER_VALIDATE_INT);

if ($incident_id === false || $incident_id === null) {
    $error_message = 'Invalid incident ID.';
    include('error.php');
    exit();
}

// Get incident details
try {
    $query = 'SELECT i.incidentID, i.title, i.description, i.dateOpened,
                     c.firstName AS customerFirstName, c.lastName AS customerLastName, 
                     c.email, c.phone,
                     p.productCode, p.productName, p.version
              FROM incidents i
              INNER JOIN customers c ON i.customerID = c.customerID
              INNER JOIN products p ON i.productID = p.productID
              WHERE i.incidentID = :incident_id AND i.techID IS NULL';
    
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

// Get all available technicians
try {
    $query = 'SELECT techID, firstName, lastName, email
              FROM technicians
              ORDER BY firstName, lastName';
    
    $statement = $db->prepare($query);
    $statement->execute();
    $technicians = $statement->fetchAll();
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
    <title>Assign Incident #<?php echo $incident['incidentID']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h3 class="mb-0">Assign Incident to Technician</h3>
                        <small>Incident #<?php echo $incident['incidentID']; ?></small>
                    </div>
                    <div class="card-body">
                        
                        <!-- Incident Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <strong>Incident Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>Customer:</strong></div>
                                    <div class="col-md-9">
                                        <?php echo htmlspecialchars($incident['customerFirstName'] . ' ' . 
                                                                   $incident['customerLastName']); ?>
                                        <br>
                                        <small class="text-muted">
                                            Email: <?php echo htmlspecialchars($incident['email']); ?>
                                            <?php if ($incident['phone']): ?>
                                                | Phone: <?php echo htmlspecialchars($incident['phone']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>Product:</strong></div>
                                    <div class="col-md-9">
                                        <?php echo htmlspecialchars($incident['productName']) . ' ' . 
                                                  htmlspecialchars($incident['version']); ?>
                                        (<?php echo htmlspecialchars($incident['productCode']); ?>)
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>Date Opened:</strong></div>
                                    <div class="col-md-9">
                                        <?php 
                                            $date = new DateTime($incident['dateOpened']);
                                            echo $date->format('F j, Y \a\t g:i A'); 
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>Title:</strong></div>
                                    <div class="col-md-9">
                                        <?php echo htmlspecialchars($incident['title']); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><strong>Description:</strong></div>
                                    <div class="col-md-9">
                                        <div class="alert alert-secondary mb-0">
                                            <?php echo nl2br(htmlspecialchars($incident['description'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assignment Form -->
                        <form action="process_assignment.php" method="post">
                            <input type="hidden" name="incident_id" value="<?php echo $incident_id; ?>">
                            
                            <div class="mb-4">
                                <label for="tech_id" class="form-label">
                                    <strong>Assign to Technician:</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg" id="tech_id" name="tech_id" required>
                                    <option value="">-- Select a Technician --</option>
                                    <?php foreach ($technicians as $tech): ?>
                                        <option value="<?php echo $tech['techID']; ?>">
                                            <?php echo htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']); ?>
                                            (<?php echo htmlspecialchars($tech['email']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    Select the technician who will handle this incident
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-person-check"></i> Assign Incident
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    ← Back to Incident List
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