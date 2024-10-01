<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session
}

$host = 'localhost';
$port = 3306;
$db = 'rentgf_db';
$user = '';
$pass = '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Function to check if the user is an admin
function isAdmin($email) {
    return $email === 'awiones@gmail.com'; // Change this email as needed
}

?>
