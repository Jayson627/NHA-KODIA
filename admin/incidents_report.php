<?php
include_once('connection.php'); 
// Start output buffering to prevent header issues
if (!headers_sent()) {
    ob_start();
}


// Handle "Resolve" action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {
    $resolveId = (int) $_POST['resolve_id'];
    try {
        $stmt = $pdo->prepare("UPDATE incidents SET resolved = 1 WHERE id = :id");
        $stmt->execute([':id' => $resolveId]);
    } catch (PDOException $e) {
        die("Failed to resolve incident: " . $e->getMessage());
    }
    // Ensure no output before header
    if (!headers_sent()) {
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
        exit;
    }
}

// Fetch unresolved incidents from the database
try {
    $stmt = $pdo->query("SELECT id, incident_type, description, incident_date FROM incidents WHERE resolved = 0 ORDER BY incident_date DESC");
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #4A90E2;
            font-size: 32px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
            display: block;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
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
            font-size: 18px;
            margin-top: 20px;
        }

        .btn-resolve {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            width: 100%; /* Make the button fill the cell */
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            table th, table td {
                padding: 12px;
                font-size: 14px;
            }

            .btn-resolve {
                padding: 6px 12px;
                font-size: 12px;
                width: auto; /* Resize button width */
            }
        }

        @media (max-width: 480px) {
            table th, table td {
                padding: 10px;
                font-size: 12px;
            }

            .btn-resolve {
                padding: 5px 10px;
                font-size: 10px;
                width: auto;
            }

            h1 {
                font-size: 24px;
            }

            .container {
                padding: 15px;
            }

            .btn-resolve {
                padding: 5px 10px;
                font-size: 12px;
            }

            /* Form in the table cell */
            form {
                width: 100%; /* Ensure form uses full width */
                display: flex;
                justify-content: center; /* Center the button */
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
                                <form method="POST" style="margin: 0;">
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
