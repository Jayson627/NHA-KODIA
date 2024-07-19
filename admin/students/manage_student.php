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
<div class="content py-3">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header">
            <h3 class="card-title"><b><?= isset($id) ? "Update Student Details - ". $roll : "New Household" ?></b></h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="student_form">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <fieldset class="border-bottom">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="roll" class="control-label">House No.</label>
                                <input type="text" name="roll" id="roll" autofocus value="<?= isset($roll) ? $roll : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="roll-error" class="text-danger" style="display: none;">Invalid House No. Only numbers are allowed.</div>
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
                                <div id="contact-error" class="text-danger" style="display: none;">Invalid Contact No. Must be exactly 11 digits.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="block" class="control-label">Block #</label>
                                <input type="text" name="block" id="block" value="<?= isset($block) ? $block : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="block-error" class="text-danger" style="display: none;">Invalid Block No. Only numbers are allowed.</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lot" class="control-label">Lot #</label>
                                <input type="text" name="lot" id="lot" value="<?= isset($lot) ? $lot : "" ?>" class="form-control form-control-sm rounded-0" required>
                                <div id="lot-error" class="text-danger" style="display: none;">Invalid Lot No. Only numbers are allowed.</div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="present_address" class="control-label">Baranggay</label>
                                    <textarea rows="3" name="present_address" id="present_address" class="form-control form-control-sm rounded-0" required><?= isset($present_address) ? $present_address : "" ?></textarea>
                                    <div id="present_address-error" class="text-danger" style="display: none;">Invalid Barranggay. Only letters are allowed.</div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="permanent_address" class="control-label">Remarks</label>
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
        // Validation function for House No.
        $('#roll').on('keyup', function() {
            var roll = $(this).val();
            var rollError = $('#roll-error');
            if (/[^0-9]/.test(roll)) {
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

        // Validation function for Contact No., Block No., Lot No.
        $('#contact').on('input', function() {
            var value = $(this).val();
            var contactError = $('#contact-error');
            // Remove non-digit characters
            value = value.replace(/\D/g, '');
            // Limit input to 11 digits
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            $(this).val(value);
            if (value.length !== 11) {
                contactError.text('Invalid Contact No. Must be exactly 11 digits.');
                contactError.show();
            } else {
                contactError.hide();
            }
        });

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

        $('#student_form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_student",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error: err => {
					console.log(err)
					alert_toast("An error occurred",'error');
					end_loader();
				},
                success: function(resp) {
                    if(resp.status == 'success') {
                        location.href="./?page=students/view_student&id="+resp.sid;
                    } else if(!!resp.msg) {
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    } else {
                        el.addClass("alert-danger")
                        el.text("An error occurred due to an unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>
