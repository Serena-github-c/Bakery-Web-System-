<?php
session_start();
require_once '../inc/connect.php';
// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    
    // Prepare a delete statement
    $sql = "DELETE FROM products WHERE product_id = ?";
    
    if($stmt = mysqli_prepare($connection, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: products.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($connection);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: orders.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Order</title>
  <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
  <div class="admin-container">
                        <h2>Delete Product</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this product?</p>
                            <p>
                                
                                <input type="submit" value="Yes" class='status-btn status-yes'>
                                <a href="orders.php" class='status-btn status-no'>No</a>
                            </p>
                    </form>
</div>
</body>
</html>