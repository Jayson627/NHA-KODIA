<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $incident_date = $_POST['incident_date'];
    $incident_description = $_POST['incident_description'];
    $reported_by = $_POST['reported_by'];

    // Prepare and bind for incident report
    $stmt = $conn->prepare("INSERT INTO incidents (incident_date, description, reported_by) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $incident_date, $incident_description, $reported_by);

    if ($stmt->execute()) {
        // If incident report was successfully inserted, insert a new notification
        $notification_content = "New incident report submitted by $reported_by";
        $notification_stmt = $conn->prepare("INSERT INTO notifications (content, date) VALUES (?, NOW())");
        $notification_stmt->bind_param("s", $notification_content);
        $notification_stmt->execute();

        // Trigger success alert and redirect
        echo "<script>
            alert('Incident report successfully sent.');
            window.location.href = 'dashboard.php'; // Redirect to a new page after submission
        </script>";
    } else {
        // If something went wrong
        echo "<script>
            alert('Failed to submit the incident report. Please try again.');
        </script>";
    }

    // Close statement and connection
    $stmt->close();
    $notification_stmt->close();
    $conn->close();
}
?>
