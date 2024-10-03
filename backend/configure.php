<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session
}

$host = 'sql12.freesqldatabase.com';
$port = 3306;
$db = 'sql12734623';
$user = 'sql12734623';
$pass = 'bmQJ2dqEc9';

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
