<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user data including birthday
$query = "SELECT username, gender, birthday_day, birthday_month, birthday_year, birthday FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $userName = $user['username'];
    $gender = $user['gender'];
    $birthdayDay = $user['birthday_day'];
    $birthdayMonth = $user['birthday_month'];
    $birthdayYear = $user['birthday_year'];

    // Greeting message based on gender
    if ($gender === 'male') {
        $greeting = "Hello Mr. " . htmlspecialchars($userName);
    } elseif ($gender === 'female') {
        $greeting = "Hello Mrs. " . htmlspecialchars($userName);
    } else {
        $greeting = "Hello " . htmlspecialchars($userName);
    }
} else {
    header("Location: index.php");
    exit();
}

// Check if the user has already entered a birthday
$hasBirthday = !empty($birthdayDay) && !empty($birthdayMonth) && !empty($birthdayYear);

// Get the success message (added or updated birthday)
$message = '';
if (isset($_GET['message'])) {
    $messageType = htmlspecialchars($_GET['message']);
    if ($messageType === 'added') {
        $message = "You have successfully added your birthday.";
    } elseif ($messageType === 'updated') {
        $message = "You have successfully updated your birthday.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Birthday</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .form-container, .birthday-container, .greeting-container {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .greeting-container {
            text-align: center;
            font-size: 1.2em;
            color: #007bff;
        }

        .birthday-container {
            display: <?php echo $hasBirthday ? 'block' : 'none'; ?>;
            text-align: center;
            font-size: 1.2em;
            cursor: pointer;
        }

        .birthday-container p {
            margin: 0;
        }

        .form-container {
            display: <?php echo $hasBirthday ? 'none' : 'block'; ?>;
        }

        h2 {
            font-size: 1.5em;
            color: #333;
        }

        label {
            font-size: 1em;
            display: block;
            margin-bottom: 8px;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .submit-btn {
            background-color: rgba(0, 123, 255, 0.8);
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: rgba(0, 123, 255, 1);
        }

        .message {
            margin-top: 20px;
            color: #28a745;
            text-align: center;
        }
    </style>
    <script>
        function toggleForm() {
            var formContainer = document.querySelector('.form-container');
            formContainer.style.display = (formContainer.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</head>
<body>
<header style="background-color: #007bff; padding: 20px; color: #fff; text-align: center;">
    <h1 style="margin: 0; font-size: 2em;">Add Birthday</h1>
</header>

<!-- Greeting -->
<div class="greeting-container">
    <?php echo $greeting; ?>
</div>

<!-- Success message -->
<?php if ($message) { ?>
    <div class="message"><?php echo $message; ?></div>
<?php } ?>

<!-- Birthday display -->
<div class="birthday-container" onclick="toggleForm()">
    <p>Your birthday is: 
        <?php 
        if ($hasBirthday) {
            echo htmlspecialchars($birthdayDay) . ' / ' . htmlspecialchars($birthdayMonth) . ' / ' . htmlspecialchars($birthdayYear);
        } else {
            echo "Not set.";
        }
        ?>
    </p>
</div>

<!-- Birthday form -->
<div class="form-container">
    <h2><?php echo $hasBirthday ? "Update Your Birthday" : "Add Your Birthday"; ?></h2>
    
    <form action="births.php" method="POST">
        <div class="form-group">
            <label for="day">Day</label>
            <select name="day" id="day" required>
                <option value="">Select Day</option>
                <?php for ($i = 1; $i <= 31; $i++) { ?>
                    <option value="<?php echo $i; ?>" <?php echo ($i == $birthdayDay) ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="month">Month</label>
            <select name="month" id="month" required>
                <option value="">Select Month</option>
                <?php
                $months = array(
                    1 => "January", 2 => "February", 3 => "March", 4 => "April",
                    5 => "May", 6 => "June", 7 => "July", 8 => "August",
                    9 => "September", 10 => "October", 11 => "November", 12 => "December"
                );
                foreach ($months as $num => $name) { ?>
                    <option value="<?php echo $num; ?>" <?php echo ($num == $birthdayMonth) ? 'selected' : ''; ?>>
                        <?php echo $name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" name="year" id="year" placeholder="Enter Year" min="1900" max="<?php echo date("Y"); ?>" value="<?php echo htmlspecialchars($birthdayYear); ?>" required>
        </div>

        <button type="submit" class="submit-btn"><?php echo $hasBirthday ? "Update Birthday" : "Submit Birthday"; ?></button>
    </form>
</div>

</body>
</html>
