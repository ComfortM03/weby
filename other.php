<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transparent Boxes with Links</title>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            background-image: url('who.jpg');
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the background image */
            background-repeat: no-repeat;
        }

        .container {
            display: flex;
            gap: 20px;
        }

        .box {
            width: 200px;
            height: 150px;
            background-color: rgba(255, 255, 255, 0.4); /* Transparent background */
            border: 1px solid rgba(0, 0, 0, 0.1); /* Transparent border */
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-weight: bold;
        }

        .box:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Slightly more visible on hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
        }

        .box a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }

        .label {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
            display: block;
        }

        .box-content {
            display: flex;
            flex-direction: column;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .box {
                width: 80%; /* Make the boxes wider on smaller screens */
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Box 1: Add Birthday -->
        <div class="box">
            <div class="box-content">
                <span class="label">Add Birthday</span>
                <a href="birth.php">Go to Birth Form</a>
            </div>
        </div>

        <!-- Box 2: Add Email -->
        <div class="box">
            <div class="box-content">
                <span class="label">Add Email</span>
                <a href="email.php">Go to Email Form</a>
            </div>
        </div>

        <!-- Box 3: Change Password -->
        <div class="box">
            <div class="box-content">
                <span class="label">Change Password</span>
                <a href="change.php">Go to Change Password</a>
            </div>
        </div>
    </div>

</body>
</html>
