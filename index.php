<?php
include('backend/configure.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$stmt = $pdo->query("SELECT * FROM girlfriends WHERE status = 'accepted'");

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'Android') !== false || strpos($user_agent, 'iPhone') !== false) {
    header('Location: m-index.php');
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent a Girlfriend | Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        window.onload = function() {
            if (window.innerWidth < 768) {
                window.location.href = 'm-index.php';
            }
        };
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand ms-3" href="index.php">SewaPacar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="become_gf.php">Register as GF</a>
                </li>
                <?php if (isset($_SESSION['user_email'])): ?>
                    <?php if (isAdmin($_SESSION['user_email'])): // Check if user is admin ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../backend/dashboard-atmin.php">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </li>
                <?php endif; ?>
            </ul>

            <form class="search-form ms-3 me-3" action="search.php" method="GET">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Search...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </nav>

    <div class="bd-example">
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
            <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
            <img src="img/banner1.jpg" class="d-block w-100" alt="Girlfriend 1">
            <div class="carousel-caption d-none d-md-block">
                <h5>Meet Your Perfect Date</h5>
                <p>In sewapacar.com you can find a fit perfect girlfriends</p>
            </div>
            </div>
            <div class="carousel-item">
            <img src="img/banner2.jpg" class="d-block w-100" alt="Girlfriend 2">
            <div class="carousel-caption d-none d-md-block">
                <h5>A Day of Adventure</h5>
                <p>The girlfriends enjoys exploring new places and exciting adventures in the city!</p>
            </div>
            </div>
            <div class="carousel-item">
            <img src="img/banner3.jpg" class="d-block w-100" alt="Girlfriend 3">
            <div class="carousel-caption d-none d-md-block">
                <h5>The Movie Lover</h5>
                <p>Spend with your lovers by watching classic films and enjoying good snacks.</p>
            </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Available Girlfriends for Rent</h1>
                <div class="card-wrapper">
                    <?php
                    $stmt = $pdo->query('SELECT * FROM girlfriends');
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="card">
                        <a href="lookup.php?id=<?= urlencode($row['name']) ?>">
                            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text">Age: <?= $row['age'] ?></p>
                            <p class="card-text">Location: <?= htmlspecialchars($row['location']) ?></p>
                            <p class="card-text">Personality: <?= htmlspecialchars($row['personality']) ?></p>
                            <p class="card-text">Price per day: $<?= $row['price'] ?></p>

                            <div class="rating">
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
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="col-md-4 d-none d-md-block"> 
                <div class="sidebar">
                    <h2>Hot Girlfriends 🔥</h2>
                    <ul class="list-group">
                        <?php
                        $hot_girlfriends = $pdo->query("SELECT * FROM girlfriends ORDER BY RAND() LIMIT 3");
                        while ($gf = $hot_girlfriends->fetch(PDO::FETCH_ASSOC)): ?>
                            <li class="list-group-item">
                                <?= htmlspecialchars($gf['name']) ?> - $<?= $gf['price'] ?> / day
                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <h2>New Arrivals</h2>
                    <ul class="list-group">
                        <?php
                        $new_arrivals = $pdo->query("SELECT * FROM girlfriends ORDER BY id DESC LIMIT 3");
                        while ($new = $new_arrivals->fetch(PDO::FETCH_ASSOC)): ?>
                            <li class="list-group-item">
                                <?= htmlspecialchars($new['name']) ?> - $<?= $new['price'] ?> / day
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php include 'baha n/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/cookie.js"></script>
</body>
</html>
