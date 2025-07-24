<?php 
include 'inc/header.php'; 
require 'inc/connect.php' ;
?>

<!DOCTYPE html>
<html lang="en">

    <div class="container">
        <h1>🎁Our Delicious Offerings</h1>
        <div class="container__box">
            

<?php
    $query = "SELECT * FROM products";
    $result = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($result)) {
      $img = htmlspecialchars($row['image_url']);
      $name = htmlspecialchars($row['name']);
      $desc = htmlspecialchars($row['description']);
      $price = number_format($row['price_per_unit'], 2);
      echo "
        <div class='box'>
          <img src='$img' alt='$name'>
          <h3>$name</h3>
          <p>$desc</p>
          <p><strong>\$$price</strong></p>
          <a href='order.php' class='heading-button' target='_blank'>Order now</a>
        </div>
      ";
    }
    ?>

  </div>
</div>

<?php include 'inc/footer.php'; ?>
</body>
</html>        