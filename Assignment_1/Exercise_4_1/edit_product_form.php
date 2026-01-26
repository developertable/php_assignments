<?php
require('database.php');

// Get the product ID
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

if ($product_id == NULL || $product_id == FALSE) {
    $error = "Invalid product ID.";
    include('error.php');
    exit();
}

// Get the product data
$query = 'SELECT * FROM products WHERE productID = :product_id';
$statement = $db->prepare($query);
$statement->bindValue(':product_id', $product_id);
$statement->execute();
$product = $statement->fetch();
$statement->closeCursor();

// Get all categories for dropdown
$query_categories = 'SELECT * FROM categories ORDER BY categoryID';
$statement2 = $db->prepare($query_categories);
$statement2->execute();
$categories = $statement2->fetchAll();
$statement2->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <header><h1>Product Manager</h1></header>

    <main>
        <h1>Edit Product</h1>
        <form action="update_product.php" method="post">
            
            <input type="hidden" name="product_id" value="<?php echo $product['productID']; ?>">

            <label>Category:</label>
            <select name="category_id">
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['categoryID']; ?>"
                    <?php if ($category['categoryID'] == $product['categoryID']) echo 'selected'; ?>>
                    <?php echo $category['categoryName']; ?>
                </option>
            <?php endforeach; ?>
            </select><br>

            <label>Code:</label>
            <input type="text" name="code" value="<?php echo htmlspecialchars($product['productCode']); ?>"><br>

            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['productName']); ?>"><br>

            <label>List Price:</label>
            <input type="text" name="price" value="<?php echo htmlspecialchars($product['listPrice']); ?>"><br>

            <label>&nbsp;</label>
            <input type="submit" value="Update Product"><br>
        </form>
        <p><a href="index.php">View Product List</a></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Guitar Shop, Inc.</p>
    </footer>
</body>
</html>