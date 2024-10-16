<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lubanza";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Users from the Database
$sql_fetch = "SELECT * FROM users";
$result = $conn->query($sql_fetch);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Add or Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullnames = $conn->real_escape_string($_POST['fullnames']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $car_model = $conn->real_escape_string($_POST['car_model']);
    $car_make = $conn->real_escape_string($_POST['car_make']);
    $car_registration = $conn->real_escape_string($_POST['car_registration']);
    $gender = $conn->real_escape_string($_POST['gender']); // Capture gender

    if (isset($_POST['add_user'])) {
        // Check if username is unique
        $sql_check = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql_check);
        if ($result->num_rows > 0) {
            $error_message = "Username already exists. Please choose another.";
        } else {
            $sql = "INSERT INTO users (fullnames, username, email, phone, car_model, car_make, car_registration, gender, password) 
                    VALUES ('$fullnames', '$username', '$email', '$phone', '$car_model', '$car_make', '$car_registration', '$gender', '12345')";
            if ($conn->query($sql)) {
                $success_message = "User added successfully!";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
    } elseif (isset($_POST['update_user'])) {
        $user_id = $conn->real_escape_string($_POST['user_id']);
        $sql = "UPDATE users SET fullnames = '$fullnames', email = '$email', phone = '$phone', car_model = '$car_model', 
                car_make = '$car_make', car_registration = '$car_registration', gender = '$gender' WHERE id = '$user_id'";
        if ($conn->query($sql)) {
            $success_message = "User updated successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Delete User
if (isset($_GET['delete_user'])) {
    $user_id = $conn->real_escape_string($_GET['delete_user']);
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Park Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            background-image: url('who.jpg');
            background-size: cover;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: black; 
            border-radius: 15px;
            padding: 10px;
            text-align: center;
            color: white;
            font-size: 1.5em;
            margin-top: 10px;
        }
        .header .title {
            margin-bottom: 20px;
        }
        .header .options {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .header .options a {
            color: white;
            background-color: #0097a7;
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 1em;
        }
        .header .options a:hover {
            background-color: #00796b;
        }
        .section {
            display: none;
            margin-top: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }
        form input, form select, form textarea, form button {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }
        form button {
            background-color: #00bcd4;
            border: none;
            cursor: pointer;
            color: white;
        }
        form button:hover {
            background-color: #0097a7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        table th {
            background-color: #00bcd4;
            color: white;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .actions button {
            background-color: #00bcd4;
            border: none;
            padding: 5px 10px;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .actions button:hover {
            background-color: #0097a7;
        }
        .collapse-btn {
            cursor: pointer;
            color: white;
            font-size: 1.2em;
        }

        .header {
    background-color: black;
    border-radius: 25px;
    padding: 10px;
    text-align: center;
    color: white;
    font-size: 1em;
    margin-top: 10px;
    position: relative;
}

.menu-icon {
    position: absolute;
    right: 20px;
    top: 10px;
    cursor: pointer;
    font-size: 24px; /* Size of the hamburger icon */
}

.menu {
    display: none; /* Initially hide the menu */
    position: absolute;
    right: 0;
    top: 60px; /* Adjust based on your header height */
    background-color: black;
    border-radius: 0 0 0 10px;
    width: 200px; /* Width of the menu */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.menu li {
    padding: 10px;
}

.menu li a {
    color: white;
    text-decoration: none;
    display: block; /* Make the entire area clickable */
}

.menu li a:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Hover effect */
}

.gender-container {
    margin: 20px 0;
    padding: 5px;
    border: 1px solid #ccc; /* Add border */
    border-radius: 5px; /* Rounded corners */
    background-color: rgba(255, 255, 255, 0.2);
}

.gender-options {
    display: flex; /* Align items in a row */
    gap: 15px; /* Space between options */
}

.gender-options label {
    display: flex;
    align-items: center; /* Center the radio and text */
    cursor: pointer; /* Pointer on hover */
    font-weight: bold;
}

    </style>
</head>
<body>

    <!-- Header section -->
    <header class="header">
    <div class="title">CarPark Management System</div>
    <div class="menu-icon" onclick="toggleMenu()">
        &#9776; <!-- This is the hamburger icon (three lines) -->
    </div>
    <nav id="menu" class="menu">
        <ul>
            <li><a href="cust.php">Customer </a></li>
            <li><a href="feed.php">Feedback </a></li>
            <li><a href="pay.php">Payment </a></li>
            
            <!-- Add more menu items as needed -->
        </ul>
    </nav>
</header>

    <!-- Add/Edit User Form -->
    <div class="container">
    <form id="user-form" method="POST">
        <input type="hidden" name="user_id" id="user_id">
        <input type="text" name="fullnames" id="fullnames" placeholder="Full Names" required>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="email" name="email" id="email" placeholder="Email Address" required>
        <input type="number" name="phone" id="phone" placeholder="Phone Number" required>
        <input type="text" name="car_model" id="car_model" placeholder="Car Model" required>
        <input type="text" name="car_make" id="car_make" placeholder="Car Make" required>
        <input type="text" name="car_registration" id="car_registration" placeholder="Car Registration Number" required>
        
        <!-- Gender Selection -->
        <div class="gender-container">
    <label>Gender</label>
    <div class="gender-options">
        <label>
            <input type="radio" name="gender" value="Male" required> Male
        </label>
        <label>
            <input type="radio" name="gender" value="Female" required> Female
        </label>
        <label>
            <input type="radio" name="gender" value="Other" required> Other
        </label>
    </div>
</div>
        
        <button type="submit" name="add_user">Add User</button>
        <button type="submit" name="update_user" id="update_user" style="display: none;">Update User</button>
    </form>
</div>

<!-- User Table -->
<div class="container">
    <table>
        <thead>
            <tr>
                <th>Full Names</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Car Model</th>
                <th>Car Make</th>
                <th>Car Registration</th>
                <th>Gender</th> <!-- Added Gender Column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr id="user-<?php echo $user['id']; ?>">
                    <td><?php echo $user['fullnames']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td><?php echo $user['car_model']; ?></td>
                    <td><?php echo $user['car_make']; ?></td>
                    <td><?php echo $user['car_registration']; ?></td>
                    <td><?php echo $user['gender']; ?></td> <!-- Display Gender -->
                    <td class="actions">
                        <button onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                        <button onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function editUser(userId) {
    const row = document.getElementById(`user-${userId}`);
    const fullnames = row.cells[0].innerText;
    const username = row.cells[1].innerText;
    const email = row.cells[2].innerText;
    const phone = row.cells[3].innerText;
    const carModel = row.cells[4].innerText;
    const carMake = row.cells[5].innerText;
    const carRegistration = row.cells[6].innerText;
    const gender = row.cells[7].innerText; // Get Gender Value

    document.getElementById('fullnames').value = fullnames;
    document.getElementById('username').value = username;
    document.getElementById('email').value = email;
    document.getElementById('phone').value = phone;
    document.getElementById('car_model').value = carModel;
    document.getElementById('car_make').value = carMake;
    document.getElementById('car_registration').value = carRegistration;
    document.getElementById('user_id').value = userId;

    // Set Gender
    const genderRadios = document.querySelectorAll('input[name="gender"]');
    genderRadios.forEach(radio => {
        radio.checked = (radio.value === gender); // Ensure correct radio is checked
    });

    document.getElementById('update_user').style.display = 'block';
    document.querySelector('button[name="add_user"]').style.display = 'none';
}

    function deleteUser(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            fetch(`?delete_user=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`user-${userId}`).remove();
                        alert("User deleted successfully!");
                    } else {
                        alert("Error deleting user: " + data.message);
                    }
                });
        }
    }

    function toggleMenu() {
        const menu = document.getElementById('menu');
        // Toggle display style
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }
</script>

</body>
</html>
