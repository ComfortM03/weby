<?php
session_start();
require 'db_connect.php'; // Add your DB connection script

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: home.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, gender, password FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// If user not found in the database, redirect to home
if (!$user) {
    header("Location: home.php");
    exit();
}

// Handle password change
$password_message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new password and confirm password match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the user's password in the users table
        $update_password = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_password->bind_param("si", $hashed_password, $user_id);
        
        if ($update_password->execute()) {
            $password_message = "Password successfully updated!";
        } else {
            $password_message = "Error updating password!";
        }
    } else {
        $password_message = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the background image */
            background-repeat: no-repeat;
        }
        .container {
            margin-top: 50px;
            border: 1px solid #000;
            box-sizing: border-box;
        }
        h1 {
            color: #333;
            font-size: 2.5em;
        }
        .password-form {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
            max-width: 400px;
        }
        input[type="password"] {
            width: 80%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Hello, <?php echo htmlspecialchars($user['username']); ?>! 🗝️</h1>
    <p>Your current password is: <strong>******</strong></p> <!-- Masked for security -->

    <div class="password-form">
        <h2>Change Your Password</h2>
        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit">Update Password</button>
            <p class="<?php echo $password_message ? ($password_message == 'Password successfully updated!' ? 'message' : 'error') : ''; ?>">
                <?php echo $password_message; ?>
            </p>
        </form>
    </div>
</div>

</body>
</html>
