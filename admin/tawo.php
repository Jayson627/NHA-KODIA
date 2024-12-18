<?php
// Connect to the database
$host = 'localhost';
$db = 'your_database_name'; // Replace with your actual database name
$user = 'your_database_user'; // Replace with your database user
$pass = 'your_database_password'; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle account approval
if (isset($_POST['approve_user_id'])) {
    $userId = $_POST['approve_user_id'];
    // Update user status to 'approved'
    $stmt = $pdo->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    $stmt->execute([$userId]);
    echo '<script>alert("Account approved successfully!");</script>';
}

// Fetch users with 'pending' status
$stmt = $pdo->prepare("SELECT * FROM users WHERE status = 'pending'");
$stmt->execute();
$users = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
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
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['fullname']) ?></td>
                    <td><?= htmlspecialchars($user['dob']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="approve_user_id" value="<?= $user['id'] ?>">
                            <button type="submit">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Show success message when account is approved
        <?php if (isset($_POST['approve_user_id'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Account Approved',
                text: 'The account has been successfully approved.',
            });
        <?php endif; ?>
    </script>

</body>
</html>hhh
