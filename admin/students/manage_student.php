<?php
include_once('connection.php'); // Include your database connection

// Define the capacity for each block
$block_capacity = 2;

// Initialize variables
$occupied_blocks = 6;
$unoccupied_blocks = 6;

// Get the total number of blocks
$total_block = $conn->query("SELECT COUNT(*) as total FROM `blocks`")->fetch_assoc()['total'];

// Query to count occupied blocks
$occupied_blocks_query = $conn->query("
    SELECT COUNT(b.block_no) AS occupied_count
    FROM blocks b
    LEFT JOIN student_list s ON b.block_no = s.block_no
    GROUP BY b.block_no
    HAVING COUNT(s.id) >= $block_capacity
");

if ($occupied_blocks_query) {
    $occupied_blocks = $occupied_blocks_query->num_rows; // Count of occupied blocks
}

// Calculate unoccupied blocks
$unoccupied_blocks = $total_block - $occupied_blocks;


// Get available blocks for the dropdown (only blocks that are not fully occupied)
$blocks = [];
$result = $conn->query("
    SELECT b.block_no, COUNT(s.id) AS assigned_count 
    FROM blocks b
    LEFT JOIN student_list s ON b.block_no = s.block_no
    GROUP BY b.block_no
    HAVING assigned_count < $block_capacity
");

if (!$result) {
    // Display the SQL error if the query fails
    echo "SQL Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $blocks[] = $row['block_no'];
        }
    }
}


// Get available lots for the dropdown (only lots that are not assigned)
$lots = [];
$result = $conn->query("SELECT lot_no FROM lots WHERE lot_no NOT IN (SELECT lot FROM student_list)");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lots[] = $row['lot_no'];
    }
}

// Fetch student details if updating
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `student_list` WHERE id = '{$_GET['id']}'");
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

