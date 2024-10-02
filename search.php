<?php
include('backend/configure.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$query = isset($_GET['query']) ? htmlspecialchars(trim($_GET['query'])) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Rent a Girlfriend</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
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
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="become_gf.php">Register as GF</a>
                </li>
                <?php if (isset($_SESSION['user_email'])): ?>
                    <?php if (isAdmin($_SESSION['user_email'])): ?>
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

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h1>Search Results for '<?= $query ?>'</h1>
                <div class="card-wrapper">
                    <?php
                    if (!empty($query)) {
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM girlfriends WHERE name LIKE :query");
                            $stmt->execute(['query' => '%' . $query . '%']);
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($results) {
                                foreach ($results as $row) {
                                    $name = htmlspecialchars($row['name']);
                                    $age = htmlspecialchars($row['age']);
                                    $location = htmlspecialchars($row['location']);
                                    $personality = htmlspecialchars($row['personality']);
                                    $price = htmlspecialchars($row['price']);
                                    $image_url = htmlspecialchars($row['image_url']);
                                    ?>
                                    <div class="card mb-3">
                                        <a href="lookup.php?id=<?= urlencode($name) ?>">
                                            <img src="<?= $image_url ?>" class="card-img-top" alt="<?= $name ?>">
                                        </a>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $name ?></h5>
                                            <p class="card-text">Age: <?= $age ?></p>
                                            <p class="card-text">Location: <?= $location ?></p>
                                            <p class="card-text">Personality: <?= $personality ?></p>
                                            <p class="card-text">Price per day: $<?= $price ?></p>
                                            <a href="order.php?id=<?= urlencode($name) ?>" class="btn btn-primary">Order Now</a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<h3>No results found for '" . $query . "'.</h3>";
                            }
                        } catch (PDOException $e) {
                            echo "<p>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
                        }
                    } else {
                        echo "<h3>Please enter a search query.</h3>";
                    }
                    ?>
                </div>
            </div>

            <div class="col-md-4 d-none d-md-block">
                <div class="sidebar">
                    <h2>Hot Girlfriends 🔥</h2>
                    <ul class="list-group">
                        <?php
                        $hot_girlfriends = $pdo->query("SELECT * FROM girlfriends ORDER BY RAND() LIMIT 3");
                        while ($gf = $hot_girlfriends->fetch(PDO::FETCH_ASSOC)): ?>
                            <li class="list-group-item"><?= htmlspecialchars($gf['name']) ?> - $<?= $gf['price'] ?> / day</li>
                        <?php endwhile; ?>
                    </ul>

                    <h2>New Arrivals</h2>
                    <ul class="list-group">
                        <?php
                        $new_arrivals = $pdo->query("SELECT * FROM girlfriends ORDER BY id DESC LIMIT 3");
                        while ($new = $new_arrivals->fetch(PDO::FETCH_ASSOC)): ?>
                            <li class="list-group-item"><?= htmlspecialchars($new['name']) ?> - $<?= $new['price'] ?> / day</li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
