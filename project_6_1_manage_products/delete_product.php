<?php
require_once('database.php');

// Get product ID from form
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

// Validate product ID
if ($product_id == NULL || $product_id == FALSE) {
    $error_message = "Invalid product ID.";
    include('error.php');
    exit();
}

// Check if product has any incidents
$query_incidents = 'SELECT COUNT(*) as count FROM incidents WHERE productID = :product_id';
$statement1 = $db->prepare($query_incidents);
$statement1->bindValue(':product_id', $product_id);
$statement1->execute();
$incidents_result = $statement1->fetch();
$incident_count = $incidents_result['count'];
$statement1->closeCursor();

// Check if product has any registrations
$query_registrations = 'SELECT COUNT(*) as count FROM registrations WHERE productID = :product_id';
$statement2 = $db->prepare($query_registrations);
$statement2->bindValue(':product_id', $product_id);
$statement2->execute();
$registrations_result = $statement2->fetch();
$registration_count = $registrations_result['count'];
$statement2->closeCursor();

// If product has incidents or registrations, show error
if ($incident_count > 0) {
    $error_message = "Cannot delete this product. It has $incident_count incident(s) associated with it.";
    include('error.php');
    exit();
}

if ($registration_count > 0) {
    $error_message = "Cannot delete this product. It is registered to $registration_count customer(s).";
    include('error.php');
    exit();
}

// Delete the product from database
$query = 'DELETE FROM products WHERE productID = :product_id';
$statement = $db->prepare($query);
$statement->bindValue(':product_id', $product_id);
$statement->execute();
$statement->closeCursor();

// Redirect back to product list
header("Location: index.php");
?>