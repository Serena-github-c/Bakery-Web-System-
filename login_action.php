<?php
    session_start();
    include("inc/connect.php");

    $email = trim($_POST['email']) ;
    $password = $_POST['password'] ;
    $is_admin_login = isset($_POST['is_admin']);  // checkbox logic

    //$query = "select * from table1 where email = '$email' && password= '$password' ";
    //$result = mysqli_query($connection, $query);

    //admin login
    if ($is_admin_login){
         $stmt = $connection->prepare("SELECT password FROM admin WHERE admin_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            header("Location: login.php?flag=nouser");
            exit;
        }

        $stmt->bind_result($db_password);
        $stmt->fetch();


        if ($password !== $db_password) {
            header("Location: login.php?flag=wrongpass");
            exit;
        }

        $_SESSION['is_admin'] = true;
        $_SESSION['admin_email'] = $email;
        header("Location: admin/dashboard.php");
        exit;
    }

    //NORMAL USER LOG IN
    // Check if email exists in the database
    $stmt = $connection->prepare("SELECT customer_id, password FROM customers WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 0){
        // No user found with this email in the database
        header("Location: login.php?flag=nouser");
        exit;
    }

    $stmt->bind_result($customer_id, $hashed_password);
    $stmt->fetch();

    // Verify the entered password with the hashed password
    if (!password_verify($password, $hashed_password)) {
        header("Location: login.php?flag=wrongpass");
        exit;
    }

    // If email and password are correct
    $_SESSION['customer_id'] = $customer_id;

    // redirect back to previous page
    $redirect = $_SESSION['return_to'] ?? 'order.php';
    unset($_SESSION['return_to']);

    header("Location: $redirect");
    exit;