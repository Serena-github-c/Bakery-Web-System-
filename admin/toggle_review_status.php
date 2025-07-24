<?php
session_start();
require_once '../inc/connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php?admin=1");
    exit;
}

$review_id = intval($_POST['review_id']);
$field = $_POST['field'];

if (!in_array($field, ['is_visible'])) {
    die("Invalid field.");
}

// Get current status
$res = mysqli_query($connection, "SELECT $field FROM reviews WHERE review_id = $review_id");
$row = mysqli_fetch_assoc($res);
$current = $row[$field];

// Toggle value
$new_value = $current ? 0 : 1;

// Update
$stmt = $connection->prepare("UPDATE reviews SET $field = ? WHERE review_id = ?");
$stmt->bind_param("ii", $new_value, $review_id);
$stmt->execute();

header("Location: reviews.php");
exit;
