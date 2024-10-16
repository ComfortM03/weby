<?php
session_start();
require 'db_connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch the default phone number from settings
$query = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'default_phone_number'");
$query->execute();
$result = $query->get_result();
$default_phone_number = $result->fetch_assoc()['setting_value'];

// Fetch unpaid car fee days for the user
$user_id = $_SESSION['user_id'];
$unpaid_days_query = $conn->prepare("SELECT day, amount FROM car_fees WHERE user_id = ? AND status = 'Unpaid'");
$unpaid_days_query->bind_param("i", $user_id);
$unpaid_days_query->execute();
$unpaid_days_result = $unpaid_days_query->get_result();
$unpaid_days = $unpaid_days_result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process deposit
    $amount = $_POST['amount'];
    $payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : null;
    $service_provider = isset($_POST['service_provider']) ? $_POST['service_provider'] : null;

    // Validate input
    if ($amount < 5) {
        echo "<p class='error'>Error: Minimum deposit amount is K5.</p>";
        exit();
    } elseif ($amount == 10) {
        echo "<p class='notice'>Notice: Big cars like buses incur a fee of K10.</p>";
    }

    if ($payment_type === null || $service_provider === null) {
        echo "<p class='error'>Error: Payment type or service provider not selected.</p>";
        exit(); // Stop further execution
    }

    // Insert transaction into the database
    $insert_transaction = $conn->prepare(
        "INSERT INTO transactions (user_id, amount, service_provider, payment_type, status) VALUES (?, ?, ?, ?, 'Pending')"
    );
    $insert_transaction->bind_param("idsd", $user_id, $amount, $service_provider, $payment_type);
    if ($insert_transaction->execute()) {
        echo "<p class='success'>Payment of K" . htmlspecialchars($amount) . " successfully sent to " . htmlspecialchars($default_phone_number) . " using " . htmlspecialchars($service_provider) . ".</p>";
        // Here you would call the payment processing API
    } else {
        echo "<p class='error'>Error: Could not process the deposit.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .notice {
            color: orange;
        }
        h2 {
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Make a Deposit</h1>

    <form method="POST">
        <label for="amount">Amount (K):</label>
        <input type="number" name="amount" min="5" required>
        
        <label for="payment_type">Payment Type:</label>
        <select name="payment_type" required>
            <option value="">Select Payment Type</option>
            <option value="Car Fee">Car Fee</option>
            <option value="Monthly Payment">Monthly Payment</option>
            <option value="Today Payment">Today Payment</option>
        </select>

        <label for="service_provider">Service Provider:</label>
        <select name="service_provider" required>
            <option value="">Select Service Provider</option>
            <option value="Airtel">Airtel</option>
            <option value="MTN">MTN</option>
            <option value="Zamtel">Zamtel</option>
        </select>
        
        <button type="submit">Submit</button>
    </form>

    <h2>Unpaid Car Fees</h2>
    <table>
        <tr>
            <th>Day</th>
            <th>Amount (K)</th>
        </tr>
        <?php foreach ($unpaid_days as $day) : ?>
            <tr>
                <td><?php echo htmlspecialchars($day['day']); ?></td>
                <td><?php echo htmlspecialchars($day['amount']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
