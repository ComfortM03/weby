<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Park Website</title>
    <link rel="stylesheet" href="home.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Light background for contrast */
            background-image: url('what.jpg');
        }

        /* Header Section */
        header {
            color: white; /* White text color */
            padding: 2px; /* Padding for spacing */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Shadow effect */
            text-align: center; /* Center text */
            margin-top: 20px; /* Space above header */
            margin-inline: 40px;
            background-image: url('who.jpg'); /* Add your background image URL here */
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the background image */
            background-repeat: no-repeat;
        }

        /* Header Links */
        .header-links {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove padding */
        }

        .header-links li {
            display: inline; /* Display items in a line */
            margin: 0 75px; /* Space between items */
        }

        .header-links a {
            color: white; /* Link color */
            text-decoration: none; /* No underline */
            font-size: 18px; /* Font size */
        }

        .header-links a:hover {
            color: #4400ff; /* Highlight color on hover */
        }

        .home-icon {
            font-weight: bold; /* Bold for home icon */
            font-size: 20px; /* Icon size */
        }

        /* Gender Container Styling */
.gender-container {
    margin: 15px 0;
    font-size: 16px;
}

.gender-container label {
    font-weight: bold;
    margin-right: 10px;
}

/* Gender Options Styling */
.gender-options {
    display: flex;
    justify-content: space-around;
    margin-top: 5px;
}

.gender-options label {
    display: flex;
    align-items: center;
    margin-right: 15px;
    font-weight: normal;
}

.gender-options input[type="radio"] {
    margin-right: 5px;
}

/* Responsive Styling */
@media (max-width: 600px) {
    .gender-options {
        flex-direction: column;
        align-items: flex-start;
    }

    .gender-options label {
        margin-bottom: 10px;
    }
}

    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <ul class="header-links">
            
            <li><a href="#">LUBANZA REGISTRATION</a></li>
            
        </ul>
    </header>

    <div class="background-box">
        <div class="form-container">
            <img src="car.jpeg" alt="Logo" class="form-image">
    
            <!-- Registration Form -->
            <form id="registration-form" action="auth.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-header">Registration</div>
                <input type="text" id="fullnames" name="fullnames" placeholder="Full Names" required>
                <input type="text" id="user-name" name="username" placeholder="Username" required>
                <input type="text" id="phone" name="phone" placeholder="Phone Number" required>
                <div style="position: relative;">
                    <input type="password" id="reg-password" name="password" placeholder="Password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('reg-password')">👁️</button>
                </div>
                <input type="text" id="car-model" name="car_model" placeholder="Car Model" required>
                <input type="text" id="car-make" name="car_make" placeholder="Car Make" required>
                <input type="text" id="car-registration-number" name="car_registration" placeholder="Car Registration Number" required>

                <div class="gender-container">
               
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

            <button type="submit" class="translucent-button">Register</button>
        </form>
    
            <!-- Success Message Box (Initially Hidden) -->
            <div id="success-message-box" class="hidden">
    <p class="success-message">Registration successful! <span class="success-icon">✔️</span></p>
</div>
<div class="form-link-box">
    <!-- Modified link to redirect to index.php -->
    <div class="form-link">
        <a href="index.php">Already have an account? Login</a>
    </div>
</div>


    
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(passwordFieldId) {
            const passwordInput = document.getElementById(passwordFieldId);
            const toggleButton = passwordInput.nextElementSibling;
    
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Show password
                toggleButton.textContent = '🙈'; // Change icon to indicate hide action
            } else {
                passwordInput.type = 'password'; // Hide password
                toggleButton.textContent = '👁️'; // Change icon to indicate show action
            }
        }
        
    </script>
</body>
</html>