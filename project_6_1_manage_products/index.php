<?php
require_once('database.php');

// get all products from database
$query = 'SELECT * FROM products ORDER BY productID';
$statement = $db->prepare($query);
$statement->execute();
$products = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product List - Tech Support</title>
  <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  </head>
<body>
  <nav class="navbar navbar-dark bg-dark">
    <div class="container">
      <span class="navbar-brand mb-0 h1">Tech Support - Product Manager</span>
    </div>
  </nav>
    <div class="container mt-4">
        <h2>Product List</h2>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Version</th>
                    <th>Release Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['productCode']); ?></td>
                    <td><?php echo htmlspecialchars($product['productName']); ?></td>
                    <td><?php echo htmlspecialchars($product['version']); ?></td>
                    <td><?php echo htmlspecialchars($product['releaseDate']); ?></td>
                    <td>
                        <form action="delete_product.php" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['productID']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="add_product_form.php" class="btn btn-success">Add Product</a>
    </div>
</body>
</html>