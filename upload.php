<?php
session_start();
include('db_connect.php'); // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $target_dir = "uploads/"; // Directory to store uploaded files
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    $uploadOk = 1;

    // Check if directory exists, if not, create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
    }

    // Check if image file is an actual image or a fake image
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Handle image resizing before uploading
        $target_file_resized = $target_dir . 'resized_' . basename($_FILES["profile_pic"]["name"]);

        // Resize image function
        function resizeImage($file, $target_file_resized, $imageFileType) {
            list($width, $height) = getimagesize($file);

            // Resize dimensions (you can adjust these as needed)
            $new_width = 800; 
            $new_height = ($height / $width) * $new_width;

            // Create a new image resource
            $thumb = imagecreatetruecolor($new_width, $new_height);

            // Depending on file type, use appropriate imagecreate function
            if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
                $source = imagecreatefromjpeg($file);
            } elseif ($imageFileType == 'png') {
                $source = imagecreatefrompng($file);
            } elseif ($imageFileType == 'gif') {
                $source = imagecreatefromgif($file);
            } else {
                return false; // Unsupported format
            }

            // Resize the image
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            // Save the resized image to the target location
            if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
                imagejpeg($thumb, $target_file_resized, 85); // 85% quality
            } elseif ($imageFileType == 'png') {
                imagepng($thumb, $target_file_resized, 6); // Compression level 6
            } elseif ($imageFileType == 'gif') {
                imagegif($thumb, $target_file_resized);
            }

            return $target_file_resized;
        }

        // Call the resize function
        if (resizeImage($_FILES["profile_pic"]["tmp_name"], $target_file_resized, $imageFileType)) {
            // Update user's profile picture in the database
            $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $target_file_resized, $user_id);

            if ($stmt->execute()) {
                echo "Profile picture updated successfully!";
                header('Location: dashboard.php'); // Redirect to dashboard after successful upload
                exit();
            } else {
                echo "Error updating profile picture in the database.";
            }
        } else {
            echo "Sorry, there was an error resizing your image.";
        }
    }
}
?>
