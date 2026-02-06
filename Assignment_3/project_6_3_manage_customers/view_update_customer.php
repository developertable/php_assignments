<?php
require_once('database.php');

// Get customer ID
$customer_id = filter_input(INPUT_POST, 'customer_id', FILTER_VALIDATE_INT);
if ($customer_id == NULL || $customer_id == FALSE) {
    $error_message = "Invalid customer ID.";
    include('error.php');
    exit();
}

// Check if form was submitted for update
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    // Get updated data
    $first_name = filter_input(INPUT_POST, 'first_name');
    $last_name = filter_input(INPUT_POST, 'last_name');
    $address = filter_input(INPUT_POST, 'address');
    $city = filter_input(INPUT_POST, 'city');
    $state = filter_input(INPUT_POST, 'state');
    $postal_code = filter_input(INPUT_POST, 'postal_code');
    $country_id = filter_input(INPUT_POST, 'country_id', FILTER_VALIDATE_INT);
    $phone = filter_input(INPUT_POST, 'phone');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    
    // Validate required fields
    if ($first_name == NULL || $last_name == NULL || $address == NULL ||
        $city == NULL || $state == NULL || $postal_code == NULL ||
        $country_id == NULL || $country_id == FALSE ||
        $email == NULL || $email == FALSE || $password == NULL) {
        $error_message = "All fields except phone are required. Email must be valid.";
        include('error.php');
        exit();
    }
    
    // Update customer in database
    $query = 'UPDATE customers SET
              firstName = :first_name,
              lastName = :last_name,
              address = :address,
              city = :city,
              state = :state,
              postalCode = :postal_code,
              countryID = :country_id,
              phone = :phone,
              email = :email,
              password = :password
              WHERE customerID = :customer_id';
    
    $statement = $db->prepare($query);
    $statement->bindValue(':first_name', $first_name);
    $statement->bindValue(':last_name', $last_name);
    $statement->bindValue(':address', $address);
    $statement->bindValue(':city', $city);
    $statement->bindValue(':state', $state);
    $statement->bindValue(':postal_code', $postal_code);
    $statement->bindValue(':country_id', $country_id);
    $statement->bindValue(':phone', $phone);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':customer_id', $customer_id);
    $statement->execute();
    $statement->closeCursor();
    
    // Redirect back to search
    header("Location: select_customer.php");
    exit();
}

// Get customer data
$query = 'SELECT * FROM customers WHERE customerID = :customer_id';
$statement = $db->prepare($query);
$statement->bindValue(':customer_id', $customer_id);
$statement->execute();
$customer = $statement->fetch();
$statement->closeCursor();

// Get all countries for dropdown
$query = 'SELECT * FROM countries ORDER BY countryName';
$statement = $db->prepare($query);
$statement->execute();
$countries = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View/Update Customer - Tech Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Tech Support - Customer Manager</span>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>View/Update Customer</h2>
        
        <form action="view_update_customer.php" method="post">
            <input type="hidden" name="customer_id" value="<?php echo $customer['customerID']; ?>">
            <input type="hidden" name="action" value="update">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="<?php echo htmlspecialchars($customer['firstName']); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="<?php echo htmlspecialchars($customer['lastName']); ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" class="form-control" id="address" name="address" 
                       value="<?php echo htmlspecialchars($customer['address']); ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" id="city" name="city" 
                           value="<?php echo htmlspecialchars($customer['city']); ?>" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="state" class="form-label">State:</label>
                    <input type="text" class="form-control" id="state" name="state" 
                           value="<?php echo htmlspecialchars($customer['state']); ?>" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="postal_code" class="form-label">Postal Code:</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" 
                           value="<?php echo htmlspecialchars($customer['postalCode']); ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="country_id" class="form-label">Country:</label>
                <select class="form-select" id="country_id" name="country_id" required>
                    <?php foreach ($countries as $country) : ?>
                        <option value="<?php echo $country['countryID']; ?>"
                                <?php if ($country['countryID'] == $customer['countryID']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($country['countryName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($customer['phone']); ?>">
                <small class="form-text text-muted">Optional</small>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" 
                       value="<?php echo htmlspecialchars($customer['password']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Customer</button>
            <a href="select_customer.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>