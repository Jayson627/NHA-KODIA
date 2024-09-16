<?php
// Database credentials
$servername = "127.0.0.1:3306";
$username = "u510162695_sis_db";
$password = "1Sis_dbpassword";
$dbname = "u510162695_sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connections
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch announcements from the database
$sql = "SELECT id, title, content, date FROM announcements ORDER BY date DESC";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007BFF; /* Blue color for header */
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
        }
        .header .notification-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            position: relative;
        }
        .header .notification-icon svg {
            fill: #fff; /* White color for SVG icon */
        }
        .header .notification-icon::after {
            content: attr(data-count);
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
        }
        .announcement {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .announcement h2 {
            margin-top: 0;
        }
        .announcement .date {
            font-size: 0.9em;
            color: #555;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Resident Portal</h1>
        <div class="notification-icon" data-count="<?php echo $result->num_rows; ?>">
            <!-- SVG Notification Bell Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2C10.9 2 10 2.9 10 4V5C10 6.1 10.9 7 12 7S14 6.1 14 5V4C14 2.9 13.1 2 12 2ZM19 16V11.6C19 9.3 17.8 7.3 15.8 6.1V5C15.8 3.1 14 1.5 12 1.2C11 1.1 10.1 1.5 9.5 2.3C8.9 3.1 8.3 4.4 8.3 5.5V6.1C6.3 7.3 5 9.3 5 11.6V16L4 17V18H20V17L19 16ZM12 20C10.3 20 8.9 18.6 8.9 17H15.1C15.1 18.6 13.7 20 12 20Z"/>
            </svg>
        </div>
    </div>

    <div class="container">
        <h1>Announcements</h1>

        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<div class='announcement'>";
                echo "<h2>" . $row["title"] . "</h2>";
                echo "<div class='date'>" . $row["date"] . "</div>";
                echo "<p>" . $row["content"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "No announcements found.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
