<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You are not logged in. No session found.");
}

// Database connection details
$host = 'localhost'; // Database host
$db = 'lubanza'; // Replace with your actual database name
$user = 'root'; // Default username for XAMPP
$pass = ''; // Default password for XAMPP (usually blank)

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from database using the session user_id
$user_id = $_SESSION['user_id']; // Use the session variable for user_id
$query = "SELECT fullnames, username, car_registration, car_model, car_make, gender, birthday FROM users WHERE id = ?"; // Select the user based on ID
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind user_id to the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch the user's data
} else {
    die("User not found.");
}

// Fetch today's date
$today = date('Y-m-d'); // Format: YYYY-MM-DD

// Check for holidays (assuming you have a holidays table)
$holiday_query = "SELECT holiday_name FROM holidays WHERE holiday_date = ?";
$holiday_stmt = $conn->prepare($holiday_query);
$holiday_stmt->bind_param("s", $today); // Bind today's date
$holiday_stmt->execute();
$holiday_result = $holiday_stmt->get_result();

// Close the statement
$stmt->close();
$holiday_stmt->close();

// Close the connection
$conn->close();

// Initialize messages
$birthday_message = '';
$holiday_message = '';
$date_of_birth = $user['birthday'] ?? null; // Use null coalescing operator to avoid undefined index

// Check for birthday messages
if ($date_of_birth) {
    $user_birthday = date('Y-m-d', strtotime($date_of_birth)); // Format the user's birthday
    if ($today === $user_birthday) {
        $birthday_message = "🎉 Happy Birthday, " . htmlspecialchars($user['fullnames']) . "! 🎂";
        $date_of_birth_display = ''; // Hide the date of birth
    } else {
        $date_of_birth_display = htmlspecialchars($date_of_birth);
    }
} else {
    $date_of_birth_display = "Please insert your date of birth in the system through the information menu.";
}

// Check for holiday message
if ($holiday_result->num_rows > 0) {
    $holiday_row = $holiday_result->fetch_assoc();
    $holiday_message = "🎊 Happy " . htmlspecialchars($holiday_row['holiday_name']) . "! 🎊";
}

