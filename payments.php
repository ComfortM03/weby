<?php
// Include database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id']; // Assuming user ID is sent with the form
    $target_dir = "uploads/"; // Directory where the file will be uploaded
    $target_file = $target_dir . basename($_FILES["payment_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid file type (PDF, JPG, PNG)
    if ($fileType != "pdf" && $fileType != "jpg" && $fileType != "png") {
        echo "Sorry, only PDF, JPG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["payment_file"]["tmp_name"], $target_file)) {
            // File successfully uploaded, now save the file path to the database
            $sql = "UPDATE users SET payment_proof = '$target_file' WHERE id = $user_id";

            if ($conn->query($sql) === TRUE) {
                echo "The file " . htmlspecialchars(basename($_FILES["payment_file"]["name"])) . " has been uploaded.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Redirect back to the main page (optional)
    header("Location: index.html");
    exit();
}
?>
