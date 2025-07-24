<?php
session_start();
require 'inc/connect.php';
include 'inc/header.php';
?>

<div class="review-container">
    <h1>⭐Customer Reviews</h1>

    <div class="reviews">
        <?php
        $query = "SELECT r.rating, r.comments, c.first_name
                  FROM reviews r
                  JOIN customers c ON r.customer_id = c.customer_id
                  WHERE r.is_visible = 1
                  ORDER BY r.created_at DESC
                  LIMIT 3";
        $result = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $stars = str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']);
            echo "<div class='review-card'>
                    <div class='reviewer'>" . htmlspecialchars($row['first_name']) . "</div>
                    <div class='stars'>$stars</div>
                    <p class='review-text'>\"" . htmlspecialchars($row['comments']) . "\"</p>
                  </div>";
        }
        ?>
    </div>

    <div class="review-form">
        <h2>⭐Leave a Review</h2>

        <?php
        if (isset($_GET['flag']) && $_GET['flag'] === 'submitted') {
            echo "<p class='success'>Thank you! Your review was submitted.</p>";
        } elseif (isset($_GET['flag']) && $_GET['flag'] === 'invalid') {
            echo "<p class='error'>Invalid review input. Please try again.</p>";
        }
        ?>

        <?php if (isset($_SESSION['customer_id'])): ?>
        <form action="submit_review.php" method="post">
            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" step="1" placeholder="5" required>

            <label for="review">Your Review:</label>
            <textarea id="review" name="review" rows="4" placeholder="Share your experience" required></textarea>

            <button type="submit" class="heading-button">Submit Review</button>
        </form>
        <?php else: ?>
            <p>Please <a href="login.php">log in</a> to leave a review.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
</body>
</html>