// Combine messages for display
$combined_message = '';
if ($birthday_message && $holiday_message) {
    $combined_message = $birthday_message . ' and ' . $holiday_message;
} else {
    $combined_message = $birthday_message ?: $holiday_message;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Park Website</title>
    <link rel="stylesheet" href="dash.css">

    <style>
        
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <div>
            <ul class="header-links">
                <li><a href="#" class="home-icon">&#127968; Home</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Other Members</a></li>
                <li><a href="pay.php">Payment Modes</a></li>
            </ul>
        </div>
        
        <!-- Profile Picture Placeholder -->
        <div class="profile-placeholder">
            <?php if (!empty($user['profile_pic'])): ?>
                <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
            <?php else: ?>
            <?php endif; ?>
        </div>

        <div class="menu-icon" onclick="toggleMenu(event)">
            &#9776; <!-- Hamburger Icon -->
        </div>
        <nav id="menu" class="menu">
            <ul>
                <li><a href="pay.php">Payment</a></li>
                <li><a href="other.php">Other Information</a></li>
                <li><a href="#" onclick="toggleProfileUpload()">Upload Profile Picture</a></li> <!-- New menu item -->
            </ul>
        </nav>
    </header>
    <div class="info-container">
    <main>
    <h1>Hi, <?php echo ucfirst($user['gender']) === 'Male' ? 'Mr.' : 'Mrs.'; ?> <?php echo htmlspecialchars($user['username']); ?>! 👋</h1>
                
    </h1>
                
        <div class="user-info">
            <div class="info-box">
                <label>Car Registration</label>
                <p><?php echo htmlspecialchars($user['car_registration']); ?></p>
            </div>
            <div class="info-box">
                <label>Car Model</label>
                <p><?php echo htmlspecialchars($user['car_model']); ?></p>
            </div>
            <div class="info-box">
                <label>Car Make</label>
                <p><?php echo htmlspecialchars($user['car_make']); ?></p>
            </div>
            <div class="info-box">
                <label>Gender</label>
                <p><?php echo htmlspecialchars(string: $user['gender']); ?></p>
            </div>
            <div class="info-box">
        <label>Full Name</label>
        <?php 
        // Display the user's full name with title based on gender
        $title = (strtolower($user['gender']) === 'female') ? 'Mrs.' : 'Mr.';
        echo "<p>" . htmlspecialchars($title . " " . $user['fullnames']) . "</p>"; 
        ?>
    </div>
    <div class="info-box">
        <label>Date of Birth</label>
        <p>
            <?php 
            // Display the date of birth or appropriate message
            echo $date_of_birth_display;
            if ($combined_message) {
                echo "<br>" . $combined_message; // Display combined birthday and holiday message
            }
            ?>
        </p>
    </div>
        <div class="profile-pic-upload" id="profilePicUpload">
            <h2>Upload Profile Picture</h2>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" accept="image/*" required>
                <button type="submit">Upload</button>
            </form>
        </div>
        <div class="calendar-container">
    <div class="calendar-header">
        <button id="prevMonth">&lt;</button>
        <h2 id="monthYear"></h2>
        <button id="nextMonth">&gt;</button>
    </div>
    <div class="calendar" id="calendar"></div>
    <div class="event-display" id="eventDisplay"></div>
</div>

    </main>

    <script>
    // Function to toggle the menu
    function toggleMenu(event) {
        event.stopPropagation(); // Prevent click event from bubbling up
        const menu = document.getElementById('menu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }

    // Close the menu when clicking anywhere outside of it
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('menu');
        const menuIcon = document.querySelector('.menu-icon');
        if (menu.style.display === 'block' && !menu.contains(event.target) && !menuIcon.contains(event.target)) {
            menu.style.display = 'none';
        }
    });

    // Function to toggle the profile picture upload section
    function toggleProfileUpload() {
        const uploadSection = document.getElementById('profilePicUpload');
        uploadSection.style.display = (uploadSection.style.display === 'block') ? 'none' : 'block';
    }

    const holidays = {
        "2024-01-01": "New Year's Day",
        "2024-02-14": "Boyfriend and Girlfriend Day",
        "2024-03-08": "International Women's Day",
        "2024-03-29": "Good Friday",
        "2024-03-31": "Easter Sunday",
        "2024-05-01": "Labour Day",
        "2024-05-25": "Africa Freedom Day",
        "2024-06-12": "Youth Day",
        "2024-10-24": "Independence Day",
        "2024-12-25": "Christmas Day",
        "2024-12-26": "Boxing Day",
        "2024-12-31": "New Year's Eve"
    };

    let currentUserBirthday = "1990-10-12"; // Placeholder; this will be dynamically fetched or input

    // Function to format date to 'YYYY-MM-DD' string
    function formatDateToISO(date) {
        return date.toISOString().split('T')[0];
    }

    // Function to generate the calendar
    function generateCalendar(date) {
        const calendar = document.getElementById('calendar');
        const eventDisplay = document.getElementById('eventDisplay');
        const monthYear = document.getElementById('monthYear');

        // Clear previous content
        calendar.innerHTML = ""; 

        // Set month and year display
        monthYear.innerText = date.toLocaleString('default', { month: 'long' }) + " " + date.getFullYear();

        // Days of the week headers
        const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        daysOfWeek.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.classList.add('day', 'dayHeader');
            dayHeader.innerText = day;
            calendar.appendChild(dayHeader);
        });

        // Get first day and number of days in the month
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
        const monthDays = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

        // Fill in the days from the previous month
        for (let i = 0; i < firstDay; i++) {
            const emptyElement = document.createElement('div');
            emptyElement.classList.add('day');
            calendar.appendChild(emptyElement);
        }

        // Create days for the current month
        for (let day = 1; day <= monthDays; day++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.innerText = day;

            const dayString = formatDateToISO(new Date(date.getFullYear(), date.getMonth(), day));

            // Highlight today's date
            const today = new Date();
            const todayString = formatDateToISO(today);

            if (dayString === todayString) {
                dayElement.style.backgroundColor = '#FFD700'; // Gold color for current date
            }

            // Check if the day is a holiday
            if (holidays[dayString]) {
                dayElement.innerText += ` (${holidays[dayString]})`;
            }

            // Highlight user's birthday if it falls within the current month
            if (dayString === currentUserBirthday) {
                dayElement.innerText += " 🎉 Happy Birthday!";
            }

            calendar.appendChild(dayElement);
        }

        // Display event message if today is a holiday or birthday
        if (holidays[todayString]) {
            eventDisplay.innerText = `Today is ${holidays[todayString]}!`;
            eventDisplay.style.display = 'block';
        } else if (todayString === currentUserBirthday) {
            eventDisplay.innerText = `Happy Birthday, User! 🎂`;
            eventDisplay.style.display = 'block';
        } else {
            eventDisplay.style.display = 'none'; // Hide if no event today
        }
    }

    // Change month functionality
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar(currentDate);
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar(currentDate);
    });

    // Initial calendar generation when the page loads
    let currentDate = new Date(); // Initialize current date dynamically
    window.onload = () => generateCalendar(currentDate);

</script>

</body>
</html>