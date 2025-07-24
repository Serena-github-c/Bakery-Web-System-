<?php
session_start();
require_once '../inc/connect.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}

// Fetch customers and products for dropdowns
$customers = mysqli_query($connection, "SELECT customer_id, CONCAT(first_name,' ',last_name) AS name FROM customers");
$products = mysqli_query($connection, "
  SELECT
    p.product_id,
    p.name,
    p.price_per_unit,
    -- derive unit_type on the fly:
    CASE
      WHEN p.name LIKE '%Cake%' THEN 'serving'
      ELSE 'dozen'
    END AS unit_type
  FROM products p
");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id    = (int) $_POST['customer_id'];
    $product_id     = (int) $_POST['product_id'];
    $quantity       = (int) $_POST['quantity'];
    $unit_type      = trim($_POST['unit_type']);
    $unit_size      = (float) $_POST['unit_size'];
    $price_per_unit = (float) $_POST['price_per_unit'];
    $notes          = trim($_POST['notes']);
    $pickup_time    = trim($_POST['pickup_time']);
    $is_baked       = isset($_POST['is_baked']) ? 1 : 0;
    $is_picked_up   = isset($_POST['is_picked_up']) ? 1 : 0;

    // 1) Insert into orders
    $sql1 = "INSERT INTO orders 
             (customer_id, notes, pickup_time, is_baked, is_picked_up, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt1 = mysqli_prepare($connection, $sql1);
    mysqli_stmt_bind_param($stmt1, "issii", 
        $customer_id, $notes, $pickup_time, $is_baked, $is_picked_up
    );
    mysqli_stmt_execute($stmt1);
    $new_order_id = mysqli_insert_id($connection);
    mysqli_stmt_close($stmt1);

    // 2) Insert into order_products
    $sql2 = "INSERT INTO order_products 
             (order_id, product_id, quantity, unit_type, unit_size, price_per_unit)
             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt2 = mysqli_prepare($connection, $sql2);
    mysqli_stmt_bind_param($stmt2, "iiisid", 
        $new_order_id, $product_id, $quantity, $unit_type, $unit_size, $price_per_unit
    );
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    header('Location: orders.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create New Order</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="../js/order_form.js"></script>
</head>

<body>
  <div class="admin-container">
    <h2>Create New Order</h2>

    <form method="post">
      <label>Customer:</label><br>
      <select name="customer_id" required>
        <option value="">-- Select Customer --</option>
        <?php while($c = mysqli_fetch_assoc($customers)): ?>
          <option value="<?php echo $c['customer_id']; ?>">
            <?php echo htmlspecialchars($c['name']); ?>
          </option>
        <?php endwhile; ?>
      </select><br><br>



      <label>Product:</label><br>
      <select name="product_id" id="product-select" required>
        <option value="">-- Select Product --</option>
        <?php while($p = mysqli_fetch_assoc($products)): ?>
            <option
            value="<?php echo $p['product_id']; ?>"
            data-price="<?php echo $p['price_per_unit']; ?>"
            data-unit-type="<?php echo $p['unit_type']; ?>"
            >
            <?= htmlspecialchars($p['name']) ?> —
            $<?= number_format($p['price_per_unit'], 2) ?> /
            <?= $p['unit_type'] ?>
            </option>
        <?php endwhile; ?>
        </select> <br><br>


        <label>Price per Unit:</label><br>
        <input 
        type="text" 
        name="price_per_unit" 
        id="price-per-unit" 
        value="" 
        readonly 
        ><br><br>


      <label>Quantity:</label><br>
      <input type="number" name="quantity" min="1" required><br><br>

      <label>Unit Type:</label><br>
      <select name="unit_type" required>
        <option value="">-- Select type --</option>
        <option value="dozen">Dozen</option>
        <option value="serving">Serving</option>
        </select><br><br>


      <label>Unit Size:</label><br>
      <input type="number" name="unit_size" step="0.5" required><br><br>

      <label>Pickup Time:</label><br>
      <input type="datetime-local" name="pickup_time"   min="<?php echo date('Y-m-d\TH:i'); ?>" ><br><br>

      <label>Total Price:</label><br>
      <span id="line-total">—</span><br><br>



      <label>Notes:</label><br>
      <textarea name="notes" rows="3"></textarea><br><br>

      <label>
        <input type="checkbox" name="is_baked"> Is Baked
      </label><br>

      <label>
        <input type="checkbox" name="is_picked_up"> Is Picked Up
      </label><br><br>

      <button type="submit" class="status-btn status-yes">Create Order</button>
      <a href="orders.php" class="status-btn status-no">Cancel</a>
    </form>
  </div>

  <script>
    // (Optional) auto-fill price_per_unit when product changes
    document.querySelector('select[name="product_id"]')
      .addEventListener('change', function(){
        const opt = this.selectedOptions[0];
        document.querySelector('input[name="price_per_unit"]').value = opt.dataset.price || '';
      });
  </script>
</body>
</html>
