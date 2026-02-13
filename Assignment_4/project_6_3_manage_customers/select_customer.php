<?php
require_once('database.php');

// Initialize variables
$last_name = '';
$customers = array();

// Check if search was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $last_name = filter_input(INPUT_POST, 'last_name');
    
    if (!empty($last_name)) {
        // Search for customers by last name
        $query = 'SELECT * FROM customers WHERE lastName LIKE :last_name ORDER BY lastName, firstName';
        $statement = $db->prepare($query);
        $statement->bindValue(':last_name', '%' . $last_name . '%');
        $statement->execute();
        $customers = $statement->fetchAll();
        $statement->closeCursor();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Customers - Tech Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Tech Support - Customer Manager</span>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Search Customers</h2>
        
        <form action="select_customer.php" method="post" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="<?php echo htmlspecialchars($last_name); ?>" required>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <?php if (!empty($customers)) : ?>
            <h3>Search Results</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['city']); ?></td>
                        <td>
                            <form action="view_update_customer.php" method="post" style="display:inline;">
                                <input type="hidden" name="customer_id" value="<?php echo $customer['customerID']; ?>">
                                <button type="submit" class="btn btn-info btn-sm">Select</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <div class="alert alert-warning">No customers found with last name "<?php echo htmlspecialchars($last_name); ?>"</div>
        <?php endif; ?>
    </div>
    <div class="mt-3 text-center">
                    <a href="../index.php" class="btn btn-link">← Back to Home</a>
    </div>
</body>
</html>