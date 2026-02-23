<?php
/**
 * Assign Incidents to Technicians
 * Admin interface to view unassigned incidents and assign them to technicians
 */

require('../includes/db_functions.php');
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

// Set page details
$page_title = 'Assign Incidents to Technicians';
$breadcrumbs = [
    ['name' => 'Administrators', 'url' => '../index.php'],
    ['name' => 'Assign Incidents', 'url' => '']
];

include('../includes/header.php');
?>

<div class="container mt-5">
    
    <?php include('../includes/breadcrumb.php'); ?>
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-clipboard-check"></i> Assign Incidents to Technicians
        </h2>
        <a href="../index.php" class="btn btn-outline-secondary">
            <i class="bi bi-house"></i> Home
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">
                <i class="bi bi-exclamation-triangle-fill"></i> Unassigned Incidents
            </h4>
        </div>
        <div class="card-body">
            
            <?php if (count($incidents) > 0): ?>
                
                <div class="alert alert-warning">
                    <strong>
                        <i class="bi bi-info-circle"></i> 
                        <?php echo count($incidents); ?> incident(s)
                    </strong> waiting to be assigned to a technician.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="8%">Incident #</th>
                                <th width="15%">Date Opened</th>
                                <th width="20%">Customer</th>
                                <th width="20%">Product</th>
                                <th width="27%">Title</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $incident): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">
                                            #<?php echo $incident['incidentID']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            $date = new DateTime($incident['dateOpened']);
                                            echo $date->format('M d, Y'); 
                                        ?>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo $date->format('g:i A'); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <strong>
                                            <?php echo htmlspecialchars($incident['customerFirstName'] . ' ' . 
                                                                       $incident['customerLastName']); ?>
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope"></i>
                                            <?php echo htmlspecialchars($incident['email']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($incident['productName']) . ' ' . 
                                                  htmlspecialchars($incident['version']); ?>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($incident['productCode']); ?>
                                        </small>
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
                    <h5>
                        <i class="bi bi-check-circle-fill"></i> All Caught Up!
                    </h5>
                    <p class="mb-0">There are no unassigned incidents at this time.</p>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
    
</div>

<?php include('../includes/footer.php'); ?>