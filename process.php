<?php
require_once 'backend/configure.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get details from the form
    $name = $_POST['name'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $personality = $_POST['personality'];
    $description = $_POST['description'];
    $rules = $_POST['rules'];

    if (isset($_FILES["image"])) {
        // Handle image upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $image_url = "uploads/" . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            error_log("File upload failed: " . $_FILES["image"]["error"]);
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert into the database with status "pending"
                $stmt = $pdo->prepare("INSERT INTO girlfriends (name, age, location, price, personality, description, rules, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->execute([$name, $age, $location, $price, $personality, $description, $rules, $image_url]);

                // Redirect to a success page or back to the form
                header("Location: index.php");
                exit;
            } else {
                echo "Sorry, there was an error uploading your file.";
                error_log("File upload failed: " . $_FILES["image"]["error"]);
            }
        }
    } else {
        echo "No file was uploaded.";
        error_log("No file was uploaded.");
    }
}
?>