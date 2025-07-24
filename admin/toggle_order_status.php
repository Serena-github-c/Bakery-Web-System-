<?php
session_start();
require_once '../inc/connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php?admin=1");
    exit;
}

$order_id = intval($_POST['order_id']);
$field = $_POST['field'];

if (!in_array($field, ['is_baked', 'is_picked_up'])) {
    die("Invalid field.");
}

// Get current status
$res = mysqli_query($connection, "SELECT $field FROM orders WHERE order_id = $order_id");
$row = mysqli_fetch_assoc($res);
$current = $row[$field];

// Toggle value
$new_value = $current ? 0 : 1;

// Update
$stmt = $connection->prepare("UPDATE orders SET $field = ? WHERE order_id = ?");
$stmt->bind_param("ii", $new_value, $order_id);
$stmt->execute();

header("Location: orders.php");
exit;
