<?php
session_start();
require 'inc/header.php';
require 'inc/connect.php';


$order_id = intval($_GET['order_id']);

// Fetch order and customer details
$order_query = $connection->prepare("
    SELECT o.order_id, o.pickup_time, o.notes, o.created_at, 
           c.first_name, c.last_name, c.email
    FROM orders o
    JOIN customers c ON o.customer_id = c.customer_id
    WHERE o.order_id = ?
");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch ordered products
$product_query = $connection->prepare("
    SELECT p.name, op.quantity, op.unit_type, op.unit_size, op.price_per_unit
    FROM order_products op
    JOIN products p ON op.product_id = p.product_id
    WHERE op.order_id = ?
");
$product_query->bind_param("i", $order_id);
$product_query->execute();
$products = $product_query->get_result();
?>


<div class="thank-you-box">
  <h1>🎉 Thank You, <?= htmlspecialchars($order['first_name']) ?>!</h1>
  <p>Your order #<?= $order['order_id'] ?> has been submitted.</p>
  <p>We'll contact you at <?= htmlspecialchars($order['email']) ?> to confirm your pickup on <strong><?= date("F j, Y \a\\t g:i A", strtotime($order['pickup_time'])) ?></strong>.</p>

  <h2>🧁 Order Summary</h2>
  <table class="order-summary-table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Unit Type</th>
        <th>Unit Size</th>
        <th>Quantity</th>
        <th>Price/Unit</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $total = 0;
      while ($row = $products->fetch_assoc()):
        $subtotal = $row['price_per_unit'] * $row['quantity'];
        $total += $subtotal;
      ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['unit_type']) ?></td>
          <td><?= intval($row['unit_size']) ?></td>
          <td><?= intval($row['quantity']) ?></td>
          <td>$<?= number_format($row['price_per_unit'], 2) ?></td>
          <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5"><strong>Total</strong></td>
        <td><strong>$<?= number_format($total, 2) ?></strong></td>
      </tr>
    </tfoot>
  </table>

  <a class="btn" href="products.php">Order More Cakes</a>
</div>

<?php
require 'inc/footer.php';
?>

