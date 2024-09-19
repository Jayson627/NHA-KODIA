<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update unread notifications to read
$conn->query("UPDATE notifications SET is_read = 1 WHERE is_read = 0");

$conn->close();
?>
