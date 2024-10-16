<?php
// Include database connection
include 'db_connect.php';

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Display users in a table
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['fullnames'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['phone'] . "</td>";
        echo "<td>" . $row['car_model'] . "</td>";
        echo "<td>" . $row['car_make'] . "</td>";
        echo "<td>" . $row['car_registration'] . "</td>";
        echo "<td><button class='btn-edit' onclick='editUser(" . $row['id'] . ")'>Edit</button>";
        echo "<button class='btn-delete' onclick='deleteUser(" . $row['id'] . ")'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
