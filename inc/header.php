<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Sweet Delights Bakery</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div class="header-content">
            <h1>🍰Sweet Delights Bakery</h1>
            <ul class="nav-links">
                <li><a href="index.php#products">Products</a></li>
                <li><a href="index.php#about-us">About Us</a></li>
                <li><a href="index.php#contact-us">Contact</a></li>

                <?php if(isset($_SESSION['email'])): ?>
                  <li class="nav-user">
                  <img src="images/person_icon.png" alt="User" class="user-icon">
                  <span><?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') ?></span>
                  <a href="logout.php">Logout</a>
                </li>
              <?php else: ?>
                <li class="nav-user">
                  <a href="login.php">
                    <img src="images/person_icon.png" alt="Login" class="user-icon">
                    <span>Login</span>
                  </a>
                </li>
              <?php endif; ?>
              

            </ul>
          </div>
        </header>
                  



