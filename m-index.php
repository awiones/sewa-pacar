<?php
include('backend/configure.php');
session_start();
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
        // Redirect if screen width is greater than 700 pixels
        if (window.innerWidth > 700) {
            window.location.href = "index.php";
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-light d-flex justify-content-between">
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

    <div class="container">
        <div class="row my-3">
            <div class="col-12">
                <div class="sub-bar">
                    <div class="btn-group" role="group" aria-label="Girlfriend Categories">
                        <button type="button" class="btn btn-secondary">Hot Girlfriends</button>
                        <button type="button" class="btn btn-secondary">New Girlfriends</button>
                    </div>
                    <input type="text" class="form-control search-bar" placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="row card-wrapper">
            <?php
            $stmt = $pdo->query('SELECT * FROM girlfriends');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text">Age: <?= $row['age'] ?></p>
                            <p class="card-text">Location: <?= htmlspecialchars($row['location']) ?></p>
                            <p class="card-text">Price per day: $<?= $row['price'] ?></p>
                            <a href="order.php?id=<?= $row['id'] ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
