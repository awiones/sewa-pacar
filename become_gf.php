<?php
include('backend/configure.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

$upload_success = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get details from the form
    $name = htmlspecialchars($_POST['name']);
    $age = (int)$_POST['age'];
    $location = htmlspecialchars($_POST['location']);
    $price = (float)$_POST['price'];
    $description = htmlspecialchars($_POST['description']);
    $personality = htmlspecialchars($_POST['personality']);
    $rules = htmlspecialchars($_POST['rules']);
    $imageData = $_POST['image'];

    // Handle the uploaded image data (base64)
    if (!empty($imageData)) {
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $image = base64_decode($imageData);
        $image_name = 'gf_' . time() . '.jpg';
        $image_path = 'images/' . $image_name;

        // Save the image to the server
        if (file_put_contents($image_path, $image)) {
            // Insert into the database
            $stmt = $pdo->prepare("INSERT INTO girlfriends (name, age, location, price, description, personality, rules, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $age, $location, $price, $description, $personality, $rules, $image_path]);

            $upload_success = true;
        } else {
            $upload_success = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Girlfriend - SewaPacar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="css/styles.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 50px;
        }
        .form-container {
            flex: 1;
            margin-right: 50px;
        }
        .drag-drop-area {
            width: 450px;
            height: 450px;
            border: 2px dashed #ddd;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #aaa;
            cursor: pointer;
            transition: border-color 0.3s ease-in-out;
        }
        .drag-drop-area:hover {
            border-color: #ff66a3;
        }
        .modal-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            height: 400px;
        }
        .cropper-container {
            width: 100%;
            height: 500px;
        }
        #croppedImage {
            display: none;
            max-width: 100%;
            height: 100%;
            margin-top: 10px;
        }
        .btn-submit {
            background-color: #ff66a3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background-color: #ff99c2;
        }
        .modal-dialog {
            max-width: 800px;
            width: 90%;
        }
        .modal-content {
            height: 600px;
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
        }
        .image-upload-container {
            position: relative;
            width: 450px;
            height: 450px;
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .btn {
            margin-top: 10px;
        }
        .cropper-crop-box, 
        .cropper-view-box {
            cursor: move; /* Cursor changes to 'move' when over the crop box */
        }
        .cropper-face {
            cursor: move; /* Cursor shows 'move' on the face (central area) of the crop box */
        }
        .cropper-line, 
        .cropper-point {
            cursor: pointer; /* Resize cursors around the edges */
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
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h1>Apply to Become a Girlfriend</h1>
            <?php if ($upload_success === true): ?>
                <div class="alert alert-success">Your application has been submitted!</div>
            <?php elseif ($upload_success === false): ?>
                <div class="alert alert-danger">Image upload failed. Please try again.</div>
            <?php endif; ?>
            <form id="gfForm" method="POST" action="process.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control modern-input" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control modern-input" id="age" name="age" required min="17">
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control modern-input" id="location" name="location" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price per Day</label>
                    <div class="price-input-container">
                        <span class="currency-symbol">$</span>
                        <input type="number" class="form-control modern-input price-input" id="price" name="price" required placeholder="0" min="0" step="0.01">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control modern-input" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="personality" class="form-label">Personality</label>
                    <textarea class="form-control modern-input" id="personality" name="personality" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="rules" class="form-label">Rules</label>
                    <textarea class="form-control modern-input" id="rules" name="rules" rows="3" required></textarea>
                </div>
                <input type="hidden" name="image" id="imageData">
                <button type="submit" class="btn btn-modern">Become GF</button>
            </form>
        </div>
        <div class="crop-container">
            <h2>Upload Picture</h2>
            <div id="dragDropArea" class="drag-drop-area">
                Drop here or click me
            </div>
            <div class="image-upload-container" id="imageUploadContainer" style="display: none;">
                <img id="uploadedImage" src="" alt="Uploaded Image" style="max-width: 100%; height: auto; border-radius: 10px;">
                <button id="editImage" class="btn btn-warning" style="position: absolute; top: 10px; right: 80px;">Edit</button>
                <button id="deleteImage" class="btn btn-danger" style="position: absolute; top: 10px; right: 10px;">Delete</button>
            </div>
            <img id="croppedImage" alt="Cropped Image" style="display:none;"> <!-- Hidden by default -->
        </div>
    </div>


    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <h5 class="modal-title" id="cropperModalLabel">Crop your image</h5>
                <div class="modal-body">
                    <img id="imagePreview" alt="Image for cropping" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropButton">Crop</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'bahan/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cookie.js"></script>
    <script>
        const dragDropArea = document.getElementById('dragDropArea');
        const imagePreview = document.getElementById('imagePreview');
        const cropButton = document.getElementById('cropButton');
        const croppedImage = document.getElementById('croppedImage');
        const submitButton = document.querySelector('button[type="submit"]'); // Adjusted to use the submit button in the form
        const imageDataInput = document.getElementById('imageData');
        const uploadedImage = document.getElementById('uploadedImage');
        const imageUploadContainer = document.getElementById('imageUploadContainer');
        const editImageButton = document.getElementById('editImage');
        const deleteImageButton = document.getElementById('deleteImage');
        let cropper;

        dragDropArea.addEventListener('click', () => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.click();
            fileInput.addEventListener('change', handleImageUpload);
        });

        dragDropArea.addEventListener('dragover', (event) => {
            event.preventDefault();
        });

        dragDropArea.addEventListener('drop', (event) => {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            handleImageUpload({ target: { files: [file] } });
        });

        function handleImageUpload(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;

                const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
                cropperModal.show();

                if (cropper) {
                    cropper.destroy(); // Destroy any existing cropper
                }

                cropper = new Cropper(imagePreview, {
                    aspectRatio: 1,  // Keeps the aspect ratio square
                    viewMode: 2,
                    autoCropArea: 1,
                    zoomable: true,
                    scalable: true,
                    cropBoxResizable: true,
                    cropBoxMovable: true,
                    movable: true,
                    zoomOnTouch: true,
                    zoomOnWheel: true,
                    responsive: true,
                    background: false,
                    minContainerWidth: 450,
                    minContainerHeight: 450,
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
                    ready: function() {
                        const containerData = cropper.getContainerData();
                        const imageData = cropper.getImageData();

                        if (imageData.width > containerData.width || imageData.height > containerData.height) {
                            const zoomRatio = Math.max(
                                containerData.width / imageData.width,
                                containerData.height / imageData.height
                            );
                            cropper.zoomTo(zoomRatio);
                        }

                        cropper.setCropBoxData({
                            left: containerData.width / 4,
                            top: containerData.height / 4,
                            width: containerData.width / 2,
                            height: containerData.width / 2
                        });
                    },
                    cropmove: function(event) {
                        const cropBoxData = cropper.getCropBoxData();
                        const containerData = cropper.getContainerData();

                        if (cropBoxData.left <= 0) {
                            cropper.setCropBoxData({ left: 0 });
                            cropper.move(5, 0);  // Move the image right
                        }

                        if (cropBoxData.left + cropBoxData.width >= containerData.width) {
                            cropper.setCropBoxData({ left: containerData.width - cropBoxData.width });
                            cropper.move(-5, 0);  // Move the image left
                        }

                        if (cropBoxData.top <= 0) {
                            cropper.setCropBoxData({ top: 0 });
                            cropper.move(0, 5);  // Move the image down
                        }

                        if (cropBoxData.top + cropBoxData.height >= containerData.height) {
                            cropper.setCropBoxData({ top: containerData.height - cropBoxData.height });
                            cropper.move(0, -5);  // Move the image up
                        }
                    }
                });

                // Track dragging even when the cursor leaves the crop box or image
                let isDragging = false;

                document.addEventListener('mousedown', () => {
                    isDragging = true; // Start dragging on mousedown
                });

                document.addEventListener('mousemove', (e) => {
                    if (isDragging) {
                        cropper.move(e.movementX, e.movementY);
                    }
                });

                document.addEventListener('mouseup', () => {
                    isDragging = false; // Stop dragging when the mouse is released
                });
            };

            reader.readAsDataURL(file);
        }

        cropButton.addEventListener('click', () => {
            const canvas = cropper.getCroppedCanvas({
                width: 450,
                height: 450,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            const imageSrc = canvas.toDataURL('image/jpeg', 1.0);

            croppedImage.src = imageSrc;
            imageDataInput.value = imageSrc;

            uploadedImage.src = imageSrc;
            imageUploadContainer.style.display = 'block';

            dragDropArea.style.display = 'none'; // Hide the drag-and-drop area
            submitButton.style.display = 'block'; // Show the submit button

            const cropperModal = bootstrap.Modal.getInstance(document.getElementById('cropperModal'));
            cropperModal.hide();
        });

        editImageButton.addEventListener('click', () => {
            const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
            cropperModal.show();
        });

        deleteImageButton.addEventListener('click', () => {
            uploadedImage.src = '';
            imageDataInput.value = '';
            croppedImage.style.display = 'none';
            imageUploadContainer.style.display = 'none';
            submitButton.style.display = 'none';

            // Restore the drag-and-drop area
            dragDropArea.style.display = 'flex';
        });
    </script>
</body>
</html>
