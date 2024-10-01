<?php
session_start(); // Start the session
include('backend/configure.php');

$message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_email'] = $user['email']; // Set session variable for email
        $_SESSION['user_id'] = $user['id']; // Set session variable for user id

        // Check if the user is an admin
        if (isAdmin($user['email'])) {
            header('Location: ../backend/dashboard-atmin.php'); // Redirect to admin dashboard
        } else {
            header('Location: index.php'); // Redirect to regular user dashboard
        }
        exit;
    } else {
        $message = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SewaPacar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8e2e8; 
            color: #3d2b4e;
            font-family: 'Arial', sans-serif; 
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px; 
            padding: 40px; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); 
            max-width: 400px; 
            margin: auto; 
            margin-top: 100px;
        }
        h2 {
            color: #d5006d;
            font-weight: bold; 
        }
        .btn-primary {
            background-color: #d5006d;
            border: none;
            border-radius: 20px; 
            padding: 12px;
            font-weight: bold; 
        }
        .btn-primary:hover {
            background-color: #b3004d;
            transition: background-color 0.3s ease; 
        }
        .alert {
            margin-bottom: 20px;
        }
        .text-center a {
            color: #d5006d; 
            font-weight: bold; 
        }
        .text-center a:hover {
            text-decoration: underline;
        }
        .form-control {
            border-radius: 20px;
            border: 1px solid #d5006d;
        }
        .form-control:focus {
            border-color: #b3004d;
            box-shadow: 0 0 5px rgba(211, 0, 109, 0.5);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="mt-3 text-center">
            <a href="forgot_password.php">Forgot Password?</a> | 
            <a href="register.php">Register</a>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cookie.js"></script>
</body>
</html>
