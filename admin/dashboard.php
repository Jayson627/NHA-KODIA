<?php
session_start();

if (!isset($_SESSION['president_name'])) {
    header("Location: president.php");
    exit();
}


?>


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

// Initialize error message variable
$error_message = "";

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
            color: #ffffff;
        }
        .header .icons {
            display: flex;
            align-items: center;
        }
        .header .notification-icon,
        .header .message-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-left: 15px;
        }
        .header .notification-icon svg,
        .header .message-icon svg {
            fill: #fff; /* White color for SVG icons */
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
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .logout-btn {
    display: flex;
    align-items: center;
    color: white;
    background-color: light;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.logout-btn i {
    margin-right: 8px;
    font-size: 18px;
}

.logout-btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="header">
  
    <h1> <?php echo htmlspecialchars($_SESSION['president_name']); ?></h1>

        <div class="icons">
        <a href="logout.php" class="logout-btn">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>

            <div class="notification-icon" data-count="<?php echo $result->num_rows; ?>">
                <!-- SVG Notification Bell Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C10.9 2 10 2.9 10 4V5C10 6.1 10.9 7 12 7S14 6.1 14 5V4C14 2.9 13.1 2 12 2ZM19 16V11.6C19 9.3 17.8 7.3 15.8 6.1V5C15.8 3.1 14 1.5 12 1.2C11 1.1 10.1 1.5 9.5 2.3C8.9 3.1 8.3 4.4 8.3 5.5V6.1C6.3 7.3 5 9.3 5 11.6V16L4 17V18H20V17L19 16ZM12 20C10.3 20 8.9 18.6 8.9 17H15.1C15.1 18.6 13.7 20 12 20Z"/>
                </svg>
            </div>
            <div class="message-icon" onclick="openIncidentModal()">
    <!-- SVG Incident Report Icon (Exclamation Mark inside Triangle) -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24px" height="24px">
        <path d="M1 21h22L12 2 1 21zm13-2h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
    </svg>
</div>



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

  <!-- The Modal -->
<div id="incidentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeIncidentModal()">&times;</span>
        <h2>Report an Incident</h2>
        <form action="submit_incident.php" method="POST">
            <label for="incident_date">Incident Date:</label>
            <input type="date" id="incident_date" name="incident_date" required>
            <br><br>

            <label for="incident_description">Description:</label>
            <textarea id="incident_description" name="incident_description" rows="4" required></textarea>
            <br><br>

            <label for="reported_by">Reported By:</label>
            <input type="text" id="reported_by" name="reported_by" required>
            <br><br>

            

            <input type="submit" value="Submit Report">
        </form>
    </div>
</div>


    <script>
    function openIncidentModal() {
        document.getElementById('incidentModal').style.display = 'block';
    }

    function closeIncidentModal() {
        document.getElementById('incidentModal').style.display = 'none';
    }

    // Close the modal if the user clicks anywhere outside of the modal
    window.onclick = function(event) {
        if (event.target == document.getElementById('incidentModal')) {
            document.getElementById('incidentModal').style.display = 'none';
        }
    }
    </script>
</body>
</html>
