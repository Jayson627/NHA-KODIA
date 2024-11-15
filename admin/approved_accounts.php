<?php
// Include PHPMailer
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");

include_once('connection.php'); // Include your database connection
$message = ""; 
// Approve or update status of residents
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    $newStatus = ($action === 'approve') ? 'approved' : 'pending';

    $stmt = $conn->prepare("UPDATE residents SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);

    if ($stmt->execute()) {
        $message = "Account status updated successfully!"; 

        // Fetch the resident's email and name
        $stmt = $conn->prepare("SELECT email, fullname FROM residents WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($email, $fullname);
        $stmt->fetch();

        // Send approval email
        if ($email) {
            sendApprovalEmail($email, $fullname);
        }

    } else {
        $message = "Error updating status: " . $stmt->error; // Set error message
    }

    $stmt->close();
}

// Retrieve all pending residents
$sql = "SELECT id, fullname, dob, lot_no, house_no, email, username, created_at, status, role 
        FROM residents 
        WHERE status = 'pending'";
$result = $conn->query($sql);

// Function to send email notification
function sendApprovalEmail($toEmail, $fullname) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'alcantarajayson118@gmail.com'; // Your Gmail address
        $mail->Password = 'xbzybthzvcrfmqmy'; // Your Gmail app password (if 2FA enabled)
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
        $mail->Port = 587;  // Port for TLS

        // Recipients
        $mail->setFrom('alcantarajayson118@gmail.com', 'NHA KODIA');
        $mail->addAddress($toEmail, $fullname); // Add recipient email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Approval Notification';
        $mail->Body    = "<h3>Hello $fullname,</h3><p>Your account has been approved. You can now access your account.</p><p>Thank you for your patience.</p>";
        
        // Send email
        if ($mail->send()) {
            echo 'Approval email sent.';
        } else {
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>

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
            background-color: lightgreen;
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
        
        /* Responsive Design for Small Screens */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }

            .action-button {
                padding: 5px 8px;
                font-size: 12px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            table thead {
                display: none; /* Hide the header row */
            }

            table tr {
                display: block;
                margin-bottom: 10px;
            }

            table td {
                display: block;
                text-align: right;
                font-size: 14px;
                position: relative;
                padding-left: 50%; /* Create space for labels */
            }

            table td::before {
                content: attr(data-label); /* Show column label before data */
                position: absolute;
                left: 10px;
                font-weight: bold;
            }

            td[data-label="Full Name"] { padding-left: 20px; }
            td[data-label="Date of Birth"] { padding-left: 20px; }
            td[data-label="Lot No"] { padding-left: 20px; }
            td[data-label="House No"] { padding-left: 20px; }
            td[data-label="Email"] { padding-left: 20px; }
            td[data-label="Username"] { padding-left: 20px; }
            td[data-label="Created At"] { padding-left: 20px; }
            td[data-label="Status"] { padding-left: 20px; }
            td[data-label="Role"] { padding-left: 20px; }

            td:last-child {
                text-align: center;
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
            <th>Created At</th>
            <th>Status</th>
            <th>Role</th> <!-- Added Role Column -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data for each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td data-label='Full Name'>{$row['fullname']}</td>
                        <td data-label='Date of Birth'>{$row['dob']}</td>
                        <td data-label='Lot No'>{$row['lot_no']}</td>
                        <td data-label='House No'>{$row['house_no']}</td>
                        <td data-label='Email'>{$row['email']}</td>
                        <td data-label='Created At'>{$row['created_at']}</td>
                        <td data-label='Status'>{$row['status']}</td>
                        <td data-label='Role'>{$row['role']}</td> <!-- Displaying the Role -->
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='action' value='approve' class='action-button'>Approve</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No pending accounts found.</td>"; // Updated colspan
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
