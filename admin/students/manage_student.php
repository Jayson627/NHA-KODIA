<?php
include_once('connection.php'); // Include your database connection

// Fetch available blocks
$blocks = [];
$block_result = $conn->query("SELECT block_no FROM blocks");
while ($row = $block_result->fetch_assoc()) {
    $blocks[] = $row['block_no'];
}

// Fetch available lots
$lots = [];
$lot_result = $conn->query("SELECT lot_number FROM lot_numbers");
while ($row = $lot_result->fetch_assoc()) {
    $lots[] = $row['lot_number'];
}

// Fetch student data if ID is set
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `student_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}

// Handle form submission for saving student data
if (isset($_POST['roll'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $roll = $_POST['roll'];

    // Check if the household number already exists
    $check = $conn->query("SELECT * FROM `student_list` WHERE roll = '$roll' AND id != '$id'");
    if ($check->num_rows > 0) {
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
            <h3 class="card-title"><b><?= isset($id) ? "Update Household Details - " . $roll : "New Household" ?></b></h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="student_form">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    
                    <fieldset class="border-bottom">
                        <legend>Household Details</legend>
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="roll" class="control-label">Household No.</label>
                                <input type="text" name="roll" id="roll" autofocus value="<?= isset($roll) ? $roll : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="roll-error" class="text-danger" style="display: none;">Household number is already in use.</div>
                            </div>
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
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="lastname" class="control-label">Last Name</label>
                                <input type="text" name="lastname" id="lastname" value="<?= isset($lastname) ? $lastname : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="lastname-error" class="text-danger" style="display: none;">Invalid Last Name. Only letters are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="owner_extension" class="control-label">Owner Extension</label>
                                <select name="owner_extension" id="owner_extension" class="form-control form-control-sm rounded-0">
                                    <option value="">Select Extension</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                                <div id="owner_extension-error" class="text-danger" style="display: none;">Invalid Owner Extension.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="owner_age" class="control-label">Owner Age</label>
                                <input type="number" name="owner_age" id="owner_age" value="<?= isset($owner_age) ? $owner_age : "" ?>" class="form-control form-control-sm rounded-0" required min="0" readonly>
                                <div id="owner_age-error" class="text-danger" style="display: none;">Invalid Age. Please enter a valid number.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="gender" class="control-label">Sex</label>
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
                                <input type="text" name="contact" id="contact" value="<?= isset($contact) ? $contact : "" ?>" class="form-control form-control-sm rounded-0" required placeholder="09267754212">
                                <div id="contact-error" class="text-danger" style="display: none;">Invalid Contact No. Must be 11 digits and only numbers are allowed.</div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-bottom">
                        <legend>Address Details</legend>
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="block" class="control-label">Block #</label>
                                <select name="block_no" id="block" class="form-control form-control-sm rounded-0" required>
                                    <option value="">Select Block</option>
                                    <?php foreach ($blocks as $block_no): ?>
                                        <option value="<?= htmlspecialchars($block_no) ?>" <?= isset($student) && $student['block_no'] == $block_no ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($block_no) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lot" class="control-label">Lot #</label>
                                <select name="lot_no" id="lot" class="form-control form-control-sm rounded-0" required>
                                    <option value="">Select Lot</option>
                                </select>
                                <div id="lot-error" class="text-danger" style="display: none;">Please select a valid Lot.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="present_address" class="control-label">Barangay</label>
                                <select name="present_address" id="present_address" class="form-control form-control-sm rounded-0" required>
                                    <option value="" disabled selected>Select a Barangay</option>
                                    <option value="kangwayan">Barangay kangwayan</option>
                                    <option value=" Tugas ">Barangay Tugas</option>
                                    <option value="Kaongkod ">Barangay Kaongkod</option>
                                    <option value=" Mancilang ">Barangay Mancilang</option>
                                    <option value="Kodia ">Barangay Kodia</option>
                                    <option value="Pili ">Barangay Pili</option>
                                    <option value=" Tarong ">Barangay Tarong</option>
                                    <option value="Maalat ">Barangay Maalat</option>
                                    <option value=" Talangnan ">Barangay Talangnan</option>
                                    <option value="San Agustin ">Barangay San Agustin</option>
                                    <option value="Malbago ">Barangay Malbago</option>
                                    <option value="Tabagak ">Barangay Tabagak</option>
                                    <option value= Bunakan">Barangay Bunakan</option>
                                    <option value="Poblacion ">Barangay Poblacion</option>
                                </select>
                                <div id="present_address-error" class="text-danger" style="display: none;">Invalid Barangay. Please select a valid option.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label for="permanent_address" class="control-label">Remarks</label>
                                <textarea rows="3" name="permanent_address" id="permanent_address" class="form-control form-control-sm rounded-0" required><?= isset($permanent_address) ? $permanent_address : "" ?></textarea>
                                <div id="permanent_address-error" class="text-danger" style="display: none;">Please provide valid remarks.</div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-bottom">
                        <legend>Spouse Details (if applicable)</legend>
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="spouse_firstname" class="control-label">Spouse First Name</label>
                                <input type="text" name="spouse_firstname" id="spouse_firstname" value="<?= isset($spouse_firstname) ? $spouse_firstname : "" ?>" class="form-control form-control-sm rounded-0">
                                <div id="spouse_firstname-error" class="text-danger" style="display: none;">Invalid First Name. Only letters are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="spouse_middlename" class="control-label">Spouse Middle Name</label>
                                <input type="text" name="spouse_middlename" id="spouse_middlename" value="<?= isset($spouse_middlename) ? $spouse_middlename : "" ?>" class="form-control form-control-sm rounded-0" placeholder='optional'>
                                <div id="spouse_middlename-error" class="text-danger" style="display: none;">Invalid Middle Name. Only letters are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="spouse_lastname" class="control-label">Spouse Last Name</label>
                                <input type="text" name="spouse_lastname" id="spouse_lastname" value="<?= isset($spouse_lastname) ? $spouse_lastname : "" ?>" class="form-control form-control-sm rounded-0">
                                <div id="spouse_lastname-error" class="text-danger" style="display: none;">Invalid Last Name. Only letters are allowed.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="spouse_extension" class="control-label">Spouse Extension</label>
                                <select name="spouse_extension" id="spouse_extension" class="form-control form-control-sm rounded-0">
                                    <option value="">Select Extension</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                                <div id="spouse_extension-error" class="text-danger" style="display: none;">Invalid Spouse Extension.</div>
                            </div>

    <div class="form-group col-md-4">
        <label for="spouse_dob" class="control-label">Spouse Date of Birth</label>
        <input type="date" name="spouse_dob" id="spouse_dob" value="<?= isset($spouse_dob) ? $spouse_dob : "" ?>" class="form-control form-control-sm rounded-0">
    </div>
    <div class="form-group col-md-4">
        <label for="spouse_age" class="control-label">Spouse Age</label>
        <input type="number" name="spouse_age" id="spouse_age" value="<?= isset($spouse_age) ? $spouse_age : "" ?>" class="form-control form-control-sm rounded-0" min="0" readonly>
        <div id="spouse_age-error" class="text-danger" style="display: none;">Invalid Age. Please enter a valid number.</div>
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

$(function() {
    $('#block').change(function() {
        var blockNo = $(this).val();
        var lotSelect = $('#lot');

        // Clear previous options
        lotSelect.empty();
        lotSelect.append('<option value="">Select Lot</option>');

        if (blockNo) {
            $.ajax({
                url: 'get_lots', // Your PHP script to fetch lots
                method: 'POST',
                data: { block_no: blockNo },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $.each(response.lots, function(index, lot) {
                            lotSelect.append('<option value="' + lot + '">' + lot + '</option>');
                        });
                    } else {
                        console.error(response.msg);
                    }
                },
                error: function(err) {
                    console.error('Error fetching lots:', err);
                }
            });
        }
    });
});

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

        $(function() {
    $('#dob').change(function() {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var monthDifference = today.getMonth() - dob.getMonth();

        // If the current month is before the birth month, subtract a year
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        $('#owner_age').val(age);
    });
});

$(function() {
    $('#spouse_dob').change(function() {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var monthDifference = today.getMonth() - dob.getMonth();

        // If the current month is before the birth month, subtract a year
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        $('#spouse_age').val(age);
    });
});


        // Validation function for Spouse First Name, Middle Name, Last Name
$('#spouse_firstname, #spouse_middlename, #spouse_lastname').on('keyup', function() {
    var name = $(this).val();
    var id = $(this).attr('id');
    var errorElement = $('#' + id + '-error');
    if (/[^a-zA-Z ]/.test(name)) {
        errorElement.show();
    } else {
        errorElement.hide();
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
                url: _base_url_+"classes/Master?f=save_student",
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
