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

// Show all tables in the database
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "Tables in the database:<br>";
        while ($row = $result->fetch_row()) {
            echo $row[0] . "<br>";
        }
    } else {
        echo "No tables found in the database.";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
