<?php
session_start();
require_once '../inc/connect.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}

$order_id = $_GET['id'] ?? null;

// Fetch all the fields you want to display
$sql = "
    SELECT
        o.order_id,
        CONCAT(c.first_name,' ',c.last_name) AS customer_name,
        o.created_at,
        p.name           AS product_name,
        op.quantity,
        op.unit_type,
        op.unit_size,
        o.notes,
        o.pickup_time,
        ROUND(op.quantity * op.unit_size * op.price_per_unit, 2) AS item_total_price,
        o.is_baked,
        o.is_picked_up
    FROM orders o
    JOIN customers c     ON o.customer_id   = c.customer_id
    JOIN order_products op ON o.order_id      = op.order_id
    JOIN products p      ON op.product_id    = p.product_id
    WHERE o.order_id = ?
";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    echo "Order not found.";
    exit;
}
mysqli_stmt_close($stmt);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Order</title>
  <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
  <div class="admin-container">
            <h2>Order #<?php echo htmlspecialchars($row['order_id']); ?></h2>                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($row['customer_name']); ?></p>
    <p><strong>Created At:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
    <hr>

    <h3>Product Details</h3>
    <p><strong>Product:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['quantity']); ?></p>
    <p><strong>Unit Type:</strong> <?php echo htmlspecialchars($row['unit_type']); ?></p>
    <p><strong>Unit Size:</strong> <?php echo htmlspecialchars($row['unit_size']); ?></p>
    <p><strong>Total Price:</strong> <?php echo htmlspecialchars($row['item_total_price']); ?></p>
    <hr>

    <h3>Order Info</h3>
    <p><strong>Pickup Time:</strong> <?php echo htmlspecialchars($row['pickup_time']); ?></p>
    <p><strong>Notes:</strong><br><?php echo nl2br(htmlspecialchars($row['notes'])); ?></p>
    <p>
      <strong>Is Baked:</strong>
      <span class="status-btn <?php echo $row['is_baked'] ? 'status-yes' : 'status-no'; ?>">
        <?php echo $row['is_baked'] ? 'Yes' : 'No'; ?>
      </span>
    </p>
    <p>
      <strong>Is Picked Up:</strong>
      <span class="status-btn <?php echo $row['is_picked_up'] ? 'status-yes' : 'status-no'; ?>">
        <?php echo $row['is_picked_up'] ? 'Yes' : 'No'; ?>
      </span>
    </p>

    <div style="margin-top:30px;">
      <a href="edit.php?id=<?php echo $order_id; ?>" class="status-btn status-yes" style="margin-right:10px;">Edit</a>
      <a href="delete.php?id=<?php echo $order_id; ?>" class="status-btn status-no" style="margin-right:10px;">Delete</a>
      <a href="orders.php" class="status-btn" style="background:#6c757d;">Back</a>
    </div>
  </div>
</body>
</html>