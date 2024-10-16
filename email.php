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
$query = $conn->prepare("SELECT username, gender, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$email_message = "";
$current_email = $user['email'];

// Handle email submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email already exists in the users table for another user
        $check_email = $conn->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $user_id); // Exclude current user
        $check_email->execute();
        $email_exists = $check_email->get_result()->num_rows > 0;

        if (!$email_exists) {
            // Update the user's email in the users table
            $update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_email->bind_param("si", $email, $user_id);
            if ($update_email->execute()) {
                $email_message = $current_email ? "Email successfully updated!" : "Email successfully added!";
                $current_email = $email; // Update the current email shown on form
            } else {
                $email_message = "Error updating email!";
            }
        } else {
            $email_message = "Email already exists for another user!";
        }
    } else {
        $email_message = "Invalid email format!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            color: #333;
            font-size: 2.5em;
        }
        .email-form {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
            max-width: 400px;
        }
        .email-form.active {
            display: block;
            opacity: 1;
        }
        input[type="email"] {
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
    <h1>
        Hi, <?php echo ucfirst($user['gender']) === 'Male' ? 'Mr.' : 'Mrs.'; ?>
        <?php echo htmlspecialchars($user['username']); ?>! 👋
    </h1>

    <button id="showFormButton">Enter/Update Your Email</button>

    <form method="POST" class="email-form" id="emailForm">
        <h2>Enter/Update Your Email</h2>
        <input type="email" name="email" value="<?php echo htmlspecialchars($current_email); ?>" placeholder="Enter your email" required>
        <button type="submit"><?php echo $current_email ? 'Update Email' : 'Submit'; ?></button>
        <p class="<?php echo $email_message ? ($email_message == 'Email successfully updated!' || $email_message == 'Email successfully added!' ? 'message' : 'error') : ''; ?>">
            <?php echo $email_message; ?>
        </p>
    </form>
</div>

<script>
    // Toggle form visibility
    document.getElementById('showFormButton').addEventListener('click', function() {
        const emailForm = document.getElementById('emailForm');
        emailForm.classList.toggle('active');
    });
</script>

</body>
</html>
