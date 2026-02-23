<?php
/**
 * Technician Dashboard - My Incidents
 * Displays all incidents assigned to logged-in technician with filtering
 */

session_start();

// Include helper functions
require_once('../includes/session_helper.php');
require_once('../includes/db_functions.php');
require('database.php');

// Require technician login
require_technician_login();

// Get technician ID from session
$tech_id = get_technician_id();

// Get filter from URL (default: open)
$filter = filter_input(INPUT_GET, 'filter') ?? 'open';

// Validate filter value
if (!in_array($filter, ['open', 'closed', 'all'])) {
    $filter = 'open';
}

// Get incidents based on filter
$incidents = get_technician_incidents($db, $tech_id, $filter);

// Get incident counts for statistics
$counts = get_technician_incident_counts($db, $tech_id);

// Set page title and breadcrumbs
$page_title = 'My Incidents - Technician Dashboard';
$breadcrumbs = [
    ['name' => 'Technicians', 'url' => 'technician_login.php'],
    ['name' => 'My Incidents', 'url' => '']
];

// Include header
include('../includes/header.php');
?>

<!-- Page Content -->
<div class="container mt-4">
    
    <?php include('../includes/breadcrumb.php'); ?>
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-clipboard-pulse"></i> My Incidents
            </h2>
            <p class="text-muted mb-0">
                Logged in as: <strong><?php echo htmlspecialchars($_SESSION['tech_name']); ?></strong>
                <small class="text-muted">(<?php echo htmlspecialchars($_SESSION['tech_email']); ?>)</small>
            </p>
        </div>
        <div class="no-print">
            <a href="logout.php" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <a href="../index.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-house"></i> Home
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Open Incidents</h6>
                            <h2 class="mb-0"><?php echo $counts['open']; ?></h2>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Closed Incidents</h6>
                            <h2 class="mb-0"><?php echo $counts['closed']; ?></h2>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card stats-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Assigned</h6>
                            <h2 class="mb-0"><?php echo $counts['total']; ?></h2>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-clipboard-data-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <?php echo $filter == 'open' ? 'active' : ''; ?>" 
               href="index.php?filter=open">
                <i class="bi bi-exclamation-circle"></i> 
                Open 
                <span class="badge bg-warning text-dark"><?php echo $counts['open']; ?></span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?php echo $filter == 'closed' ? 'active' : ''; ?>" 
               href="index.php?filter=closed">
                <i class="bi bi-check-circle"></i> 
                Closed 
                <span class="badge bg-success"><?php echo $counts['closed']; ?></span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?php echo $filter == 'all' ? 'active' : ''; ?>" 
               href="index.php?filter=all">
                <i class="bi bi-list-ul"></i> 
                All 
                <span class="badge bg-secondary"><?php echo $counts['total']; ?></span>
            </a>
        </li>
    </ul>
    
    <!-- Incidents Table -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-clipboard-check"></i>
                <?php 
                    if ($filter == 'open') echo 'Open Incidents';
                    elseif ($filter == 'closed') echo 'Closed Incidents';
                    else echo 'All Incidents';
                ?>
            </h5>
        </div>
        <div class="card-body p-0">
            
            <?php if (count($incidents) > 0): ?>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="8%">ID</th>
                                <th width="15%">Date Opened</th>
                                <th width="18%">Customer</th>
                                <th width="18%">Product</th>
                                <th width="25%">Title</th>
                                <th width="8%">Status</th>
                                <th width="8%">Action</th>
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
                                        <?php echo htmlspecialchars($incident['productName']); ?>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($incident['productCode']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($incident['title']); ?>
                                    </td>
                                    <td>
                                        <?php if ($incident['dateClosed']): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Closed
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <?php 
                                                    $closedDate = new DateTime($incident['dateClosed']);
                                                    echo $closedDate->format('M d'); 
                                                ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock"></i> Open
                                            </span>
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
                
                <div class="alert alert-info m-3">
                    <h5>
                        <i class="bi bi-info-circle"></i> 
                        No Incidents Found
                    </h5>
                    <p class="mb-0">
                        <?php 
                            if ($filter == 'open') {
                                echo 'You have no open incidents. Great job! 🎉';
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

<?php include('../includes/footer.php'); ?>