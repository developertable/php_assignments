<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Tech Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Tech Support - Product Manager</span>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Add Product</h2>
        
        <form action="add_product.php" method="post">
            <div class="mb-3">
                <label for="product_code" class="form-label">Product Code:</label>
                <input type="text" class="form-control" id="product_code" name="product_code" required>
                <small class="form-text text-muted">Example: DRAFT30</small>
            </div>

            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
                <small class="form-text text-muted">Example: Draft Manager</small>
            </div>

            <div class="mb-3">
                <label for="version" class="form-label">Version:</label>
                <input type="text" class="form-control" id="version" name="version" required>
                <small class="form-text text-muted">Example: 3.0</small>
            </div>

            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date:</label>
                <input type="date" class="form-control" id="release_date" name="release_date" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>