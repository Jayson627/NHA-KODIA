<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #5a67d8;
            text-align: center;
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
            background-color: #5a67d8;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-button {
            background-color: #5a67d8;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .action-button:hover {
            background-color: #4c51bf;
        }
        
        /* Responsive styling for mobile view */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            thead tr {
                display: none; /* Hide header row */
            }
            tr {
                margin-bottom: 10px;
                border-bottom: 2px solid #ddd;
            }
            td {
                display: flex;
                justify-content: space-between;
                padding: 8px;
                border: none;
                border-bottom: 1px solid #ddd;
                position: relative;
            }
            td::before {
                content: attr(data-label); /* Use the data-label attribute to label the columns */
                font-weight: bold;
                color: #5a67d8;
                flex: 0 0 50%;
            }
            .action-button {
                width: 100%;
                margin-top: 8px;
            }
        }
    </style>
</head>
<body>

<h2>Pending Accounts</h2>

<?php if ($message): ?>
    <script>
        swal({
            title: "<?php echo $message; ?>",
            icon: "<?php echo (strpos($message, 'Error') === false) ? 'success' : 'error'; ?>",
            buttons: {
                cancel: "Cancel",
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "confirm-button",
                    closeModal: true
                }
            },
            dangerMode: true,
        }).then((willConfirm) => {
            if (willConfirm) {
                console.log("Confirmed!");
            } else {
                console.log("Cancelled!");
            }
        });
    </script>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Date of Birth</th>
            <th>Lot No</th>
            <th>House No</th>
            <th>Email</th>
            <th>Username</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td data-label='Full Name'>{$row['fullname']}</td>
                        <td data-label='Date of Birth'>{$row['dob']}</td>
                        <td data-label='Lot No'>{$row['lot_no']}</td>
                        <td data-label='House No'>{$row['house_no']}</td>
                        <td data-label='Email'>{$row['email']}</td>
                        <td data-label='Username'>{$row['username']}</td>
                        <td data-label='Created At'>{$row['created_at']}</td>
                        <td data-label='Status'>{$row['status']}</td>
                        <td data-label='Role'>{$row['role']}</td>
                        <td data-label='Action'>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='action' value='approve' class='action-button'>Approve</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No pending accounts found.</td>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
