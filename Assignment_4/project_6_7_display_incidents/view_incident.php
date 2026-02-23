<?php
/**
 * View Incident Details
 * Displays complete incident information and allows technician to close it
 */

session_start();

// Include helpers
require_once('../includes/session_helper.php');
require_once('../includes/db_functions.php');
require('database.php');

// Require technician login
require_technician_login();

// Get technician ID and incident ID
$tech_id = get_technician_id();
$incident_id = filter_input(INPUT_GET, 'incident_id', FILTER_VALIDATE_INT);

// Validate incident ID
if ($incident_id === false || $incident_id === null) {
    $error_message = 'Invalid incident ID.';
    include('error.php');
    exit();
}

// Get incident details - verify it's assigned to this technician
try {
    $query = 'SELECT i.incidentID, i.title, i.description, i.dateOpened, i.dateClosed,
                     c.customerID, c.firstName AS customerFirstName, c.lastName AS customerLastName, 
                     c.email, c.phone, c.address, c.city, c.state, c.postalCode,
                     p.productID, p.productCode, p.productName, p.version,
                     t.firstName AS techFirstName, t.lastName AS techLastName, t.email AS techEmail
              FROM incidents i
              INNER JOIN customers c ON i.customerID = c.customerID
              INNER JOIN products p ON i.productID = p.productID
              INNER JOIN technicians t ON i.techID = t.techID
              WHERE i.incidentID = :incident_id AND i.techID = :tech_id';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':incident_id', $incident_id);
    $statement->bindValue(':tech_id', $tech_id);
    $statement->execute();
    $incident = $statement->fetch();
    $statement->closeCursor();
    
    if (!$incident) {
        $error_message = 'Incident not found or not assigned to you.';
        include('error.php');
        exit();
    }
    
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    include('error.php');
    exit();
}

// Calculate time open (if not closed)
$days_open = null;
if (!$incident['dateClosed']) {
    $dateOpened = new DateTime($incident['dateOpened']);
    $now = new DateTime();
    $interval = $dateOpened->diff($now);
    $days_open = $interval->days;
}

// Set page details
$page_title = 'Incident #' . $incident['incidentID'] . ' Details';
$breadcrumbs = [
    ['name' => 'Technicians', 'url' => 'technician_login.php'],
    ['name' => 'Dashboard', 'url' => 'index.php'],
    ['name' => 'Incident #' . $incident['incidentID'], 'url' => '']
];

include('../includes/header.php');
?>

<div class="container mt-4">
    
    <?php include('../includes/breadcrumb.php'); ?>
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-clipboard-data"></i> 
                Incident #<?php echo $incident['incidentID']; ?>
            </h2>
            <p class="text-muted mb-0">
                Assigned to: <strong><?php echo htmlspecialchars($_SESSION['tech_name']); ?></strong>
            </p>
        </div>
        <div class="no-print">
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
    
    <!-- Status Alert -->
    <?php if ($incident['dateClosed']): ?>
        <div class="alert alert-success">
            <h5>
                <i class="bi bi-check-circle-fill"></i> Incident Closed
            </h5>
            <p class="mb-0">
                This incident was closed on 
                <?php 
                    $closedDate = new DateTime($incident['dateClosed']);
                    echo $closedDate->format('F j, Y \a\t g:i A'); 
                ?>
            </p>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <h5>
                <i class="bi bi-exclamation-triangle-fill"></i> Incident Open
            </h5>
            <p class="mb-0">
                This incident has been open for <strong><?php echo $days_open; ?> day(s)</strong>.
                Please work on resolving this issue.
            </p>
        </div>
    <?php endif; ?>
    
    <!-- Incident Information -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-file-text"></i> Incident Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3"><strong>Incident Number:</strong></div>
                <div class="col-md-9">
                    <span class="badge bg-primary fs-6">#<?php echo $incident['incidentID']; ?></span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Title:</strong></div>
                <div class="col-md-9">
                    <?php echo htmlspecialchars($incident['title']); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Description:</strong></div>
                <div class="col-md-9">
                    <div class="alert alert-light mb-0">
                        <?php echo nl2br(htmlspecialchars($incident['description'])); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Date Opened:</strong></div>
                <div class="col-md-9">
                    <?php 
                        $dateOpened = new DateTime($incident['dateOpened']);
                        echo $dateOpened->format('F j, Y \a\t g:i A'); 
                    ?>
                </div>
            </div>
            <?php if ($incident['dateClosed']): ?>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Date Closed:</strong></div>
                    <div class="col-md-9">
                        <?php 
                            $dateClosed = new DateTime($incident['dateClosed']);
                            echo $dateClosed->format('F j, Y \a\t g:i A'); 
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-3"><strong>Status:</strong></div>
                <div class="col-md-9">
                    <?php if ($incident['dateClosed']): ?>
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle-fill"></i> Closed - Resolved
                        </span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark fs-6">
                            <i class="bi bi-clock-fill"></i> Open - In Progress
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Information -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="bi bi-person-circle"></i> Customer Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong>Name:</strong></div>
                <div class="col-md-9">
                    <?php echo htmlspecialchars($incident['customerFirstName'] . ' ' . 
                                               $incident['customerLastName']); ?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Email:</strong></div>
                <div class="col-md-9">
                    <a href="mailto:<?php echo htmlspecialchars($incident['email']); ?>">
                        <i class="bi bi-envelope"></i>
                        <?php echo htmlspecialchars($incident['email']); ?>
                    </a>
                </div>
            </div>
            <?php if ($incident['phone']): ?>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Phone:</strong></div>
                    <div class="col-md-9">
                        <a href="tel:<?php echo htmlspecialchars($incident['phone']); ?>">
                            <i class="bi bi-telephone"></i>
                            <?php echo htmlspecialchars($incident['phone']); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-3"><strong>Address:</strong></div>
                <div class="col-md-9">
                    <i class="bi bi-geo-alt"></i>
                    <?php echo htmlspecialchars($incident['address']); ?><br>
                    <?php echo htmlspecialchars($incident['city']) . ', ' . 
                              htmlspecialchars($incident['state']) . ' ' . 
                              htmlspecialchars($incident['postalCode']); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Information -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-box-seam"></i> Product Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong>Product Name:</strong></div>
                <div class="col-md-9">
                    <?php echo htmlspecialchars($incident['productName']); ?>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Version:</strong></div>
                <div class="col-md-9">
                    <?php echo htmlspecialchars($incident['version']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"><strong>Product Code:</strong></div>
                <div class="col-md-9">
                    <span class="badge bg-secondary">
                        <?php echo htmlspecialchars($incident['productCode']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <?php if (!$incident['dateClosed']): ?>
        <div class="card border-success mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check-square"></i> Close This Incident
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    Once you have resolved the issue, you can close this incident. 
                    This will mark it as complete and notify the customer.
                </p>
                
                <form action="close_incident.php" method="post" 
                      onsubmit="return confirm('Are you sure you want to close this incident? This action cannot be undone.');">
                    <input type="hidden" name="incident_id" value="<?php echo $incident_id; ?>">
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle-fill"></i> Mark as Resolved & Close Incident
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Keep Open & Return to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <i class="bi bi-lock-fill text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mb-3 mt-3">This incident is closed and cannot be modified.</p>
                <a href="index.php" class="btn btn-info text-white">
                    <i class="bi bi-arrow-left"></i> Return to Dashboard
                </a>
            </div>
        </div>
    <?php endif; ?>
    
</div>

<?php include('../includes/footer.php'); ?>