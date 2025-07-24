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
    if ($flag === 'email_exists') {
        echo "<p class='error'>An account with this email already exists. Please log in.</p>";
    
    } elseif ($flag === 'db_error') {
        echo "<p class='error'>Something went wrong. Please try again later.</p>";
        }
}
?>


<div class="login-container">

<!-- Name in the input field should be the same as the column name in the database-->
    <h2>Customer Sign up</h2>
    <fieldset>
    <legend>👤 Your Details</legend>

  <form method="post" action="signup_action.php">
    <div class="form-group">
      <label class="left" id="name-label" for="first_name">First Name:</label>
    <input type="text" name="first_name" class="right" id="name" placeholder="Enter your first name" required>
    </div>

<div class="form-group">
      <label class="left" id="name-label" for="last_name">Last Name:</label>
    <input type="text" name="last_name" class="right" id="name" placeholder="Enter your last name" required>
    </div>
        

    <div class="form-group">
      <label class="left" id="email-label" for="email">Email:</label>
    <input type="email" name="email" class="right" placeholder="you@example.com" id="email" required    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.com$""
>
    </div>

    <div class="form-group">
      <label  class="left" id="number-label" for="phone">Phone number:</label>
    <input type="text" name="phone" placeholder="e.g. +961 111 000" class="right" id="number" required  pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com)$">
  </div>

   <div class="form-group">

      <label class="left" id="name-label" for="password">Password:</label>
    <input type="text" name="password" class="right" id="name" placeholder="At least 8 digits" required pattern=".{8,}">
    </div>

    <button type="submit" class="heading-button">Sign up</button>
  </form>

  <p>
    Already have an account?
    <a href="login.php">Log in here</a>
  </p>
</div>
  </fieldset>

  
<?php
require 'inc/footer.php';
?>