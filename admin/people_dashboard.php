<?php
session_start();

if (!isset($_SESSION['president_name'])) {
    header("Location: president.php");
    exit();
}
include_once('connection.php'); 

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
            background-image: url('nha.jpg'); /* Path to your background image */
            background-size: cover; /* Cover the entire viewport */
            background-repeat: no-repeat; /* Prevent repeating the image */
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007BFF;
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
            margin-left: auto;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
            display: none; /* Hide announcements by default */
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
            display: none;
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
            margin: 10% auto;
            padding: 15px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .modal-content h2 {
            text-align: center;
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
            background-color: blue;
        }
        .report-btn, .message-btn, .announcement-btn {
            color: white;
            background-color: transparent;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-left: 15px;
        }
        .report-btn:hover, .message-btn:hover, .announcement-btn:hover {
            background-color: blue;
        }
        .form-group {
            margin-bottom: 10px; 
            text-align: center;
        }
        .form-group label {
            display: block;
            margin-bottom: 2px; 
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: calc(100% - 10px);
            max-width: 250px; 
            padding: 5px; 
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            resize: vertical;
            height: 80px; 
        }
        .submit-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 15px; 
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: block;
            margin: 10px auto;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .president-name {
            color: #fff;
            margin-left: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="president-name">
        Welcome, <?php echo htmlspecialchars($_SESSION['president_name']); ?>!
    </div>
    <div class="icons">
        <button class="announcement-btn" onclick="toggleAnnouncements()">Announcements</button>
        <button class="report-btn" onclick="openIncidentModal()">Report</button>
        <button class="message-btn" onclick="openMessageModal()">Message</button>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="container" id="announcements">
    <h1>Announcements</h1>

    <?php
    if ($result->num_rows > 0) {
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
    ?>
</div>

<!-- The Incident Modal -->
<div id="incidentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeIncidentModal()">&times;</span>
        <h2>Report an Incident</h2>
        <form action="submit_incident.php" method="POST">
            <div class="form-group">
                <label for="incident_date">Incident Date:</label>
                <input type="date" id="incident_date" name="incident_date" required>
            </div>
            <div class="form-group">
                <label for="incident_description">Description:</label>
                <textarea id="incident_description" name="incident_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="reported_by">Reported By:</label>
                <input type="text" id="reported_by" name="reported_by" required>
            </div>
            <div style="text-align: center;">
                <input type="submit" class="submit-btn" value="Submit Report">
            </div>
        </form>
    </div>
</div>

<!-- The Message Modal -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeMessageModal()">&times;</span>
        <h2>Send a Message</h2>
        <form action="submit_message.php" method="POST">
            <div class="form-group">
                <label for="lot_no">Lot No.:</label>
                <input type="text" id="lot_no" name="lot_no" required>
            </div>
            <div class="form-group">
                <label for="block_no">Block No.:</label>
                <input type="text" id="block_no" name="block_no" required>
            </div>
            <div class="form-group">
                <label for="sender_name">Sender Name:</label>
                <input type="text" id="sender_name" name="sender_name" required>
            </div>
            <div class="form-group">
                <label for="message_content">Message:</label>
                <textarea id="message_content" name="message_content" required></textarea>
            </div>
            <div style="text-align: center;">
                <input type="submit" class="submit-btn" value="Send Message">
            </div>
        </form>
    </div>
</div>

<script>
    function toggleAnnouncements() {
        const announcements = document.getElementById('announcements');
        announcements.style.display = announcements.style.display === 'none' || announcements.style.display === '' ? 'block' : 'none';
    }

    function openIncidentModal() {
        document.getElementById('incidentModal').style.display = 'block';
    }

    function closeIncidentModal() {
        document.getElementById('incidentModal').style.display = 'none';
    }

    function openMessageModal() {
        document.getElementById('messageModal').style.display = 'block';
    }

    function closeMessageModal() {
        document.getElementById('messageModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('incidentModal')) {
            closeIncidentModal();
        } else if (event.target == document.getElementById('messageModal')) {
            closeMessageModal();
        }
    }
</script>
</body>
</html>
