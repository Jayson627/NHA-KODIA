<?php
include_once('connection.php'); 

// Initialize error message variable
$error_message = "";


// Handle incident resolution
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {
    $resolve_id = $_POST['resolve_id'];
    $resolution_notes = isset($_POST['resolution_notes']) ? $_POST['resolution_notes'] : '';
    $assigned_to = isset($_POST['assigned_to']) ? $_POST['assigned_to'] : '';  // This will be the reporter's name or user who resolves it

    // SQL to update the incident status to resolved and add resolution notes
    $resolve_query = "UPDATE incidents SET status = 'resolved', resolution_notes = ?, assigned_to = ? WHERE id = ?";

    // Prepare and bind
    if ($stmt = $conn->prepare($resolve_query)) {
        $stmt->bind_param("ssi", $resolution_notes, $assigned_to, $resolve_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Incident resolved, notes added, and assigned successfully.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error resolving incident: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger text-center'>Error preparing statement: " . $conn->error . "</div>";
    }
}

// Fetch all incident reports
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
        @media (max-width: 576px) {
            .table th, .table td {
                padding: 0.5rem;
                font-size: 14px;
            }
            .table {
                font-size: 12px; /* Adjust table font size for small screens */
            }
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
    <div class="table-responsive"> <!-- Make table responsive -->
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Incident Date</th>
                <th>Incident Type</th>
                <th>Description</th>
                <th>Reported By</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td>
                            <td>" . htmlspecialchars($row['incident_type']) . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                            <td>" . htmlspecialchars($row['reported_by']) . "</td>
                            <td>";

                    // Show status (pending or resolved)
                    if ($row['status'] == 'resolved') {
                        echo "<span class='badge badge-success'>Resolved</span>";
                    } else {
                        echo "<span class='badge badge-warning'>Pending</span>";
                    }

                    echo "</td>
                            <td>";

                    // Show reporter's name in the "Assigned To" column, as they are the ones handling or resolving the incident
                    echo htmlspecialchars($row['reported_by']);

                    echo "</td>
                            <td>";

                    // Show Resolve button if the incident is pending
                    if ($row['status'] === 'pending') {
                        echo "
                        <form action='' method='post'>
                            <input type='hidden' name='resolve_id' value='" . htmlspecialchars($row['id']) . "'>
                            <textarea name='resolution_notes' class='form-control mb-2' placeholder='Enter resolution notes'></textarea>
                            <button type='submit' class='btn btn-success btn-sm'>Resolve</button>
                        </form>";
                    } else {
                        echo "<span class='badge badge-success'>Resolved</span>";
                    }

                    echo "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No incidents found</td></tr>";
            }
            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
