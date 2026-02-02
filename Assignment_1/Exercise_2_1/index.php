<?php 
    // Set default values for initial page load
    if (!isset($product_description)) { $product_description = ''; } 
    if (!isset($list_price)) { $list_price = ''; } 
    if (!isset($discount_percent)) { $discount_percent = ''; } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Discount Calculator</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
  <main>
    <h1>Product Discount Calculator</h1>


  <!-- Displays error message -->
    <?php if (!empty($error_message)) { ?>
    <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="display_discount.php" method="post">
      <div id="data">
        <label >Product Description:</label>
        <input type="text" name="product_description"><br>

        <label>List Price:</label>
        <input type="text" name="list_price"><br>

        <label>Discount Percent:</label>
        <input type="text" name="discount_percent"><span>%</span><br>
      </div>
      <div id="butons">
        <label>&nbsp;</label>
        <input type="submit" value="Calculate Discount"><br>
      </div>
    </form>
  </main>
  
</body>
</html>