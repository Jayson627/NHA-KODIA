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
            echo "<div class='alert alert-success text-center'>Incident deleted successfully</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error deleting incident: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger text-center'>Error preparing statement: " . $conn->error . "</div>";
    }
    
    // Refresh the page to update the list
   
}

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
        function confirmDelete() {
            return confirm("Are you sure you want to delete this incident?");
        }
    </script>
</head>
<body>
<div class="container">
    <h2 class="text-center">Incident Reports</h2>
    <table class="table table-bordered">
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
                            <form action='' method='post' onsubmit='return confirmDelete();'>
                                <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                <button type='submit' class='btn btn-danger'>Delete</button>
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
