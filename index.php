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
        
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
    <ul class="header-links">
         
         <li><a href="#">LUBANZA's LOGIN</a></li>

     </ul>
        </ul>
    </header>

    
    <div class="background-box">
        <div class="form-container">
            <img src="car.jpeg" alt="Logo" class="form-image">
            <div class="form-header">Login</div>
    
            <!-- Login Form -->
            <form id="login-form" action="con.php" method="POST">
    <input type="hidden" name="action" value="login">
    <input type="text" id="username" name="username" placeholder="Username" required>
    <div style="position: relative;">
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password')">👁️</button>
    </div>
    <button type="submit" class="translucent-button">Login</button>
    
    <!-- Registration Link -->
    <div class="form-link-box">
        <div class="form-link" onclick="toggleForms('reset-password')">Forgot Password?</div>
        <div class="form-link">
            <a href="home.php">Register Here</a> <!-- Link to registration page -->
        </div>
    </div>
    
            
            <!-- Success Message Box (Initially Hidden) -->
            <div id="success-message-box" class="hidden">
                <p class="success-message">Registration successful!<span class="success-icon">✔️</span></p>
            </div>

        </div>
    </div>
    
    <script>
        // Toggle between login and registration forms
        function toggleForms(form) {
            const loginForm = document.getElementById('login-form');
            const registrationForm = document.getElementById('registration-form');
            const successMessageBox = document.getElementById('success-message-box');
    
            if (form === 'registration') {
                loginForm.classList.add('hidden');
                registrationForm.classList.remove('hidden');
                successMessageBox.classList.add('hidden');
            } else {
                loginForm.classList.remove('hidden');
                registrationForm.classList.add('hidden');
            }
        }
    
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