<?php
session_start();
require_once '../inc/connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php?admin=1');
    exit;
}

$product_id = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id    = (int) $_POST['product_id'];
    $name          = trim($_POST['name']);
    $price         = (float) $_POST['price_per_unit'];
    $description   = trim($_POST['description']);
    $imagePath     = $_POST['existing_image']; // default to existing image

    // Check if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../images/";
        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = "images/" . $imageName;
            }
        }
    }

    $sql = "UPDATE products SET name = ?, price_per_unit = ?, description = ?, image_url = ? WHERE product_id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "sdssi", $name, $price, $description, $imagePath, $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: products.php");
    exit;
}

if ($product_id) {
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$row = mysqli_fetch_assoc($result)) {
        header('Location: products.php');
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
    <div class="admin-container">
        <h2>Edit Product #<?php echo htmlspecialchars($product_id); ?></h2>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($row['image_url']); ?>">

            <label>Product Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required> <br><br>

            <label>Price Per Unit:</label>
            <input type="number" name="price_per_unit" step="0.01" value="<?php echo htmlspecialchars($row['price_per_unit']); ?>" required> <br><br>

            <label>Description:</label>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea> <br><br>

            <label>Current Image:</label><br>
            <img src="../<?php echo $row['image_url']; ?>" alt="Product Image" width="100"><br><br>

            <label>Upload New Image </label><br>
            <input type="file" name="image" accept="image/*">

            <br><br>
            <button type="submit" class="status-btn status-yes">Save Changes</button>
            <a href="products.php" class="status-btn status-no">Cancel</a>
        </form>
    </div>
</body>
</html>
