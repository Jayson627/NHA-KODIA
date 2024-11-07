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
<div class="content py-4">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title">Household Details</h5>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary btn-flat" href="./?page=students/manage_student&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
                <button class="btn btn-sm btn-danger btn-flat" id="delete_student"><i class="fa fa-trash"></i> Delete</button>
                <button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="update_status">Update Status</button>
                <a href="./?page=students" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
                <a href="children.php?id=<?php echo $_GET['id']; ?>" class="btn btn-sm btn-primary btn-flatv"><i class="fa fa-plus"></i> Add Children</a>
                
                <!-- New Print Button -->
                <button class="btn btn-sm btn-secondary btn-flat" onclick="printPage()"><i class="fa fa-print"></i> Print</button>
            </div>
       
                </style>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-muted">Household No.</label>
                            <div class="pl-4"><?= isset($roll) ? $roll : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-muted">Status</label>
                            <div class="pl-4">
                                <?php 
                                    switch ($status) {
                                        case 0:
                                            echo '<span class="rounded-pill badge badge-secondary bg-gradient-secondary px-3">Inactive</span>';
                                            break;
                                        case 1:
                                            echo '<span class="rounded-pill badge badge-primary bg-gradient-primary px-3">Active</span>';
                                            break;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <fieldset class="border p-3 mb-3">
                <legend class="w-auto">Owner Details</legend>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label text-muted">Name</label>
                            <div class="pl-4"><?= isset($fullname) ? $fullname : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Sex</label>
                            <div class="pl-4"><?= isset($gender) ? $gender : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Date of Birth</label>
                            <div class="pl-4"><?= isset($dob) ? date("M d, Y", strtotime($dob)) : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Age #</label>
                            <div class="pl-4"><?= isset($owner_age) ? $owner_age : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Contact #</label>
                            <div class="pl-4"><?= isset($contact) ? $contact : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Block #</label>
                            <div class="pl-4"><?= isset($block_no) ? $block_no : 'N/A' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Lot #</label>
                            <div class="pl-4"><?= isset($lot_no) ? $lot_no : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label text-muted">Remarks</label>
                            <div class="pl-4"><?= isset($permanent_address) ? $permanent_address : 'N/A' ?></div>
                        </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label text-muted">Spouse Name</label>
                            <div class="pl-4"><?= isset($spouse_fullname) ? $spouse_fullname : 'N/A' ?></div>
                        </div>
                        <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label text-muted">Spouse Age</label>
                            <div class="pl-4"><?= isset($spouse_age) ? $spouse_age : 'N/A' ?></div>
                        </div>
                        <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label text-muted"> Spouse Date of Birth</label>
                            <div class="pl-4"><?= isset($spouse_dob) ? $spouse_dob : 'N/A' ?></div>
                        </div>
                    </div>
                </div>

                    </div>
                </div>
            </fieldset>

            </div>
            <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connections
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
<h2>Children Information</h2>

<table class="table table-bordered">
    <thead>
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
                <td colspan="8">No children found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

   
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
        $('#delete_student').click(function(){
            _conf("Are you sure to delete this Student Information?", "delete_student", ['<?= isset($id) ? $id : '' ?>']);
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
    

    function delete_student($id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_student",
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
                    location.replace('./?page=students');
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }
</script> 