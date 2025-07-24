<?php
session_start();
require_once '../inc/connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price_per_unit'];
    $description = $_POST['description'];
    
    // Handle image upload
    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../images/";
        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = "images/" . $imageName; 
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Insert into DB
    $sql = "INSERT INTO products (name, price_per_unit, description, image_url) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "sdss", $name, $price, $description, $imagePath);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: products.php'); // or wherever you list products
        exit;
    } else {
        echo "Error adding product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
      <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<h2>🍰 Add New Product</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label for="name">Product Name</label>
    <input type="text" name="name" required><br><br>

    <label for="price_per_unit">Price Per Unit</label>
    <input type="number" name="price_per_unit" step="0.01" required><br><br>

    <label for="description">Description</label>
    <textarea name="description" rows="4"></textarea><br><br>

    <label for="image">Product Image</label>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit" class="heading-button" >Add Product</button>
</form>

</body>
</html>
