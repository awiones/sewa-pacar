<?php
include('backend/configure.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$name = urldecode($_GET['id']); // Get the name from the URL
$stmt = $pdo->prepare('SELECT * FROM girlfriends WHERE name = ?');
$stmt->execute([$name]);
$girlfriend = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$girlfriend) {
    echo "Girlfriend not found.";
    exit;
}

// Pagination settings
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch the latest reviews for the girlfriend with pagination
$totalReviewsStmt = $pdo->prepare('SELECT COUNT(*) FROM reviews WHERE girlfriend_name = ?');
$totalReviewsStmt->execute([$girlfriend['name']]);
$totalReviews = $totalReviewsStmt->fetchColumn();
$totalPages = ceil($totalReviews / $limit);

$reviewsStmt = $pdo->prepare('SELECT * FROM reviews WHERE girlfriend_name = ? ORDER BY created_at DESC LIMIT ? OFFSET ?');
$reviewsStmt->bindValue(1, $girlfriend['name']);
$reviewsStmt->bindValue(2, $limit, PDO::PARAM_INT);
$reviewsStmt->bindValue(3, $offset, PDO::PARAM_INT);
$reviewsStmt->execute();
$reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($girlfriend['name']) ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Additional styles for layout */
        .profile-box {
            display: flex; /* Align items in a row */
            align-items: flex-start; /* Align items at the top */
            margin-bottom: 20px; /* Space between sections */
        }
        .profile-image {
            width: 450px;
            height: 450px;
            object-fit: cover; /* Maintain aspect ratio */
            border-radius: 10px; /* Rounded corners */
        }
        .details {
            margin-left: 20px; /* Space between image and text */
            flex-grow: 1; /* Allow details to take the remaining space */
        }
        .rent-box {
            border: 1px solid #ccc; /* Light border */
            padding: 15px; /* Padding inside box */
            border-radius: 10px; /* Rounded corners */
            margin-top: 10px; /* Space between boxes */
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Center vertically */
            justify-content: space-between; /* Space out elements */
        }
        .info-box {
            border: 1px solid #ccc; /* Light border */
            padding: 15px; /* Padding inside boxes */
            border-radius: 10px; /* Rounded corners */
            margin-top: 10px; /* Space between boxes */
        }
        .stars {
        color: #ffcc00; /* Gold color for stars */
    }
    /* New styles for the reviews section */
    .reviews-section {
        margin-top: 30px; /* Space above the reviews section */
        background-color: #fff; /* White background for reviews section */
        padding: 20px; /* Padding for reviews section */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }
    .review {
        border: 1px solid #e0e0e0; /* Light border for each review */
        padding: 15px; /* Padding inside each review */
        border-radius: 10px; /* Rounded corners for reviews */
        margin-top: 15px; /* Space between reviews */
        background-color: #fafafa; /* Slightly darker background */
        transition: box-shadow 0.3s ease; /* Smooth shadow transition */
    }
    .review:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow on hover */
    }
    .pagination {
        justify-content: center; /* Center the pagination links */
        margin-top: 15px; /* Space above pagination */
    }
    .pagination .page-item {
        margin: 0 5px; /* Space between pagination items */
    }
    .pagination .page-link {
        border-radius: 50px; /* Rounded pagination buttons */
        background-color: #007bff; /* Primary color for pagination */
        color: #fff; /* White text */
        transition: background-color 0.3s; /* Transition for hover */
    }
    .pagination .page-link:hover {
        background-color: #0056b3; /* Darker shade on hover */
    }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand ms-3" href="#">SewaPacar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
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
    <div class="profile-box">
        <img src="<?= htmlspecialchars($girlfriend['image_url']) ?>" alt="<?= htmlspecialchars($girlfriend['name']) ?>" class="profile-image">
        <div class="details">
            <h2 class="profile-title"><strong><?= htmlspecialchars($girlfriend['name']) ?></strong></h2>

            <div class="stars">
                <?php
                // Calculate average rating
                $rating_count = $girlfriend['rating_count'];
                $rating_total = $girlfriend['rating_total'];
                $average_rating = $rating_count > 0 ? round($rating_total / $rating_count) : 0;

                for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?= $i <= $average_rating ? 'text-warning' : 'text-muted' ?>"></i>
                <?php endfor; ?>

                <span>(<?= $rating_count ?> people rated)</span>
            </div>

            <p class="profile-info"><strong>Location:</strong> <?= htmlspecialchars($girlfriend['location']) ?></p>
            <p class="profile-info"><strong>Price per day:</strong> <span id="price-display">$<?= htmlspecialchars($girlfriend['price']) ?></span></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($girlfriend['description']) ?></p>

            <div class="rent-box">
                <label for="quantity">Quantity of days to rent:</label>
                <div class="quantity-control">
                    <button type="button" class="btn btn-secondary" onclick="changeQuantity(-1)">-</button>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" class="form-control quantity-input" onchange="updatePrice()">
                    <button type="button" class="btn btn-secondary" onclick="changeQuantity(1)">+</button>
                </div>
                <p id="total-price">Total Price: $<?= htmlspecialchars($girlfriend['price']) ?></p>
                <a href="order.php?id=<?= urlencode($girlfriend['name']) ?>" class="btn btn-primary">Rent Her</a>
            </div>
        </div>
    </div>

    <div class="info-box">
        <p><strong>Personality:</strong> <?= htmlspecialchars($girlfriend['personality']) ?></p>
        <p><strong>Rules:</strong><br>
            <?= nl2br(htmlspecialchars($girlfriend['rules'])) ?>
        </p>
    </div>

    <div class="reviews-section">
        <h3>Latest Reviews:</h3>
        <div class="info-box"> <!-- Keep the info-box for consistency -->
            <?php
            if ($reviews) {
                foreach ($reviews as $review) {
                    echo '<div class="review">'; // Use the review class for styling
                    echo '<p><strong>' . htmlspecialchars($review['reviewer_name']) . ':</strong></p>';
                    echo '<p>' . htmlspecialchars($review['review_text']) . '</p>';
                    echo '<p><strong>Rating:</strong> ' . str_repeat('⭐', $review['rating']) . '</p>';
                    echo '<p><em>Reviewed on: ' . htmlspecialchars($review['created_at']) . '</em></p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No reviews yet.</p>';
            }
            ?>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?id=<?= urlencode($girlfriend['name']) ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?id=<?= urlencode($girlfriend['name']) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?id=<?= urlencode($girlfriend['name']) ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <?php include 'bahan/footer.php'; ?>

    <script>
        function changeQuantity(change) {
            var quantityInput = document.getElementById('quantity');
            var currentQuantity = parseInt(quantityInput.value);
            var newQuantity = currentQuantity + change;

            if (newQuantity >= 1) { // Ensure quantity is not less than 1
                quantityInput.value = newQuantity;
                updatePrice(); // Update price if necessary
            }
        }

        function updatePrice() {
            var quantity = parseInt(document.getElementById('quantity').value);
            var pricePerDay = <?= htmlspecialchars($girlfriend['price']) ?>; // Price fetched from the database
            var totalPrice = pricePerDay * quantity;

            document.getElementById('total-price').innerText = 'Total Price: $' + totalPrice.toFixed(2); // Update total price display
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
