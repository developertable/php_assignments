<?php
    // Set default values
    $investment = '';
    $interest_rate = '';
    $years = '';
    $error_message = '';
    $show_results = false;
    
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get and validate data
    $investment = filter_input(INPUT_POST, 'investment', FILTER_VALIDATE_FLOAT);
    $interest_rate = filter_input(INPUT_POST, 'interest_rate', FILTER_VALIDATE_FLOAT);
    $years = filter_input(INPUT_POST, 'years', FILTER_VALIDATE_INT);
    
    // Validate investment
    if ($investment === FALSE) {
        $error_message .= 'Investment must be a valid number.<br>';
    } else if ($investment <= 0) {
        $error_message .= 'Investment must be greater than zero.<br>';
    }
    
    // Validate interest rate
    if ($interest_rate === FALSE) {
        $error_message .= 'Interest rate must be a valid number.<br>';
    } else if ($interest_rate <= 0) {
        $error_message .= 'Interest rate must be greater than zero.<br>';
    } else if ($interest_rate > 15) {
        $error_message .= 'Interest rate must be less than or equal to 15.<br>';
    }
    
    // Validate years
    if ($years === FALSE) {
        $error_message .= 'Years must be a valid whole number.<br>';
    } else if ($years <= 0) {
        $error_message .= 'Years must be greater than zero.<br>';
    } else if ($years > 30) {
        $error_message .= 'Years must be less than 31.<br>';
    }
    // If no errors, calculate
    if ($error_message == '') {
        // Calculate future value with compound interest
        $future_value = $investment;
        for ($i = 1; $i <= $years; $i++) {
            $future_value += $future_value * $interest_rate * .01;
        }
        
        // Format for display
        $investment_f = '$'.number_format($investment, 2);
        $yearly_rate_f = $interest_rate.'%';
        $future_value_f = '$'.number_format($future_value, 2);
        
        // Set flag to show results
        $show_results = true;
        
        // Save years for display
        $years_display = $years;

        // CLEAR the input fields
        $investment = '';
        $interest_rate = '';
        $years = '';
    }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Future Value Calculator</title>
<link rel="stylesheet" href="main.css">
</head>
<body>
<main>
    <h1>Future Value Calculator</h1>
    
    <?php if (!empty($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>
    
    <form action="index.php" method="post">
        <div id="data">
            <label>Investment Amount:</label>
            <input type="text" name="investment" value="<?php echo htmlspecialchars($investment); ?>"><br>
            
            <label>Yearly Interest Rate:</label>
            <input type="text" name="interest_rate" value="<?php echo htmlspecialchars($interest_rate); ?>"><br>
            
            <label>Number of Years:</label>
            <input type="text" name="years" value="<?php echo htmlspecialchars($years); ?>"><br>
        </div>
        
        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Calculate"><br>
        </div>
    </form>

    <?php if ($show_results) { ?>
        <h2>Calculation Results</h2>
        
        <label>Investment Amount:</label>
        <span><?php echo htmlspecialchars($investment_f); ?></span><br>

        <label>Yearly Interest Rate:</label>
        <span><?php echo htmlspecialchars($yearly_rate_f); ?></span><br>

        <label>Number of Years:</label>
        <span><?php echo htmlspecialchars($years_display); ?></span><br>

        <label>Future Value:</label>
        <span><?php echo htmlspecialchars($future_value_f); ?></span><br>
        
        <p>This calculation was done on <?php echo date('m/d/Y'); ?>.</p>
    <?php } ?>
    
</main>
</body>
</html>