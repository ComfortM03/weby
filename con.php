<?php
// Start the session
session_start();

// Include database connection file
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the user from the database
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if the password is correct
        if (password_verify($password, $user['password'])) {
            // Set session variable
            $_SESSION['user_id'] = $user['id'];
            
            // Redirect to the dashboard or another page
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