<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `student_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<?php
if(isset($_POST['roll'])){
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $roll = $_POST['roll'];

    // Check if the household number already exists
    $check = $conn->query("SELECT * FROM `student_list` WHERE roll = '$roll' AND id != '$id'");
    if($check->num_rows > 0){
        echo json_encode(['status' => 'error', 'msg' => 'Household number is already in use.']);
        exit;
    }

    // Proceed with saving the student details
    // Your existing code to save the student details
    // ...

    echo json_encode(['status' => 'success', 'sid' => $newlyCreatedId]);
    exit;
}
?>



<div class="content py-3">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header">
            <h3 class="card-title"><b><?= isset($id) ? "Update Household Details - ". $roll : "New Household" ?></b></h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="student_form">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <fieldset class="border-bottom">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="roll" class="control-label">Household No.</label>
                                <input type="text" name="roll" id="roll" autofocus value="<?= isset($roll) ? $roll : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="roll-error" class="text-danger" style="display: none;">Household number is already in use.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="firstname" class="control-label">First Name</label>
                                <input type="text" name="firstname" id="firstname" value="<?= isset($firstname) ? $firstname : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="firstname-error" class="text-danger" style="display: none;">Invalid First Name. Only letters are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="middlename" class="control-label">Middle Name</label>
                                <input type="text" name="middlename" id="middlename" value="<?= isset($middlename) ? $middlename : "" ?>" class="form-control form-control-sm rounded-0" placeholder='optional'>
                                <div id="middlename-error" class="text-danger" style="display: none;">Invalid Middle Name. Only letters are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lastname" class="control-label">Last Name</label>
                                <input type="text" name="lastname" id="lastname" value="<?= isset($lastname) ? $lastname : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="lastname-error" class="text-danger" style="display: none;">Invalid Last Name. Only letters are allowed.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="gender" class="control-label">Gender</label>
                                <select name="gender" id="gender" class="form-control form-control-sm rounded-0" required>
                                    <option <?= isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option <?= isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dob" class="control-label">Date of Birth</label>
                                <input type="date" name="dob" id="dob" value="<?= isset($dob) ? $dob : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="contact" class="control-label">Contact #</label>
                                <input type="text" name="contact" id="contact" value="<?= isset($contact) ? $contact : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="contact-error" class="text-danger" style="display: none;">Invalid Contact No. Must be 11 digits and only numbers are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
    <label for="block" class="control-label">Block #</label>
    <select name="block_no" id="block" class="form-control form-control-sm rounded-0" required>
        <option value="">Select Block</option>
        <?php foreach ($blocks as $block_no): ?>
            <option value="<?= htmlspecialchars($block_no) ?>" <?= isset($block) && $block == $block_no ? 'selected' : '' ?>>
                <?= htmlspecialchars($block_no) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div id="block-error" class="text-danger" style="display: none;">Please select a valid Block.</div>
</div>

<div class="form-group col-md-4">
    <label for="lot" class="control-label">Lot #</label>
    <select name="lot" id="lot" class="form-control form-control-sm rounded-0" required>
        <option value="">Select Lot</option>
        <?php foreach ($lots as $lot_no): ?>
            <option value="<?= htmlspecialchars($lot_no) ?>" <?= isset($lot) && $lot == $lot_no ? 'selected' : '' ?>>
                <?= htmlspecialchars($lot_no) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div id="lot-error" class="text-danger" style="display: none;">Please select a valid Lot.</div>
</div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="present_address" class="control-label">Barranggay</label>
                                    <textarea rows="3" name="present_address" id="present_address" class="form-control form-control-sm rounded-0" required><?= isset($present_address) ? $present_address : "" ?></textarea>
                                    <div id="present_address-error" class="text-danger" style="display: none;">Invalid Barranggay. Only letters are allowed.</div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="permanent_address" class="control-label">Reymarks</label>
                                    <textarea rows="3" name="permanent_address" id="permanent_address" class="form-control form-control-sm rounded-0" required><?= isset($permanent_address) ? $permanent_address : "" ?></textarea>
                                    <div id="permanent_address-error" class="text-danger" style="display: none;">Invalid Reymarks. Only letters are allowed.</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-flat btn-primary btn-sm" type="submit" form="student_form">Save Household Details</button>
            <a href="./?page=students" class="btn btn-flat btn-default border btn-sm">Cancel</a>
        </div>
    </div>
</div>
<script>
    $(function(){
        // Validation function for household No.
        $('#roll').on('input', function() {
            var roll = $(this).val();
            var rollError = $('#roll-error');
            // Remove non-digit characters
            roll = roll.replace(/\D/g, '');
    
            if (roll.length > 3) {
                roll = roll.substring( 3);
            }
            $(this).val(roll);
            if (roll.length !== 3) {
                rollError.text('.');
                rollError.show();
            } else {
                rollError.hide();
            }
        });

        // Validation function for First Name, Middle Name, Last Name
        $('#firstname, #middlename, #lastname').on('keyup', function() {
            var name = $(this).val();
            var id = $(this).attr('id');
            var errorElement = $('#' + id + '-error');
            if (/[^a-zA-Z ]/.test(name)) {
                errorElement.show();
            } else {
                errorElement.hide();
            }
        });

        // Validation function for Block No., Lot No.
        $('#block, #lot').on('keyup', function() {
            var value = $(this).val();
            var id = $(this).attr('id');
            var errorElement = $('#' + id + '-error');
            if (/[^0-9]/.test(value)) {
                errorElement.show();
            } else {
                errorElement.hide();
            }
        });

        // Validation function for Barranggay, Reymarks
        $('#present_address, #permanent_address').on('keyup', function() {
            var value = $(this).val();
            var id = $(this).attr('id');
            var errorElement = $('#' + id + '-error');
            if (/[^a-zA-Z ]/.test(value)) {
                errorElement.show();
            } else {
                errorElement.hide();
            }
        });

        // Validation function for Contact Number
        $('#contact').on('input', function() {
            var contact = $(this).val();
            var contactError = $('#contact-error');
            // Remove non-digit characters
            contact = contact.replace(/\D/g, '');

            if (contact.length > 11) {
                contact = contact.substring(0, 11);
            }
            $(this).val(contact);
            if (contact.length !== 11) {
                contactError.text('Invalid Contact No. Must be 11 digits and only numbers are allowed.');
                contactError.show();
            } else {
                contactError.hide();
            }
        });

        $('#student_form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_+"classes/Master.php?f=save_student",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp){
                    if(typeof resp == 'object' && resp.status == 'success'){
                        location.href = "./?page=students/view_student&id=" + resp.sid;
                    } else if(resp.status == 'error' && !!resp.msg){
                        $('#roll-error').text(resp.msg).show();
                        $("html, body, .modal").scrollTop(0);
                        end_loader();
                    } else {
                        alert_toast("Household Numbers is in Use", 'error');
                        end_loader();
                        console.log(resp);
                    }
                }
            });
        });
    });
</script>
