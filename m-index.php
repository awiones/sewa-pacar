<?php
include('backend/configure.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SewaPacar - Rent a Girlfriend (Mobile)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mstyles.css">
    <script>
        if (window.innerWidth > 700) {
            window.location.href = "index.php";
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-light sticky-top d-flex justify-content-between shadow-sm p-3 mb-5 bg-white rounded">
        <a class="navbar-brand ms-3" href="#">SewaPacar</a>
        <div class="d-flex align-items-center">
            <a class="nav-link" href="become_gf.php">Register as GF</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="sub-bar container mt-3">
        <div class="row align-items-center">
            <div class="col-8">
                <div class="btn-group" role="group" aria-label="Filter Categories">
                    <button type="button" class="btn btn-secondary">All</button>
                    <button type="button" class="btn btn-secondary">Hot Girlfriends</button>
                    <button type="button" class="btn btn-secondary">New Girlfriends</button>
                </div>
            </div>
            <div class="col-4">
                <form action="search.php" method="GET">
                    <input type="text" name="query" class="form-control search-bar" placeholder="Search...">
                </form>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Available Girlfriends for Rent</h1>
                <div class="row card-wrapper"> <!-- Add the .row class here -->
                    <?php
                    $stmt = $pdo->query('SELECT * FROM girlfriends');
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="col-md-4 mb-4"> <!-- 3 cards per row on medium and larger screens -->
                            <div class="card h-100"> <!-- h-100 makes the cards have the same height -->
                                <a href="lookup.php?id=<?= urlencode($row['name']) ?>">
                                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="card-img-top">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                    <p class="card-text">Age: <?= $row['age'] ?></p>
                                    <p class="card-text">Location: <?= htmlspecialchars($row['location']) ?></p>
                                    <p class="card-text">Personality: <?= htmlspecialchars($row['personality']) ?></p>
                                    <p class="card-text">Price per day: $<?= $row['price'] ?></p>

                                    <div class="rating mb-3">
                                        <?php
                                        $rating = $row['rating']; 
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<i class="fas fa-star"></i>'; 
                                            } elseif ($i - 0.5 == $rating) {
                                                echo '<i class="fas fa-star-half-alt"></i>'; 
                                            } else {
                                                echo '<i class="far fa-star"></i>'; 
                                            }
                                        }
                                        ?>
                                    </div>

                                    <a href="order.php?id=<?= urlencode($row['name']) ?>" class="btn btn-primary">Order Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>


    <?php include 'bahan/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
