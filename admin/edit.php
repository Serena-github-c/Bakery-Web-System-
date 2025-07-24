<?php
session_start();
require_once '../inc/connect.php';
// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}
 
$order_id = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve submitted values
    $order_id    = (int) $_POST['order_id'];
    $quantity    = (int) $_POST['quantity'];
    $unit_size   = $_POST['unit_size']; 
    $notes       = trim($_POST['notes']);
    $pickup_time = trim($_POST['pickup_time']);

    // Update orders table
    $sql1 = "UPDATE orders 
             SET notes = ?, pickup_time = ?
             WHERE order_id = ?";
    $stmt1 = mysqli_prepare($connection, $sql1);
    mysqli_stmt_bind_param($stmt1, "ssi", $notes, $pickup_time, $order_id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // Update order_products table
    $sql2 = "UPDATE order_products 
            SET quantity=?, unit_size=?
            WHERE order_id=?";
    $stmt2 = mysqli_prepare($connection, $sql2);
    mysqli_stmt_bind_param($stmt2, "idi", $quantity, $unit_size,$order_id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    //redirect
    header("Location: orders.php");
    exit;
}

// Fetch existing values to fill the form
if ($order_id) {
    $sql = "
        SELECT
        op.quantity,
        op.unit_size,
        o.notes,
        o.pickup_time
        FROM orders o
        JOIN order_products op ON o.order_id = op.order_id
        WHERE o.order_id = ?
    ";

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$row = mysqli_fetch_assoc($result)) {
    // no such order
    header('Location: orders.php');
    exit;
}
    mysqli_stmt_close($stmt);
} else {
    header("Location: orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>

<body>
      <div class="admin-container">
  <h2>Edit Order #<?php echo htmlspecialchars($order_id); ?></h2>
    <p>Change Quantity, Notes or Pickup Time, then Save.</p>

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">        
     <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
      
     <label>Quantity:</label><br>
      <input type="number" name="quantity" 
             value="<?php echo htmlspecialchars($row['quantity']); ?>" 
             min="1" required><br><br>

      <label>Unit Size:</label><br>
        <input 
        type="number" 
        name="unit_size" 
        step="0.5"
        value="<?php echo htmlspecialchars($row['unit_size']); ?>" 
        required
        ><br><br>

      <label>Notes:</label><br>
      <textarea name="notes" rows="3"><?php echo htmlspecialchars($row['notes']); ?></textarea><br><br>

      <label>Pickup Time:</label><br>
      <input type="datetime-local" name="pickup_time"
             value="<?php 
               echo date('Y-m-d\TH:i', strtotime($row['pickup_time'])); 
             ?>"><br><br>

      <button type="submit" class="status-btn status-yes">Save Changes</button>
      <a href="orders.php" class="status-btn status-no">Cancel</a>
    </form>
  </div>
</body>
</html>