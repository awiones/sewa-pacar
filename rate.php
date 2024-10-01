<?php
include('backend/configure.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $girlfriend_id = $_POST['girlfriend_id'];
    $rating = intval($_POST['rating']);

    // Update the girlfriend's rating total and count
    $stmt = $pdo->prepare("UPDATE girlfriends SET rating_total = rating_total + ?, rating_count = rating_count + 1 WHERE id = ?");
    $stmt->execute([$rating, $girlfriend_id]);

    // Redirect back to the profile page
    header("Location: lookup.php?id=" . urlencode($_GET['id']));
    exit;
}
?>
