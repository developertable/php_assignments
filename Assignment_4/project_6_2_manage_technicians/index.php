<?php
require_once('database.php');

// Get all technicians from database
$query = 'SELECT * FROM technicians ORDER BY techID';
$statement = $db->prepare($query);
$statement->execute();
$technicians = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician List - Tech Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Tech Support - Technician Manager</span>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Technician List</h2>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($technicians as $technician) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($technician['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($technician['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($technician['email']); ?></td>
                    <td><?php echo htmlspecialchars($technician['phone']); ?></td>
                    <td>
                        <form action="delete_technician.php" method="post" style="display:inline;">
                            <input type="hidden" name="tech_id" value="<?php echo $technician['techID']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="add_technician_form.php" class="btn btn-success">Add Technician</a>
    </div>
    <div class="mt-3 text-center">
        <a href="../index.php" class="btn btn-link">← Back to Home</a>
    </div>
</body>
</html>