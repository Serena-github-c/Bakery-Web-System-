<?php
session_start();
// If not logged in, send them to login page first:
if (!isset($_SESSION['customer_id'])) {
    // Save intended return URL so after login they come back here:
    $_SESSION['return_to'] = $_SERVER['REQUEST_URI']; 
    header('Location: login.php');
    exit;
}

require 'inc/connect.php';  // your mysqli $connection
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'inc/header.php'; ?>

<form id="order-form" method="post" action="submit_order.php">
  <p align="center">
    Welcome to our order page. We will get back to you within 24 hours. Thank you!
  </p><br>

  <fieldset>
    <legend>🍰 Order Details</legend>

    <!-- 1) Product -->
    <div class="field-row">
      <label class="left" for="product_id">Please select the item:</label>
      <select class="right" id="product_id" name="product_id" required>
        <option value="" disabled selected>Select a product</option>
        <?php
        $res = mysqli_query($connection, "SELECT product_id, name FROM products");
        while ($row = mysqli_fetch_assoc($res)) {
          echo "<option value=\"{$row['product_id']}\">{$row['name']}</option>";
        }
        ?>
      </select>
    </div>

    <!-- 2) Unit type -->
    <div class="field-row">
      <label class="left" for="unit_type">Unit Type:</label>
      <select class="right" id="unit_type" name="unit_type" required>
        <option value="" disabled selected>Select unit type</option>
        <option value="serving">Serving (cakes)</option>
        <option value="dozen">Dozen (eclairs, donuts, cookies)</option>
      </select>
    </div>

    <!-- 3) Unit size -->
    <div class="field-row">
      <label class="left" for="unit_size">Unit Size:</label>
      <input type="number" class="right" id="unit_size" name="unit_size"
             min="1" step=0.5 max='100' placeholder="e.g. 8 servings or 12 pieces" required>
    </div>

    <!-- 4) Quantity -->
    <div class="field-row">
      <label class="left" for="quantity">Quantity (boxes):</label>
      <input type="number" class="right" id="quantity" name="quantity"
             min="1" max='20'value="1" required>
    </div>

  </fieldset>

  <fieldset>
    <legend>📝 Preferences & Pickup</legend>

    <!-- 5) Notes -->
    <div class="field-row">
      <label class="left" for="notes">Flavor / Color Preferences:</label>
      <textarea class="right" id="notes" name="notes"
                rows="4" placeholder="e.g. Vanilla flavor, pink frosting..." ></textarea>
    </div>

    <!-- 6) Pickup time -->
<div class="field-row">
  <label class="left" for="pickup_time">Pickup Date &amp; Time:</label>
  <input type="datetime-local" class="right" id="pickup_time" name="pickup_time" required 
         onfocus="this.min=new Date(Date.now()+86400000).toISOString().slice(0,16)">
</div>


  </fieldset>

  <div class="submit-section">
    <button class="heading-button" type="submit">Submit Order</button>
  </div>

    <p>We will contact you to confirm your order and your pickup date.</p>
    <p>Thank you for choosing Sweet Delights! 🎂</p>
</form>

<?php include 'inc/footer.php'; ?>
</body>
</html>
