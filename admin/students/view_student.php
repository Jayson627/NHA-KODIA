<?php
// Query to retrieve household and spouse full names with extensions
$qry = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename, ' ', owner_extension) as fullname, CONCAT(spouse_lastname, ', ', spouse_firstname, ' ', spouse_middlename, ' ', spouse_extension) as spouse_fullname FROM student_list WHERE id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    $res = $qry->fetch_array();
    foreach ($res as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v;
        }
    }
}

?>
<?php
// Database connection (already established at the top of your script)
$household_id = isset($_GET['id']) ? $_GET['id'] : '';
if (isset($_GET['delete_household']) && !empty($household_id)) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // First, back up the owner’s details
        $backup_stmt = $conn->prepare("
            INSERT INTO household_backup (firstname, middlename, lastname,  roll,  owner_age, owner_extension, gender, dob, contact, block_no, lot_no, present_address, permanent_address, spouse_fullname, spouse_age, spouse_dob, status)
            SELECT firstname, middlename, lastname, roll,  owner_age, owner_extension, gender, dob, contact, block_no, lot_no, present_address, permanent_address, CONCAT(spouse_lastname, ', ', spouse_firstname, ' ', spouse_middlename) AS spouse_fullname, spouse_age, spouse_dob, status
            FROM student_list WHERE id = ?");
        $backup_stmt->bind_param("i", $household_id);
        $backup_stmt->execute();
        $backup_stmt->close();

        // Delete children associated with the household
        $stmt = $conn->prepare("DELETE FROM children WHERE child_id = ?");
        $stmt->bind_param("i", $household_id);
        $stmt->execute();
        $stmt->close();

        // Delete from the student_list (owner and household info)
        $stmt = $conn->prepare("DELETE FROM student_list WHERE id = ?");
        $stmt->bind_param("i", $household_id);
        $stmt->execute();
        $stmt->close();

        // If everything is successful, commit the transaction
        $conn->commit();

        // Redirect back to the list page after deletion
        header("Location: ./?page=students");
        exit();
    } catch (Exception $e) {
        // If there was an error, rollback the transaction
        $conn->rollback();
        echo "Error deleting household: " . $e->getMessage();
    }
}

?>
<div class="content py-4">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title">Household Details</h5>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary btn-flat" href="./?page=students/manage_student&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
                <button class="btn btn-sm btn-danger btn-flat" type="button" id="delete_household"><i class="fa fa-trash"></i> Delete Household</button>
                <button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="update_status">Update Status</button>
                <a href="./?page=students" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
                <a href="children.php?id=<?php echo $_GET['id']; ?>" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add Children</a>
                
                <!-- New Print Button -->
                <button class="btn btn-sm btn-secondary btn-flat" onclick="printPage()"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                
                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="2">Owner Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td><?= isset($fullname) ? $fullname : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Sex</td>
                                <td><?= isset($gender) ? $gender : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td><?= isset($dob) ? date("M d, Y", strtotime($dob)) : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Age</td>
                                <td><?= isset($owner_age) ? $owner_age : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Contact</td>
                                <td><?= isset($contact) ? $contact : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Block No.</td>
                                <td><?= isset($block_no) ? $block_no : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Lot No.</td>
                                <td><?= isset($lot_no) ? $lot_no : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Barangay</td>
                                <td><?= isset($present_address) ? $present_address : 'N/A' ?></td>
                            </tr>
                            <tr>
                            <tr>
                                <td>Household No.</td>
                                <td><?= isset($roll) ? $roll : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <?php 
                                        switch ($status) {
                                            case 0:
                                                echo '<span class="badge badge-secondary">Inactive</span>';
                                                break;
                                            case 1:
                                                echo '<span class="badge badge-primary">Active</span>';
                                                break;
                                        }
                                    ?>
                                </td>
                            </tr>
                                <td>Permanent Address</td>
                                <td><?= isset($permanent_address) ? $permanent_address : 'N/A' ?></td>
                            </tr>
                       
                        <tr>
                            <td>Spouse Name</td>
                            <td><?= isset($spouse_fullname) ? $spouse_fullname : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <td>Spouse Age</td>
                            <td><?= isset($spouse_age) ? $spouse_age : 'N/A' ?></td>
                        </tr>
                        <tr>
                            <td>Spouse Date of Birth</td>
                            <td><?= isset($spouse_dob) ? $spouse_dob : 'N/A' ?></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</div>

            <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number
$start = ($page - 1) * $limit;

// Handle form submission for adding a new child
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_child'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];
    $birthdate = $_POST['birthdate'];
    $educational_attainment = $_POST['educational_attainment'];
    $contact_number = $_POST['contact_number'];
    $contact_number = $_POST['remark'];

        // Insert new child record
        $stmt = $conn->prepare("INSERT INTO children (name, age, gender, status, birthdate, educational_attainment, contact_number remark) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssss", $name, $age, $gender, $status, $birthdate, $educational_attainment, $contact_number);
        if ($stmt->execute()) {
            $error_message = "Success: Record has been added!";
        } else {
            $error_message = "Error: Could not save the record. Please try again.";
        }
        $stmt->close();
    }

// Handle delete action
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM children WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
    $stmt->close();
    // Redirect to avoid resubmission
    header("Location: children.php");
    exit();
}

// Retrieve children for current page
$children = [];
$result = $conn->query("SELECT * FROM children WHERE child_id = '{$_GET['id']}' LIMIT $start, $limit");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $children[] = $row;
    }
}

// Count total number of records
$total_records = $conn->query("SELECT COUNT(*) AS total FROM children")->fetch_assoc()['total'];

