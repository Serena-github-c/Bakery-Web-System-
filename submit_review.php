<?php
session_start();
require 'inc/connect.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$rating = intval($_POST['rating']);
$comment = trim($_POST['review']);

if ($rating < 1 || $rating > 5 || empty($comment)) {
    header("Location: customer_reviews.php?flag=invalid");
    exit;
}

// Insert review into the db 
$stmt = $connection->prepare("INSERT INTO reviews (customer_id, rating, comments) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $customer_id, $rating, $comment);
$stmt->execute();

header("Location: customer_reviews.php?flag=submitted");
exit;


