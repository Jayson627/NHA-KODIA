<?php
include_once('connection.php'); 

// Initialize error message variable
$error_message = "";

// Initialize variables
$announcement = '';
$startDate = '';
$duration = '';
$editAnnouncement = null;
$message = '';
$messageType = '';

// Handle form submission for creating or updating announcements
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values with defaults to avoid undefined index notices
    $announcement = $_POST['announcement'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $duration = $_POST['duration'] ?? '';

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Update existing announcement
        $id = $_POST['id'];
        $endDate = date('Y-m-d', strtotime($startDate . " + $duration days"));

        $stmt = $conn->prepare("UPDATE announcements SET announcement = ?, start_date = ?, end_date = ? WHERE id = ?");
        $stmt->bind_param("sssi", $announcement, $startDate, $endDate, $id);
        
        if ($stmt->execute()) {
            $message = 'Announcement updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    } else {
        // Create new announcement
        $endDate = date('Y-m-d', strtotime($startDate . " + $duration days"));
        $stmt = $conn->prepare("INSERT INTO announcements (announcement, start_date, end_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $announcement, $startDate, $endDate);
        
        if ($stmt->execute()) {
            $message = 'Announcement created successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    }
}

// Fetch announcements
$result = $conn->query("SELECT * FROM announcements WHERE CURDATE() BETWEEN start_date AND end_date");
$announcements = $result->fetch_all(MYSQLI_ASSOC);

// Handle editing an announcement
if (isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editAnnouncement = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        textarea, input[type="date"], input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>Create Announcement</h1>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $editAnnouncement['id'] ?? ''; ?>">
        <label for="announcement">Announcement:</label>
        <textarea id="announcement" name="announcement" required><?php echo htmlspecialchars($editAnnouncement['announcement'] ?? ''); ?></textarea>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($editAnnouncement['start_date'] ?? ''); ?>">

        <label for="duration">Duration (in days):</label>
        <input type="number" id="duration" name="duration" required min="1" value="<?php echo htmlspecialchars((isset($editAnnouncement) && $editAnnouncement['start_date'] && $editAnnouncement['end_date']) ? (strtotime($editAnnouncement['end_date']) - strtotime($editAnnouncement['start_date'])) / (60 * 60 * 24) : ''); ?>">

        <input type="submit" value="<?php echo $editAnnouncement ? 'Update Announcement' : 'Create Announcement'; ?>">
    </form>

    <h2>Current Announcements</h2>
    <table>
        <thead>
            <tr>
                <th>Announcement</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($announcements as $announcement): ?>
                <tr>
                    <td><?php echo htmlspecialchars($announcement['announcement']); ?></td>
                    <td><?php echo htmlspecialchars($announcement['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($announcement['end_date']); ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="edit_id" value="<?php echo $announcement['id']; ?>">
                            <input type="submit" name="edit" value="Edit">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Show SweetAlert messages based on PHP variables
        <?php if ($message): ?>
            swal({
                title: "<?php echo $messageType === 'success' ? 'Success!' : 'Error!'; ?>",
                text: "<?php echo $message; ?>",
                type: "<?php echo $messageType; ?>",
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>

</body>
</html>
