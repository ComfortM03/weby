<?php
// Include database connection
include 'db_connect.php';

session_start();

// Initialize error message
$error_message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'register') {
    // Sanitize and validate input
    $fullnames = trim($_POST['fullnames']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $car_model = trim($_POST['car_model']);
    $car_make = trim($_POST['car_make']);
    $car_registration = trim($_POST['car_registration']);
    $gender = trim($_POST['gender']); // Get gender input

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username already exists. Please choose another.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user record
        $stmt = $conn->prepare("INSERT INTO users (fullnames, username, password, phone, car_model, car_make, car_registration, gender) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $fullnames, $username, $hashed_password, $phone, $car_model, $car_make, $car_registration, $gender);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Registration successful
            header("Location: index.php?success=1");
            exit();
        } else {
            // Capture any errors that occur during execution
            $error_message = "Error: " . $stmt->error;
        }
    }
}

// Display the error message if set
if (!empty($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>
