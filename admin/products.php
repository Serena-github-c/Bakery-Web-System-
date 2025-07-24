<?php
session_start();
require_once '../inc/connect.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Products</title>
  <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
  <div class="admin-container">
  <div class="top-links">
    <a href="dashboard.php">← Back to Dashboard</a>
    <a href="add_product.php"> Add New Product</a>
</div>
  <h2>🧁 All Products</h2>


<?php
$sql = "
SELECT * FROM products
";
if($result = mysqli_query($connection, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo '<table >';
            echo "<thead>";
                echo "<tr>";
                    echo "<th>#</th>";
                    echo "<th>Name</th>";
                    echo "<th>Price per unit</th>";
                    echo "<th>Description</th>";
                    echo "<th>Image</th>";
                    echo "<th>Actions</th>";
                    
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($result)){                
                echo "<tr>";
                echo "<td>" . $row['product_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['price_per_unit'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
echo "<td><img src='../" . $row['image_url'] . "' alt='Product Image' style='width: 80px; height: auto; border-radius: 6px;'></td>";


                // ACTION BUTTONS

                echo "<td class='action-links'>";
                    echo '<a href="edit_product.php?id='. $row['product_id'] .'"> Edit</a>';
                    echo '<a href="delete_product.php?id='. $row['product_id'] .'"> Delete</a>';
                echo "</td>";

            echo "</tr>";
            }
            echo "</tbody>";                            
        echo "</table>";
        // Free result set
        mysqli_free_result($result);
    } else{
        echo '<em>No records were found.</em>';
    }
} else{
    echo "Oops! Something went wrong. Please try again later.";
}

?>
</div>
</body>
</html>