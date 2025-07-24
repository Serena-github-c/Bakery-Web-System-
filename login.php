<?php
session_start();

require 'inc/header.php';
require 'inc/connect.php';  
if (!$connection) {
    // redirect back here with a flag if DB fails
    header('Location: login.php?flag=db');
    exit;
}

// Check for error flags in URL
if (isset($_GET['flag'])) {
    $flag = $_GET['flag'];
    if ($flag == 'db'){
        echo '<p class="error">Database connection failed. Please try again later </p> ';
    }
    elseif ($flag == 'nouser') {
        echo '<p class="error"> Account does not exist. Please sign up first </p> ';
    }
    elseif ($flag == 'wrongpass') {
        echo '<p class="error"> Incorrect password .Please try again </p> ';
    }

}
?>

<div class="login-container">
  <h2>Customer Login</h2>
  <fieldset>
    <legend>👤 Your Details</legend>

  <form method="post" action="login_action.php">
    <div class="form-group">
      <label for="email">Email:</label><br>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="you@example.com"
        required
        pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com)$" >

    </div>

    <div class="form-group">
      <label for="password">Password:</label><br>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="At least 8 digits"
        pattern=".{8,}"
        required
      >
    </div>

    <button type="submit" class="heading-button">Login</button>

<!--for admin login: -->
  <label><input type="checkbox" name="is_admin"> Login as Admin</label>
</div>

  </form>

  <p>
    Don’t have an account?
    <a href="signup.php">Sign up here</a>
  </p>
  </fieldset>



<?php
require 'inc/footer.php';
?>
