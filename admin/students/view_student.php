<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT *, CONCAT(lastname,', ', firstname,' ', middlename) as fullname FROM `student_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k)) {
                $$k = $v;
            }
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
                <button class="btn btn-sm btn-secondary btn-flat" onclick="printDetails()"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>
        <div id="printableArea">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-muted">Contact No.</label>
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
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-muted">Name</label>
                                <div class="pl-4"><?= isset($fullname) ? $fullname : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Gender</label>
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
                                <label class="control-label text-muted">Block #</label>
                                <div class="pl-4"><?= isset($block) ? $block : 'N/A' ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Lot #</label>
                                <div class="pl-4"><?= isset($lot) ? $lot : 'N/A' ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Barangay</label>
                                <div class="pl-4"><?= isset($present_address) ? $present_address : 'N/A' ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Remarks</label>
                                <div class="pl-4"><?= isset($permanent_address) ? $permanent_address : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>
                </fieldset>
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

        // Insert new child record
        $stmt = $conn->prepare("INSERT INTO children (name, age, gender, status, birthdate, educational_attainment, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
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
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Status</th>
            <th>Birthdate</th>
            <th>Educational Attainment</th>
            <th>Contact Number</th>
            <th>Actions</th>
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
                    <td>
                    <a href="view_child.php?id=<?php echo urlencode($child['id']); ?>" class="btn btn-info btn-sm">View</a>
<a href="edit_child.php?id=<?php echo urlencode($child['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
<a href="children.php?delete=<?php echo urlencode($child['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this child?');">Delete</a>
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
            </div>
        </div>
    </div>
</div>
<script>
    function printDetails() {
        var printContents = document.getElementById('printableArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }


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
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
        $('#print').click(function(){
            start_loader();
            $('#academic-history').dataTable().fnDestroy();
            var _h = $('head').clone();
            var _p = $('#outprint').clone();
            var _ph = $($('noscript#print-header').html()).clone();
            var _el = $('<div>');
            _p.find('tr.bg-gradient-dark').removeClass('bg-gradient-dark');
            _p.find('tr>td:last-child,tr>th:last-child,colgroup>col:last-child').remove();
            _p.find('.badge').css({'border':'unset'});
            _el.append(_h);
            _el.append(_ph);
            _el.find('title').text('Student Records - Print View');
            _el.append(_p);

            var nw = window.open('', '_blank', 'width=1000,height=900,top=50,left=250');
                nw.document.write(_el.html());
                nw.document.close();

                setTimeout(() => {
                    nw.print();
                    setTimeout(() => {
                        nw.close();
                        end_loader();
                        $('.table').dataTable({
                            columnDefs: [
                                { orderable: false, targets: 5 }
                            ],
                        });
                    }, 200);
                }, 300);
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
