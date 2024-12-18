<?php
// Database credentials
$servername = "127.0.0.1:3306";
$username = "u510162695_sis_db";
$password = "1Sis_dbpassword";
$dbname = "u510162695_sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message variable
$error_message = "";

// Handle account approval
if (isset($_POST['approve_resident_id'])) {
    $residentId = $_POST['approve_resident_id'];

    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare("UPDATE residents SET status = 'approved' WHERE id = ?")) {
        // Bind parameters and execute the statement
        $stmt->bind_param("i", $residentId);
        if ($stmt->execute()) {
            $success_message = "Account approved successfully!";
        } else {
            $error_message = "Error approving account.";
        }
        $stmt->close();
    } else {
        $error_message = "Failed to prepare the statement.";
    }
}

// Fetch residents with 'pending' status
$sql = "SELECT * FROM residents WHERE status = 'pending'";
$result = $conn->query($sql);

if ($result === false) {
    $error_message = "Error fetching data: " . $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approval</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h1>Account Approval</h1>
    <p>Below are the accounts awaiting approval. Click "Approve" to approve the account.</p>

    <?php if (!empty($error_message)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error_message; ?>'
            });
        </script>
    <?php elseif (isset($success_message)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $success_message; ?>'
            });
        </script>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($resident = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($resident['id']) ?></td>
                        <td><?= htmlspecialchars($resident['fullname']) ?></td>
                        <td><?= htmlspecialchars($resident['dob']) ?></td>
                        <td><?= htmlspecialchars($resident['email']) ?></td>
                        <td><?= htmlspecialchars($resident['role']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="approve_resident_id" value="<?= $resident['id'] ?>">
                                <button type="submit">Approve</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No accounts awaiting approval.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
