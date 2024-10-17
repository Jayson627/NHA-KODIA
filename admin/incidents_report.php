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
        /* Form styling */
        .form-group {
            max-width: 500px; /* Set max width for form inputs */
            margin: 0 auto; /* Center the form */
        }
        .form-control {
            width: 100%; /* Full width for inputs */
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
