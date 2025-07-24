<?php
session_start();
require_once '../inc/connect.php';

// Protect page
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../login.php?admin=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css"> 
  
</head>
<body>
  <div class="dashboard-container">
    <h1>🎂 Admin Dashboard</h1>
    <p>Welcome, admin!</p>

    <?php
    // Get quick stats
    $orderCount = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM orders"))[0];
    $pendingCount = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM orders WHERE is_picked_up = 0"))[0];
    $productCount = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM products"))[0];
    $pendingReviews = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM reviews WHERE is_visible = 0"))[0];
    ?>

    <div class="card stats">
      <div>
        <h3>Total Orders</h3>
        <p><?php echo $orderCount; ?></p>
      </div>
      <div>
        <h3>Pending Pickups</h3>
        <p><?php echo $pendingCount; ?></p>
      </div>
      <div>
        <h3>Total Products</h3>
        <p><?php echo $productCount; ?></p>
      </div>
      <div>
        <h3>Reviews Awaiting Approval</h3>
        <p><?php echo $pendingReviews; ?></p>
      </div>
    </div>

    <div class="card actions">
      <h3>Quick Access</h3>
      <a href="orders.php">📦 View Orders</a>
      <a href="products.php">🧁 Manage Products</a>
      <a href="reviews.php">⭐ Manage Reviews</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>
</body>
</html>
