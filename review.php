<?php
include('backend/configure.php');

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if girlfriend_name is set in the GET request
if (!isset($_GET['name'])) {
    echo "Error: No girlfriend specified.";
    exit; // Stop execution if girlfriend_name is not provided
}

$girlfriend_name = urldecode($_GET['name']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Insert review into the database
    $stmt = $pdo->prepare('INSERT INTO reviews (girlfriend_name, rating, review_text, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$girlfriend_name, $rating, $review_text]);
    
    // Update the girlfriend's rating count and total rating
    $update_stmt = $pdo->prepare('UPDATE girlfriends SET rating_count = rating_count + 1, rating_total = rating_total + ? WHERE name = ?');
    $update_stmt->execute([$rating, $girlfriend_name]);

    header("Location: lookup.php?id=" . urlencode($girlfriend_name)); // Redirect back to the girlfriend's profile
    exit;
}

// Fetch all reviews for the girlfriend (if any)
$reviews_stmt = $pdo->prepare('SELECT * FROM reviews WHERE girlfriend_name = ? ORDER BY created_at DESC');
$reviews_stmt->execute([$girlfriend_name]);
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review for <?= htmlspecialchars($girlfriend_name) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand ms-3" href="#">SewaPacar</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="become_gf.php">Register as GF</a>
            </li>
            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Leave a Review for <?= htmlspecialchars($girlfriend_name) ?></h2>
    <form method="POST">
        <input type="hidden" name="girlfriend_name" value="<?= htmlspecialchars($girlfriend_name) ?>">
        
        <div class="mb-3">
            <label for="rating" class="form-label">Rating:</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Select a rating</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="review_text" class="form-label">Your Review:</label>
            <textarea name="review_text" id="review_text" rows="4" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>

    <h3 class="mt-4">Latest Reviews</h3>
    <ul class="list-group">
        <?php foreach ($reviews as $review): ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($review['rating']) ?> Stars</strong><br>
                <?= nl2br(htmlspecialchars($review['review_text'])) ?><br>
                <small class="text-muted">Posted on <?= htmlspecialchars($review['created_at']) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php include 'bahan/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
