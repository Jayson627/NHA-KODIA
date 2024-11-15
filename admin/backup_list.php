<?php
include_once('connection.php'); 

// Fetch backup data (Modify the query to select all relevant columns)
$backup_query = $conn->query("SELECT * FROM household_backup ORDER BY backup_date DESC");

// Display the backup data
if ($backup_query->num_rows > 0) {
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-hover table-bordered shadow-sm rounded">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th>Full Name</th>';
    echo '<th>Gender</th>';
    echo '<th>Birthday</th>';
    echo '<th>Age</th>';
    echo '<th>House no</th>';
    echo '<th>Block no</th>';
    echo '<th>Lot</th>';
    echo '<th>Spouse Name</th>';
    echo '<th>Spouse Age</th>';
    echo '<th>Spouse Birthday</th>';
    echo '<th>Contact</th>';
    echo '<th>Status</th>';
    echo '<th>Barangay</th>';
    echo '<th>Remarks</th>';
    echo '<th>Backup Date</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    while ($row = $backup_query->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
        echo '<td>' . date("M d, Y", strtotime($row['dob'])) . '</td>';
        echo '<td>' . htmlspecialchars($row['owner_age']) . '</td>';
        echo '<td>' . htmlspecialchars($row['roll']) . '</td>';
        echo '<td>' . htmlspecialchars($row['block_no']) . '</td>';
        echo '<td>' . htmlspecialchars($row['lot_no']) . '</td>';
        echo '<td>' . htmlspecialchars($row['spouse_fullname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['spouse_age']) . '</td>';
        echo '<td>' . date("M d, Y", strtotime($row['spouse_dob'])) . '</td>';
        echo '<td>' . htmlspecialchars($row['contact']) . '</td>';
        echo '<td>' . ($row['status'] == 1 ? 'Active' : 'Inactive') . '</td>';
        echo '<td>' . htmlspecialchars($row['present_address']) . '</td>';
        echo '<td>' . htmlspecialchars($row['permanent_address']) . '</td>';
        echo '<td>' . date("M d, Y H:i:s", strtotime($row['backup_date'])) . '</td>';

        // Add the restore button
        echo '<td>';
        echo '<form method="POST" action="restore_backup.php">';
        echo '<input type="hidden" name="backup_id" value="' . $row['id'] . '">';
        echo '<button type="submit" class="btn btn-success">Restore</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p class="alert alert-warning">No backup data available.</p>';
}

$conn->close();
?>

<style>
    /* Custom styling for the table */
    .table {
        margin: 30px 0;
        font-size: 16px;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 12px;
        text-align: left;
        vertical-align: middle;
    }

    .table th {
        background-color: #007bff;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table td {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
    }

    .table tr:hover {
        background-color: #f1f1f1;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .thead-dark {
        background-color: #343a40;
    }

    .thead-dark th {
        color: #fff;
    }

    .alert {
        padding: 15px;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
    }

    .shadow-sm {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .rounded {
        border-radius: 10px;
    }
</style>
