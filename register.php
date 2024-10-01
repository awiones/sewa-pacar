<?php
include('backend/configure.php');

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "Username already registered!";
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered!";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert the user into the database
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $hashedPassword])) {
                    $_SESSION['user'] = $email; // Store email in session
                    header("Location: index.php"); // Redirect to index page
                    exit();
                } else {
                    $error = "Registration failed! Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SewaPacar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background-color: #f8e2e8; /* Light pink background */
            color: #3d2b4e; /* Darker text color for contrast */
            font-family: 'Arial', sans-serif; /* Modern font */
        }
        .container {
            background-color: #ffffff; /* White background for the form */
            border-radius: 10px; /* Rounded corners */
            padding: 40px; /* Increased padding */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Enhanced shadow */
            max-width: 400px; /* Set a maximum width for the form */
            margin: auto; /* Center align the container */
            margin-top: 100px; /* Space from the top */
        }
        h2 {
            color: #d5006d; /* Pink header color */
            font-weight: bold; /* Bold font for emphasis */
        }
        .btn-primary {
            background-color: #d5006d; /* Primary button pink */
            border: none;
            border-radius: 20px; /* Rounded button corners */
            padding: 12px; /* Button padding */
            font-weight: bold; /* Bold button text */
        }
        .btn-primary:hover {
            background-color: #b3004d; /* Darker pink on hover */
            transition: background-color 0.3s ease; /* Smooth transition */
        }
        .alert {
            margin-bottom: 20px;
        }
        .text-center a {
            color: #d5006d; /* Pink link color */
            font-weight: bold; /* Bold link text */
        }
        .text-center a:hover {
            text-decoration: underline;
        }
        .form-control {
            border-radius: 20px; /* Rounded input corners */
            border: 1px solid #d5006d; /* Pink border */
        }
        .form-control:focus {
            border-color: #b3004d; /* Darker border on focus */
            box-shadow: 0 0 5px rgba(211, 0, 109, 0.5); /* Soft glow effect */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Register</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div id="username-error" class="text-danger" style="display: none;"></div> <!-- Error message -->
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            <div class="text-center"> 
            <button type="submit" class="btn btn-primary">Register</button>
        </div>

        <div class="text-center mt-3">
            <a href="forgot_password.php">Forgot Password?</a><br>
            <span>Already have an account? </span><a href="login.php">Login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#username').on('blur', function() {
                var username = $(this).val();
                $('#username-error').hide(); // Hide error message before checking
                if (username) {
                    $.get('check_duplicate.php', { username: username }, function(data) {
                        var result = JSON.parse(data);
                        if (result.exists) {
                            $('#username-error').text('Username already taken!').show(); // Show error message
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
