<?php
require_once('database.php');

// Get form data
$product_code = filter_input(INPUT_POST, 'product_code');
$product_name = filter_input(INPUT_POST, 'product_name');
$version = filter_input(INPUT_POST, 'version');
$release_date = filter_input(INPUT_POST, 'release_date');

// Validate inputs
if ($product_code == NULL || $product_name == NULL || 
    $version == NULL || $release_date == NULL) {
    $error_message = "All fields are required. Please fill in all fields.";
    include('error.php');
    exit();
}

// Insert product into database
$query = 'INSERT INTO products (productCode, productName, version, releaseDate)
          VALUES (:product_code, :product_name, :version, :release_date)';
$statement = $db->prepare($query);
$statement->bindValue(':product_code', $product_code);
$statement->bindValue(':product_name', $product_name);
$statement->bindValue(':version', $version);
$statement->bindValue(':release_date', $release_date);
$statement->execute();
$statement->closeCursor();

// Redirect to product list
header("Location: index.php");
?>