<?php
require 'inc/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT); // HASHED for extra security 

// Check if email already exists
    $check = $connection->prepare("SELECT * FROM customers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

  if ($check->num_rows > 0) {
        header("Location: signup.php?flag=email_exists");
        exit;
    }


    // Insert into the database using prepared statement
       $stmt = $connection->prepare("INSERT INTO customers (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $password);
    
    if ($stmt->execute()) {
        $_SESSION['customer_id'] = $stmt->insert_id;
        header("Location: order.php"); // or profile.php
    } else {
        header("Location: signup.php?flag=db_error");
    }
}
 
    
