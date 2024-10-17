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
        .btn-delete {
            width: 100px;
        }
        .alert {
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
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['incident_date']) . "</td>
                        <td>" . htmlspecialchars($row['description']) . "</td>
                        <td>" . htmlspecialchars($row['reported_by']) . "</td>
                        <td>
                            <form id='delete-form-" . htmlspecialchars($row['id']) . "' action='' method='post' style='display: inline;'>
                                <input type='hidden' name='delete_id' value='" . htmlspecialchars($row['id']) . "'>
                                <button type='button' class='btn btn-danger btn-delete' onclick='confirmDelete(" . htmlspecialchars($row['id']) . ");'>Delete</button>
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
