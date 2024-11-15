<?php
include_once('connection.php');

// Check if the backup ID is provided
if (isset($_POST['backup_id'])) {
    $backup_id = $_POST['backup_id'];

    // Fetch the backup data based on the provided ID
    $backup_query = $conn->query("SELECT * FROM household_backup WHERE id = $backup_id");

    if ($backup_query->num_rows > 0) {
        $row = $backup_query->fetch_assoc();

        // Split spouse_fullname into first name, middle name, and last name
        $spouse_fullname = $row['spouse_fullname'];
        $spouse_name_parts = explode(" ", $spouse_fullname);

        // Handle spouse_firstname, spouse_middlename, and spouse_lastname
        $spouse_firstname = !empty($spouse_name_parts[0]) ? $conn->real_escape_string($spouse_name_parts[0]) : '';
        $spouse_middlename = isset($spouse_name_parts[1]) ? $conn->real_escape_string($spouse_name_parts[1]) : '';
        $spouse_lastname = isset($spouse_name_parts[2]) ? $conn->real_escape_string($spouse_name_parts[2]) : '';

        // Prepare the SQL query to restore data into the student_list table
        $insert_query = "INSERT INTO student_list (
            firstname, middlename, lastname, gender, dob, owner_age, roll, block_no, lot_no, spouse_firstname, spouse_middlename, spouse_lastname, spouse_age, spouse_dob, 
            contact, status, present_address, permanent_address
        ) VALUES (
            '" . $conn->real_escape_string($row['firstname']) . "',
            '" . $conn->real_escape_string($row['middlename']) . "',
            '" . $conn->real_escape_string($row['lastname']) . "',
            '" . $conn->real_escape_string($row['gender']) . "',
            '" . $conn->real_escape_string($row['dob']) . "',
            '" . $conn->real_escape_string($row['owner_age']) . "',
            '" . $conn->real_escape_string($row['roll']) . "',
            '" . $conn->real_escape_string($row['block_no']) . "',
            '" . $conn->real_escape_string($row['lot_no']) . "',
            '$spouse_firstname',
            '$spouse_middlename',
            '$spouse_lastname',
            '" . $conn->real_escape_string($row['spouse_age']) . "',
            '" . $conn->real_escape_string($row['spouse_dob']) . "',
            '" . $conn->real_escape_string($row['contact']) . "',
            '" . $conn->real_escape_string($row['status']) . "',
            '" . $conn->real_escape_string($row['present_address']) . "',
            '" . $conn->real_escape_string($row['permanent_address']) . "'
        )";

        // Perform the insertion
        if ($conn->query($insert_query) === TRUE) {
            // After inserting, delete the backup record to avoid showing it again
            $delete_query = "DELETE FROM household_backup WHERE id = $backup_id";
            if ($conn->query($delete_query) === TRUE) {
                // Redirect back to the students page after successful restoration
                header("Location: ./?page=students");
                exit;
            } else {
                echo "Error deleting backup record: " . $conn->error;
            }
        } else {
            echo "Error restoring data: " . $conn->error;
        }
    } else {
        echo "Backup record not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();

?>
