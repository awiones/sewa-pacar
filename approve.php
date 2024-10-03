<?php
require_once 'configure.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update the status to "accepted"
    $stmt = $pdo->prepare("UPDATE girlfriends SET status = 'accepted' WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to the admin dashboard
    header("Location: dashboard-atmin.php");
    exit;
}
?>