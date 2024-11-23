<?php
// Start output buffering to prevent header issues
if (!headers_sent()) {
    ob_start();
}
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

// Initialize error message variable
$error_message = "";

// Handle "Resolve" action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {
    $resolveId = (int) $_POST['resolve_id'];
    try {
        $stmt = $conn->prepare("UPDATE incidents SET resolved = 1 WHERE id = ?");
        $stmt->bind_param("i", $resolveId);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        die("Failed to resolve incident: " . $e->getMessage());
    }
    if (!headers_sent()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch unresolved incidents from the database
try {
    $result = $conn->query("SELECT id, incident_type, description, incident_date FROM incidents WHERE resolved = 0 ORDER BY incident_date DESC");
    $incidents = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $incidents[] = $row;
        }
    }
} catch (Exception $e) {
    die("Failed to fetch incidents: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidents Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4A90E2;
            font-size: 28px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            color: #888;
            font-size: 16px;
            margin-top: 20px;
        }

        .btn-resolve {
            padding: 8px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s ease;
            display: block;
            width: 100%;
        }

        .btn-resolve:hover {
            background-color: #218838;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 15px;
                padding: 15px;
            }

            h1 {
                font-size: 24px;
            }

            table th, table td {
                padding: 8px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            table th, table td {
                padding: 6px;
                font-size: 10px;
            }

            h1 {
                font-size: 20px;
            }

            .btn-resolve {
                padding: 6px 8px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Incidents Report</h1>
        <?php if (empty($incidents)): ?>
            <div class="no-data">No incidents recorded yet.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Incident Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidents as $index => $incident): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($incident['incident_type']); ?></td>
                            <td><?= htmlspecialchars($incident['description']); ?></td>
                            <td><?= htmlspecialchars($incident['incident_date']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="resolve_id" value="<?= $incident['id']; ?>">
                                    <button type="submit" class="btn-resolve">Resolve</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <footer>&copy; <?= date("Y"); ?> Incident Management System</footer>
</body>
</html>

<?php
ob_end_flush(); // End output buffering
?>
