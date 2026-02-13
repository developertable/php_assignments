<?php
require('database.php');

// Get all unassigned incidents (techID IS NULL)
try {
    $query = 'SELECT i.incidentID, i.title, i.dateOpened,
                     c.firstName AS customerFirstName, c.lastName AS customerLastName, c.email,
                     p.productCode, p.productName, p.version
              FROM incidents i
              INNER JOIN customers c ON i.customerID = c.customerID
              INNER JOIN products p ON i.productID = p.productID
              WHERE i.techID IS NULL AND i.dateClosed IS NULL
              ORDER BY i.dateOpened ASC';
    
    $statement = $db->prepare($query);
    $statement->execute();
    $incidents = $statement->fetchAll();
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
    <title>Assign Incidents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-clipboard-check"></i> Assign Incidents to Technicians</h2>
            <a href="../index.php" class="btn btn-outline-secondary">← Home</a>
        </div>
        
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Unassigned Incidents</h4>
            </div>
            <div class="card-body">
                
                <?php if (count($incidents) > 0): ?>
                    
                    <div class="alert alert-warning">
                        <strong><?php echo count($incidents); ?> incident(s)</strong> waiting to be assigned to a technician.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Incident #</th>
                                    <th>Date Opened</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($incidents as $incident): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">#<?php echo $incident['incidentID']; ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                                $date = new DateTime($incident['dateOpened']);
                                                echo $date->format('M d, Y g:i A'); 
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($incident['customerFirstName'] . ' ' . 
                                                                       $incident['customerLastName']); ?>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($incident['email']); ?></small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($incident['productName']) . ' ' . 
                                                      htmlspecialchars($incident['version']); ?>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($incident['productCode']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($incident['title']); ?></td>
                                        <td>
                                            <a href="assign_form.php?incident_id=<?php echo $incident['incidentID']; ?>" 
                                               class="btn btn-danger btn-sm">
                                                <i class="bi bi-person-plus"></i> Assign
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php else: ?>
                    
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle"></i> All Caught Up!</h5>
                        <p class="mb-0">There are no unassigned incidents at this time.</p>
                    </div>
                    
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>
</html>