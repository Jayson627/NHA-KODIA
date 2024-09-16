<?php 
// Database connection
$servername = "localhost"; // Use 'localhost' for local server
$username = "root";
$password = "";
$dbname = "sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>