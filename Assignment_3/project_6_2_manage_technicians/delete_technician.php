<?php
require_once('database.php');

// Get technician ID from form
$tech_id = filter_input(INPUT_POST, 'tech_id', FILTER_VALIDATE_INT);

// Validate technician ID
if ($tech_id == NULL || $tech_id == FALSE) {
    $error_message = "Invalid technician ID.";
    include('error.php');
    exit();
}

// Check if technician has any incidents assigned
$query_check = 'SELECT COUNT(*) as count FROM incidents WHERE techID = :tech_id';
$statement_check = $db->prepare($query_check);
$statement_check->bindValue(':tech_id', $tech_id);
$statement_check->execute();
$result = $statement_check->fetch();
$incident_count = $result['count'];
$statement_check->closeCursor();

// If technician has incidents, show error
if ($incident_count > 0) {
    $error_message = "Cannot delete this technician. They have $incident_count incident(s) assigned to them.";
    include('error.php');
    exit();
}

// Delete the technician from database
$query = 'DELETE FROM technicians WHERE techID = :tech_id';
$statement = $db->prepare($query);
$statement->bindValue(':tech_id', $tech_id);
$statement->execute();
$statement->closeCursor();

// Redirect back to technician list
header("Location: index.php");
?>