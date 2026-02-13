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

// Get filter from URL (default: open)
$filter = filter_input(INPUT_GET, 'filter');
if (!$filter) {
    $filter = 'open';
}

// Build query based on filter
try {
    if ($filter == 'open') {
        // Open incidents (not closed)
        $query = 'SELECT i.incidentID, i.title, i.description, i.dateOpened, i.dateClosed,
                         c.firstName AS customerFirstName, c.lastName AS customerLastName, 
                         c.email, c.phone,
                         p.productCode, p.productName, p.version
                  FROM incidents i
                  INNER JOIN customers c ON i.customerID = c.customerID
                  INNER JOIN products p ON i.productID = p.productID
                  WHERE i.techID = :tech_id AND i.dateClosed IS NULL
                  ORDER BY i.dateOpened ASC';
    } elseif ($filter == 'closed') {
        // Closed incidents
        $query = 'SELECT i.incidentID, i.title, i.description, i.dateOpened, i.dateClosed,
                         c.firstName AS customerFirstName, c.lastName AS customerLastName, 
                         c.email, c.phone,
                         p.productCode, p.productName, p.version
                  FROM incidents i
                  INNER JOIN customers c ON i.customerID = c.customerID
                  INNER JOIN products p ON i.productID = p.productID
                  WHERE i.techID = :tech_id AND i.dateClosed IS NOT NULL
                  ORDER BY i.dateClosed DESC';
    } else {
        // All incidents
        $query = 'SELECT i.incidentID, i.title, i.description, i.dateOpened, i.dateClosed,
                         c.firstName AS customerFirstName, c.lastName AS customerLastName, 
                         c.email, c.phone,
                         p.productCode, p.productName, p.version
                  FROM incidents i
                  INNER JOIN customers c ON i.customerID = c.customerID
                  INNER JOIN products p ON i.productID = p.productID
                  WHERE i.techID = :tech_id
                  ORDER BY i.dateOpened DESC';
    }
    
    $statement = $db->prepare($query);
    $statement->bindValue(':tech_id', $tech_id);
    $statement->execute();
    $incidents = $statement->fetchAll();
    $statement->closeCursor();
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}

// Count open vs closed
$open_count = 0;
$closed_count = 0;
try {
    $query = 'SELECT 
                 SUM(CASE WHEN dateClosed IS NULL THEN 1 ELSE 0 END) as open_count,
                 SUM(CASE WHEN dateClosed IS NOT NULL THEN 1 ELSE 0 END) as closed_count
              FROM incidents
              WHERE techID = :tech_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':tech_id', $tech_id);
    $statement->execute();
    $counts = $statement->fetch();
    $statement->closeCursor();
    
    $open_count = $counts['open_count'] ?? 0;
    $closed_count = $counts['closed_count'] ?? 0;
    
} catch (PDOException $e) {
    // If count query fails, just continue with 0s
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Incidents - Technician Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-clipboard-pulse"></i> My Incidents</h2>
                <p class="text-muted mb-0">
                    Logged in as: <strong><?php echo htmlspecialchars($_SESSION['tech_name']); ?></strong>
                    (<?php echo htmlspecialchars($_SESSION['tech_email']); ?>)
                </p>
            </div>
            <div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                <a href="../index.php" class="btn btn-outline-secondary btn-sm">Home</a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Open Incidents</h5>
                        <h2 class="mb-0"><?php echo $open_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Closed Incidents</h5>
                        <h2 class="mb-0"><?php echo $closed_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Assigned</h5>
                        <h2 class="mb-0"><?php echo $open_count + $closed_count; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link <?php echo $filter == 'open' ? 'active' : ''; ?>" 
                   href="index.php?filter=open">
                    Open (<?php echo $open_count; ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $filter == 'closed' ? 'active' : ''; ?>" 
                   href="index.php?filter=closed">
                    Closed (<?php echo $closed_count; ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $filter == 'all' ? 'active' : ''; ?>" 
                   href="index.php?filter=all">
                    All (<?php echo $open_count + $closed_count; ?>)
                </a>
            </li>
        </ul>
        
        <!-- Incidents List -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">
                    <?php 
                        if ($filter == 'open') echo 'Open Incidents';
                        elseif ($filter == 'closed') echo 'Closed Incidents';
                        else echo 'All Incidents';
                    ?>
                </h4>
            </div>
            <div class="card-body">
                
                <?php if (count($incidents) > 0): ?>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Date Opened</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Title</th>
                                    <th>Status</th>
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
                                                echo $date->format('M d, Y'); 
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($incident['customerFirstName'] . ' ' . 
                                                                       $incident['customerLastName']); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($incident['productName']); ?>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($incident['productCode']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($incident['title']); ?></td>
                                        <td>
                                            <?php if ($incident['dateClosed']): ?>
                                                <span class="badge bg-success">Closed</span>
                                                <br>
                                                <small class="text-muted">
                                                    <?php 
                                                        $closedDate = new DateTime($incident['dateClosed']);
                                                        echo $closedDate->format('M d'); 
                                                    ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Open</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="view_incident.php?incident_id=<?php echo $incident['incidentID']; ?>" 
                                               class="btn btn-info btn-sm text-white">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php else: ?>
                    
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> No Incidents Found</h5>
                        <p class="mb-0">
                            <?php 
                                if ($filter == 'open') {
                                    echo 'You have no open incidents. Great job!';
                                } elseif ($filter == 'closed') {
                                    echo 'You haven\'t closed any incidents yet.';
                                } else {
                                    echo 'No incidents have been assigned to you yet.';
                                }
                            ?>
                        </p>
                    </div>
                    
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>
</html>