$conn->close();
?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Children Information</h2>

    <table class="table table-striped table-hover table-bordered shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>Full Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Birthdate</th>
                <th>Educational Attainment</th>
                <th>Contact Number</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($children)): ?>
                <?php foreach ($children as $child): ?>
                    <tr>
                        <td>
                            <?php 
                            $fullName = htmlspecialchars($child['first_name']) . ' ' . 
                                        htmlspecialchars($child['middle_name']) . ' ' . 
                                        htmlspecialchars($child['last_name']);
                            if (!empty($child['extension_name'])) {
                                $fullName .= ' ' . htmlspecialchars($child['extension_name']);
                            }
                            echo $fullName; 
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($child['age']); ?></td>
                        <td><?php echo htmlspecialchars($child['gender']); ?></td>
                        <td><?php echo htmlspecialchars($child['status']); ?></td>
                        <td><?php echo htmlspecialchars($child['birthdate']); ?></td>
                        <td><?php echo htmlspecialchars($child['educational_attainment']); ?></td>
                        <td><?php echo htmlspecialchars($child['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($child['remark']); ?></td>
                        <td>
                            <a href="view_child.php?id=<?php echo urlencode($child['id']); ?>" class="btn btn-info btn-sm">View</a>
                            <a href="edit_child.php?id=<?php echo urlencode($child['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No children found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Optional CSS Styles for Additional Customization -->
<style>
    .table th, .table td {
        vertical-align: middle;
    }

    .table th {
        text-align: center;
        font-weight: bold;
    }

    .table td {
        text-align: center;
    }

    .table-primary {
        background-color: #007bff;
        color: white;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table-hover tbody tr:hover {
        background-color: ;
    }

    .btn {
        margin: 0 5px;
    }

    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-family: 'Arial', sans-serif;
        color: #333;
    }
</style>

   
<script>
    function printPage() {
        var originalContent = document.body.innerHTML;
        var printContent = `
            <html>
            <head>
                <title>Print</title>
                <style>
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <h1>Household Details</h1>
                <div>
                    <p><strong>Name:</strong> <?= isset($fullname) ? $fullname : 'N/A' ?></p>
                    <p><strong>Contact No.:</strong> <?= isset($roll) ? $roll : 'N/A' ?></p>
                    <p><strong>Status:</strong> 
                        <?php 
                            switch ($status) {
                                case 0:
                                    echo 'Inactive';
                                    break;
                                case 1:
                                    echo 'Active';
                                    break;
                            }
                        ?>
                    </p>
                    <!-- Add more fields as needed -->
                </div>
                <h2>Children Information</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Birthdate</th>
                            <th>Educational Attainment</th>
                            <th>Contact Number</th>
                             <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($children)): ?>
                            <?php foreach ($children as $child): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($child['name']); ?></td>
                                    <td><?php echo htmlspecialchars($child['age']); ?></td>
                                    <td><?php echo htmlspecialchars($child['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($child['status']); ?></td>
                                    <td><?php echo htmlspecialchars($child['birthdate']); ?></td>
                                    <td><?php echo htmlspecialchars($child['educational_attainment']); ?></td>
                                    <td><?php echo htmlspecialchars($child['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($child['remark']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No children found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </body>
            </html>
        `;
        
        var newWindow = window.open('', '', 'height=600,width=800');
        newWindow.document.write(printContent);
        newWindow.document.close();
        newWindow.focus();
        newWindow.print();
    }
</script>

<script>
    $(function() {
        $('#update_status').click(function(){
            uni_modal("Update Status of <b><?= isset($roll) ? $roll : "" ?></b>", "students/update_status.php?student_id=<?= isset($id) ? $id : "" ?>");
        });
        $('#add_academic').click(function(){
            uni_modal("Add Academic Record for <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>", "students/manage_academic.php?student_id=<?= isset($id) ? $id : "" ?>", 'mid-large');
        });
        $('.edit_academic').click(function(){
            uni_modal("Edit Academic Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>", "students/manage_academic.php?student_id=<?= isset($id) ? $id : "" ?>&id=" + $(this).attr('data-id'), 'mid-large');
        });
        $('.delete_academic').click(function(){
            _conf("Are you sure to delete this Student's Academic Record?", "delete_academic", [$(this).attr('data-id')]);
        });
       
        $('.view_data').click(function(){
            uni_modal("Report Details", "students/view_report.php?id=" + $(this).attr('data-id'), "mid-large");
        });
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        $('.table').DataTable({
            lengthChange: false, // Disable the "Show entries" dropdown
            paging: false, // Disable pagination
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
    });

    $(function() {
    // Delete Household button click event
    $('#delete_household').click(function(){
        // SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will permanently delete this household and all associated information. This cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion
                $.ajax({
                    url: './?page=students/view_student&id=<?= isset($id) ? $id : "" ?>',
                    method: 'GET',
                    data: { delete_household: 1, id: <?= isset($id) ? $id : "''" ?> },
                    success: function(response) {
                        // Show success alert and redirect to student list
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The household has been deleted successfully.',
                            icon: 'success'
                        }).then(function() {
                            window.location.href = './?page=students'; // Redirect to the student list page after deletion
                        });
                    },
                    error: function(xhr, status, error) {
                        // Show error alert
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the household.',
                            icon: 'error'
                        });
                    }
                });
            } else {
                // If user cancels the action, show a cancel alert
                Swal.fire({
                    title: 'Cancelled',
                    text: 'The household was not deleted.',
                    icon: 'info'
                });
            }
        });
    });
});
   

    function delete_academic($id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_academic",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }
    

</script> 