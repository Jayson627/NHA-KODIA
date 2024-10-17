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

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    // SQL to delete a record
    $delete_query = "DELETE FROM incidents WHERE id = ?";
    
    // Prepare and bind
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            $success_message = "Incident deleted successfully.";
        } else {
            $error_message = "Error deleting incident: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $conn->error;
    }
}

// Fetch incidents
$query = "SELECT * FROM incidents";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
    <script>
        function confirmDelete(id) {
            const confirmed = confirm("Are you sure you want to delete this incident?");
            if (confirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2 class="text-center">Incident Reports</h2>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success text-center"><?= $success_message ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger text-center"><?= $error_message ?></div>
    <?php endif; ?>
    
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Incident Date</th>
            <th>Description</th>
            <th>Reported By</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['incident_date'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['reported_by'] . "</td>
                        <td>
                            <form id='delete-form-" . $row['id'] . "' action='' method='post' style='display: inline;'>
                                <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                <button type='button' class='btn btn-danger' onclick='confirmDelete(" . $row['id'] . ");'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>No incidents found</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
