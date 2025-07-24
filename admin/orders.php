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
    <title>Admin - Orders</title>
  <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
  <div class="admin-container">
  <div class="top-links">
    <a href="dashboard.php">← Back to Dashboard</a>
    <a href="create.php"> Add New Order</a>
</div>
  <h2>🧁 All Orders</h2>


<?php
$sql = "
SELECT
    o.order_id,
    CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
    o.created_at,
    p.name AS product_name,
    op.quantity,
    op.unit_type,
    op.unit_size,
    o.notes,
    o.pickup_time,
    -- total price per item row
    (op.quantity * op.unit_size * op.price_per_unit) AS item_total_price,
    o.is_baked,
    o.is_picked_up
FROM
    orders o
JOIN customers c ON o.customer_id = c.customer_id
JOIN order_products op ON o.order_id = op.order_id
JOIN products p ON op.product_id = p.product_id
ORDER BY
    o.pickup_time;

";
if($result = mysqli_query($connection, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo '<table >';
            echo "<thead>";
                echo "<tr>";
                    echo "<th>#</th>";
                    echo "<th>Name</th>";
                    echo "<th>created_at</th>";
                    echo "<th>Product</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Unit type</th>";
                    echo "<th>Unit Size</th>";
                    echo "<th>Notes</th>";
                    echo "<th>Pickup time</th>";
                    echo "<th>Total price</th>";
                    echo "<th>is_baked</th>";
                    echo "<th>is_picked_up</th>";
                    echo "<th>Actions</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($result)){
                
                $order_id = $row['order_id'];
                $baked = $row['is_baked'] ? 'Yes' : 'No';
                $bakedClass = $row['is_baked'] ? 'status-yes' : 'status-no';
                $picked = $row['is_picked_up'] ? 'Yes' : 'No';
                $pickedClass = $row['is_picked_up'] ? 'status-yes' : 'status-no';

                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['customer_name'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>" . $row['product_name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['unit_type'] . "</td>";
                echo "<td>" . $row['unit_size'] . "</td>";
                echo "<td>" . $row['notes'] . "</td>";
                echo "<td>" . $row['pickup_time'] . "</td>";
                echo "<td>" . $row['item_total_price'] . "</td>";

// Toggle buttons
                echo "<td>
                        <form method='post' action='toggle_order_status.php'>
                            <input type='hidden' name='order_id' value='{$order_id}'>
                            <input type='hidden' name='field' value='is_baked'>
                            <button type='submit' class='toggle-btn {$bakedClass}'>{$baked}</button>
                        </form>
                    </td>";

                echo "<td>
                        <form method='post' action='toggle_order_status.php'>
                            <input type='hidden' name='order_id' value='{$order_id}'>
                            <input type='hidden' name='field' value='is_picked_up'>
                            <button type='submit' class='status-btn {$pickedClass}'>{$picked}</button>
                        </form>
                    </td>";
                // ACTION BUTTONS

                echo "<td class='action-links'>";
                    echo '<a href="view.php?id='. $row['order_id'] .'"> View</a>';
                    echo '<a href="edit.php?id='. $row['order_id'] .'"> Edit</a>';
                    echo '<a href="delete.php?id='. $row['order_id'] .'"> Delete</a>';
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