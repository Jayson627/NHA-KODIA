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

// Function to handle form submissions
function handleFormSubmission($conn) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'create') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $date = date('Y-m-d H:i:s'); // Current date and time

            $sql = "INSERT INTO announcements (title, content, date) VALUES ('$title', '$content', '$date')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Announcement created successfully.'];
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        } elseif ($action == 'update') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            $sql = "UPDATE announcements SET title='$title', content='$content' WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Announcement updated successfully.'];
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        } elseif ($action == 'delete') {
            $id = $_POST['id'];

            $sql = "DELETE FROM announcements WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Announcement deleted successfully.'];
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error: ' . $conn->error];
            }
        }
    }
}

// Handle form submission
handleFormSubmission($conn);

// Fetch announcements from the database
$sql = "SELECT id, title, content, date FROM announcements ORDER BY date DESC";
$result = $conn->query($sql);

// Prepare the alert message for JavaScript
$alertMessage = '';
if (isset($_SESSION['alert'])) {
    $alertType = $_SESSION['alert']['type'] == 'success' ? 'alert-success' : 'alert-error';
    $alertMessage = $_SESSION['alert']['message'];
    unset($_SESSION['alert']); // Clear the alert message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            overflow-wrap: break-word;
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
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
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .button-delete {
            background: #dc3545;
            color: #fff;
        }
        .button-delete:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Announcements</h1>

        <?php if ($alertMessage): ?>
            <div class='alert <?php echo $alertType; ?>'><?php echo $alertMessage; ?></div>
            <script>
                setTimeout(function() {
                    document.querySelector('.alert').style.display = 'none';
                }, 3000); // Hide after 3 seconds
            </script>
        <?php endif; ?>

        <!-- Form to create a new announcement -->
        <h2>Create Announcement</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <label>Title:</label>
            <input type="text" name="title" required>
            <label>Content:</label>
            <textarea name="content" required rows="6"></textarea>
            <button type="submit">Create</button>
        </form>

        <hr>

        <!-- List of existing announcements -->
        <h2>Existing Announcements</h2>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<div class='announcement'>";
                echo "<h2>" . $row["title"] . "</h2>";
                echo "<div class='date'>" . $row["date"] . "</div>";

                // Form to update the announcement
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='action' value='update'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<label>Title:</label>";
                echo "<input type='text' name='title' value='" . $row["title"] . "' required>";
                echo "<label>Content:</label>";
                echo "<textarea name='content' required rows='6'>" . $row["content"] . "</textarea>";
                echo "<button type='submit'>Update</button>";
                echo "</form>";

                // Form to delete the announcement
                echo "<form method='POST' action='' onsubmit='return confirm(\"Are you sure you want to delete this announcement?\");'>";
                echo "<input type='hidden' name='action' value='delete'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<button type='submit' class='button-delete'>Delete</button>";
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
