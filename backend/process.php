<?php
include('backend/configure.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the uploaded file and other form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $personality = $_POST['personality'];

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert into the database with status NULL (pending)
    $stmt = $pdo->prepare("INSERT INTO girlfriends (name, age, location, price, personality, image_url, status) VALUES (?, ?, ?, ?, ?, ?, NULL)");
    $stmt->execute([$name, $age, $location, $price, $personality, $target_file]);

    // Redirect to a success page or back to the form
    header("Location: index.php");
    exit;
}
?>
