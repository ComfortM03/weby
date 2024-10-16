<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    // Validate if the inputs are numeric and within the correct range
    if (checkdate($month, $day, $year)) {
        // Combine the birthday as YYYY-MM-DD format for better storage
        $birthday = "$year-$month-$day";

        // Insert or update the birthday in the database
        $stmt = $conn->prepare("UPDATE users SET birthday_day = ?, birthday_month = ?, birthday_year = ?, birthday = ? WHERE id = ?");
        $stmt->bind_param('iiisi', $day, $month, $year, $birthday, $userId);

        if ($stmt->execute()) {
            // Redirect back with a success message
            header("Location: birth.php?message=Birthday successfully updated");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid date. Please enter a valid date.";
    }
}
?>

