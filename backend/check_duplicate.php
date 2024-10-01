<?php
require_once 'backend/configure.php'; 

if (isset($_GET['username'])) {
    $username = trim($_GET['username']);
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    echo json_encode(['exists' => $count > 0]);
}
?>
