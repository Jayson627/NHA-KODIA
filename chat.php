<?php
// Database credentials
$servername = "127.0.0.1:3306";
$username = "u510162695_sis_db";
$password = "1Sis_dbpassword";
$dbname = "u510162695_sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select all data from the residents table
$sql = "SELECT * FROM residents";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Age: " . $row["age"] . " - Address: " . $row["address"] . "<br>";
        }
    } else {
        echo "No data found in the residents table.";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
