<?php
// Include database connection
require_once('admin/connection.php');
require_once("initialize.php");

// Query to fetch all logs
$query = "SELECT id, user_email, status, message, ip_address FROM logs";

// Run the query and check if it is successful
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    // If the query fails, show the error message
    die("Error executing query: " . $conn->error);
}

// Check if there are logs to display
if ($result->num_rows > 0) {
    // Display logs in a table
    echo "<table border='1'><tr><th>ID</th><th>Email</th><th>Status</th><th>Message</th><th>IP Address</th><th>Timestamp</th></tr>";

    while ($row = $result->fetch_assoc()) {
        // Ensure the timestamp exists before accessing it
        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['user_email'] . "</td><td>" . $row['status'] . "</td><td>" . $row['message'] . "</td><td>" . $row['ip_address'] . "</td><td>" . "</td></tr>";
    }

    echo "</table>";
} else {
    echo "No logs found.";
}

$conn->close();
?>
