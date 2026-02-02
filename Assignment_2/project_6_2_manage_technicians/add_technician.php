<?php
require_once('database.php');

// Get form data
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone');
$password = filter_input(INPUT_POST, 'password');

// Validate inputs
if ($first_name == NULL || $last_name == NULL || 
    $email == NULL || $email == FALSE ||
    $phone == NULL || $password == NULL) {
    $error_message = "All fields are required and email must be valid.";
    include('error.php');
    exit();
}

// Insert technician into database
$query = 'INSERT INTO technicians (firstName, lastName, email, phone, password)
          VALUES (:first_name, :last_name, :email, :phone, :password)';
$statement = $db->prepare($query);
$statement->bindValue(':first_name', $first_name);
$statement->bindValue(':last_name', $last_name);
$statement->bindValue(':email', $email);
$statement->bindValue(':phone', $phone);
$statement->bindValue(':password', $password);
$statement->execute();
$statement->closeCursor();

// Redirect to technician list
header("Location: index.php");
?>