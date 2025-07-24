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
    <title>Admin - Reviews</title>
  <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
  <div class="admin-container">
  <div class="top-links">
    <a href="dashboard.php">← Back to Dashboard</a>
</div>
  <h2>⭐ All Reviews</h2>


<?php
$sql = "
SELECT * FROM reviews
";


if($result = mysqli_query($connection, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo '<table >';
            echo "<thead>";
                echo "<tr>";
                    echo "<th>#</th>";
                    echo "<th>customer #</th>";
                    echo "<th>Rating</th>";
                    echo "<th>Comments</th>";
                    echo "<th>created_at</th>";
                    echo "<th>is_visible</th>";
                    
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($result)){  
                $review_id = $row['review_id'];
                $visible = $row['is_visible'] ? 'Yes' : 'No';
                $visibleClass = $row['is_visible'] ? 'status-yes' : 'status-no';


                echo "<tr>";
                echo "<td>" . $row['review_id'] . "</td>";
                echo "<td>" . $row['customer_id'] . "</td>";
                echo "<td>" . $row['rating'] . "</td>";
                echo "<td>" . $row['comments'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";

// Toggle button
                echo "<td>
                        <form method='post' action='toggle_review_status.php'>
                            <input type='hidden' name='review_id' value='{$review_id}'>
                            <input type='hidden' name='field' value='is_visible'>
                            <button type='submit' class='toggle-btn {$visibleClass}'>{$visible}</button>
                        </form>
                    </td>";
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