<?php
include('backend/configure.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit;
}

// Ensure user data is properly set
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $girlfriend_name = urldecode($_GET['id']);
    
    // Fetch girlfriend details
    $stmt = $pdo->prepare('SELECT * FROM girlfriends WHERE name = ?');
    $stmt->execute([$girlfriend_name]);
    $girlfriend = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$girlfriend) {
        echo "Girlfriend not found.";
        exit;
    }
    $direct = htmlspecialchars($girlfriend['direct']);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $girlfriend_id = $_POST['girlfriend_id'];
    $quantity = intval($_POST['quantity']);
    $total_price = floatval($_POST['total_price']);
    
    // Check if user ID is set
    if ($user_id === null) {
        echo "User ID is not set. Please log in again.";
        exit;
    }
    
    // Validate quantity
    if ($quantity <= 0) {
        $error_message = "Quantity must be a positive integer.";
    } else {
        // Insert order into the database
        $stmt = $pdo->prepare("INSERT INTO orders (girlfriend_id, user_id, quantity, total_price, comments, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$girlfriend_id, $user_id, $quantity, $total_price, $_POST['comments'] ?? '']);

        // Redirect to a confirmation page or display a success message
        header("Location: order_confirmation.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order <?= htmlspecialchars($girlfriend['name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .alert {
            margin-top: 20px;
        }
        .comments-area {
            border-radius: 10px;
            overflow-y: auto;
            height: 100px;
            resize: none;
            padding: 10px;
            border: 1px solid #ced4da;
            width: 100%;
        }
        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
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
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Order <?= htmlspecialchars($girlfriend['name']) ?></h2>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="order.php">
        <input type="hidden" name="girlfriend_id" value="<?= $girlfriend['id'] ?>">
        
        <div class="mb-4">
            <h5>Order Details</h5>
            <div class="mb-3">
                <label class="form-label">Girlfriend Name:</label>
                <p class="form-control-static"><?= htmlspecialchars($girlfriend['name']) ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Price per Day:</label>
                <p class="form-control-static"><?= htmlspecialchars($girlfriend['price']) ?></p>
            </div>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity of Days to Rent:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" class="form-control" onchange="updateTotalPrice()">
        </div>

        <div class="mb-3">
            <label for="total_price" class="form-label">Total Price:</label>
            <input type="text" id="total_price" name="total_price" value="<?= htmlspecialchars($girlfriend['price']) ?>" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="comments" class="form-label">Comments or Special Requests:</label>
            <textarea id="comments" name="comments" class="comments-area" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-center">
            <a href="<?= $direct ?>" class="btn btn-primary">Confirm Order</a>
        </div>
    </form>
</div>

<?php include 'bahan/footer.php'; ?>

<script>
    function updateTotalPrice() {
        const quantity = document.getElementById('quantity').value;
        const pricePerDay = <?= htmlspecialchars($girlfriend['price']) ?>;
        const totalPrice = pricePerDay * quantity;

        document.getElementById('total_price').value = totalPrice.toFixed(2);
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
