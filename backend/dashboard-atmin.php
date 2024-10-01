<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('configure.php'); 

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || !isAdmin($_SESSION['user_email'])) {
    header('Location: ../../index.php'); // Redirect if not logged in or not an admin
    exit;
}

// Fetch pending girlfriend applications
$stmt = $pdo->query("SELECT * FROM girlfriends WHERE status IS NULL");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch available girlfriends (accepted)
$stmt_available = $pdo->query("SELECT * FROM girlfriends WHERE status = 'accepted'");
$available_gfs = $stmt_available->fetchAll(PDO::FETCH_ASSOC);

// Handle acceptance or rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $stmt = $pdo->prepare("UPDATE girlfriends SET status = 'accepted' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE girlfriends SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM girlfriends WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Redirect to refresh the page
    header('Location: dashboard-atmin.php');
    exit();
}

// Handle editing
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt_edit = $pdo->prepare("SELECT * FROM girlfriends WHERE id = ?");
    $stmt_edit->execute([$edit_id]);
    $edit_gf = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}

// Handle update after editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $personality = $_POST['personality'];
    $image_url = $_POST['image_url'];

    $stmt = $pdo->prepare("UPDATE girlfriends SET name = ?, age = ?, location = ?, price = ?, personality = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$name, $age, $location, $price, $personality, $image_url, $id]);

    // Redirect to refresh the page
    header('Location: dashboard-atmin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SewaPacar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this girlfriend? This action cannot be undone.');
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand ms-3" href="#">SewaPacar Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Girlfriend Applications</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['name']); ?></td>
                        <td><?php echo htmlspecialchars($application['age']); ?></td>
                        <td><?php echo htmlspecialchars($application['location']); ?></td>
                        <td><?php echo htmlspecialchars($application['price']); ?></td>
                        <td><?php echo htmlspecialchars($application['description']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                                <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Available Girlfriends</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Personality</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($available_gfs as $gf): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($gf['name']); ?></td>
                        <td><?php echo htmlspecialchars($gf['age']); ?></td>
                        <td><?php echo htmlspecialchars($gf['location']); ?></td>
                        <td><?php echo htmlspecialchars($gf['price']); ?></td>
                        <td><?php echo htmlspecialchars($gf['personality']); ?></td>
                        <td><img src="<?= htmlspecialchars($gf['image_url']) ?>" alt="<?= htmlspecialchars($gf['name']) ?>" width="100"></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                <input type="hidden" name="id" value="<?php echo $gf['id']; ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-danger">Delete</button>
                                <a href="?edit_id=<?php echo $gf['id']; ?>" class="btn btn-warning">Edit</a>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (isset($edit_gf)): ?>
            <h3>Edit Girlfriend</h3>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $edit_gf['id']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_gf['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($edit_gf['age']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($edit_gf['location']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($edit_gf['price']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="personality" class="form-label">Personality</label>
                    <input type="text" name="personality" class="form-control" value="<?php echo htmlspecialchars($edit_gf['personality']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image URL</label>
                    <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($edit_gf['image_url']); ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include '../bahan/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/cookie.js"></script>
</body>
</html>
