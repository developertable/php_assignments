<?php
// Get the data from the form
$product_description = filter_input(INPUT_POST, 'product_description');
$list_price = filter_input(INPUT_POST, 'list_price', FILTER_VALIDATE_FLOAT);
$discount_percent = filter_input(INPUT_POST, 'discount_percent', FILTER_VALIDATE_FLOAT);

// Initialize Error message
$error_message = '';

//validate product description
if(empty($product_description)) {
  $error_message .= 'Product description is required.<br>';
}

//validate list price
if($list_price === FALSE) {
  $error_message .= 'List price must be a valid number.<br>';
} else if ($list_price <= 0) {
  $error_message .= 'List price must be greater than zero.<br>';
}

//Validate discount percent

if($discount_percent === FALSE) {
  $error_message .= 'Discount percent must be a valid number.<br>';
} else if ($discount_percent < 0) {
  $error_message .= 'Discount percent must be zero or greater.<br>';
} else if ($discount_percent > 100) {
  $error_message .= 'Discount percent cannot exceed 100.<br>';
}

// If there are errors, go back to the form
if ($error_message != '') {
    include('index.php');
    exit();
}

// Calculate Discount
$discount = $list_price * $discount_percent * .01;
$discount_price = $list_price - $discount;

// SALES TAX CALCULATION
$sales_tax_rate = 8;  // 8% sales tax
$sales_tax_amount = $discount_price * $sales_tax_rate * .01;

// Calculate final total (discounted price + sales tax)
$sales_total = $discount_price + $sales_tax_amount;

// Format for display
$list_price_f = "$".number_format($list_price, 2);
$discount_percent_f = $discount_percent."%";
$discount_f = "$".number_format($discount, 2);
$discount_price_f = "$".number_format($discount_price, 2);
$sales_tax_rate_f = $sales_tax_rate."%";
$sales_tax_amount_f = "$".number_format($sales_tax_amount, 2);
$sales_total_f = "$".number_format($sales_total, 2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Discount Calculator</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <main>
        <h1>Product Discount Calculator</h1>

        <label>Product Description:</label>
        <span><?php echo htmlspecialchars($product_description); ?></span><br>

        <label>List Price:</label>
        <span><?php echo $list_price_f; ?></span><br>

        <label>Discount Percent:</label>
        <span><?php echo $discount_percent_f; ?></span><br>

        <label>Discount Amount:</label>
        <span><?php echo $discount_f; ?></span><br>

        <label>Discount Price:</label>
        <span><?php echo $discount_price_f; ?></span><br>

        <label>Sales Tax Rate:</label>
        <span><?php echo $sales_tax_rate_f; ?></span><br>

        <label>Sales Tax Amount:</label>
        <span><?php echo $sales_tax_amount_f; ?></span><br>

        <label>Sales Total:</label>
        <span><?php echo $sales_total_f; ?></span><br>
    </main>
</body>
</html>