<?php
session_start();
require 'inc/connect.php';  

//  Must be logged in:
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit;
}
$customer_id = (int)$_SESSION['customer_id'];

//  Collect POST data:
$product_id = (int)($_POST['product_id'] ?? 0);
$unit_type  = $_POST['unit_type']  ?? '';
$unit_size  = (int)($_POST['unit_size'] ?? 0);
$quantity   = (int)($_POST['quantity']  ?? 0);
$notes      = mysqli_real_escape_string($connection, trim($_POST['notes'] ?? ''));
$pickup_time= $_POST['pickup_time'] ?? '';

if (!$product_id || !$unit_type || !$unit_size || !$quantity || !$pickup_time) {
    die('Please fill in all required fields.');
}

//  Insert into db
$stmt = $connection->prepare("INSERT INTO orders (customer_id, notes, pickup_time) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $customer_id, $notes, $pickup_time);
$stmt->execute();

$order_id = $stmt->insert_id;  // Get the new order's ID

//  Get product price
$price_query = $connection->prepare("SELECT price_per_unit FROM products WHERE product_id = ?");
$price_query->bind_param("i", $product_id);
$price_query->execute();
$price_result = $price_query->get_result();
$product = $price_result->fetch_assoc();
$price_per_unit = $product['price_per_unit'];  // you can modify this logic later to calculate based on unit_type

// 3. Insert into `order_products`
$stmt2 = $connection->prepare("INSERT INTO order_products (order_id, product_id, quantity, unit_type, unit_size, price_per_unit) VALUES (?, ?, ?, ?, ?, ?)");
$stmt2->bind_param("iiisdd", $order_id, $product_id, $quantity, $unit_type, $unit_size, $price_per_unit);
$stmt2->execute();



// 6) Redirect to a thank-you page
header('Location: thank_you.php?order_id=' . $order_id);
exit;

?>
