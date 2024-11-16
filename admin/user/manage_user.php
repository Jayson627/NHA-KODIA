<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif; ?>
<div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">	
				<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
				<div class="row">
					<div class="form-group col-12 col-md-6">
						<label for="firstname">First Name</label>
						<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname']: '' ?>" required>
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="lastname">Last Name</label>
						<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required>
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="email">Email</label>
						<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address.">
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="password">Password</label>
						<input type="password" name="password" id="password" class="form-control" autocomplete="off" <?php echo isset($meta['id']) ? "" : 'required' ?> 
						       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
						       title="Password must be at least 8 characters long, and include at least one uppercase letter, one lowercase letter, one number, and one special character.">
						<?php if (isset($_GET['id'])): ?>
						<small class="text-info"><i>Leave this blank if you don’t want to change the password.</i></small>
						<?php endif; ?>
						<div class="form-check mt-2">
							<input type="checkbox" class="form-check-input" id="show-password">
							<label class="form-check-label" for="show-password">Show Password</label>
						</div>
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="confirm-password">Confirm Password</label>
						<input type="password" name="confirm_password" id="confirm-password" class="form-control" autocomplete="off" <?php echo isset($meta['id']) ? "" : 'required' ?>>
						<small class="text-info"><i>Leave this blank if you don’t want to change the password.</i></small>
					</div>
					<div class="form-group col-6">
					<label for="type">User </label>
					<select name="type" id="type" class="custom-select"  required>
						<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Administrator</option>
						
					</select>
				</div>
					<div class="form-group col-12 col-md-6">
						<label for="" class="control-label">Avatar</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" accept=".jpg,.jpeg,.png" onchange="displayImg(this,$(this))">
							<label class="custom-file-label" for="customFile">Choose file</label>
						</div>
					</div>
					<div class="form-group col-12 col-md-6 d-flex justify-content-center">
						<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<button class="btn btn-sm btn-primary mr-2" form="manage-user">Save</button>
				<a class="btn btn-sm btn-secondary" href="./?page=user/list">Cancel</a>
			</div>
		</div>
	</div>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100%;
	}
</style>
<script>
	$(function(){
		$('.select2').select2({
			width:'resolve'
		});
	});

	function displayImg(input, _this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$('#manage-user').submit(function(e){
	    e.preventDefault();

	    // Validate email
	    var email = $('#email').val();
	    var emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

	    if (email && !emailPattern.test(email)) {
	        $('#msg').html('<div class="alert alert-danger">Please enter a valid email address.</div>');
	        $("html, body").animate({ scrollTop: 0 }, "fast");
	        return; // Stop the form submission
	    }

	    // Validate password
	    var password = $('#password').val();
	    var confirmPassword = $('#confirm-password').val();
	    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

	    if (password && !passwordPattern.test(password)) {
	        $('#msg').html('<div class="alert alert-danger">Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.</div>');
	        $("html, body").animate({ scrollTop: 0 }, "fast");
	        return; // Stop the form submission
	    }

	    if (password !== confirmPassword) {
	        $('#msg').html('<div class="alert alert-danger">Passwords do not match.</div>');
	        $("html, body").animate({ scrollTop: 0 }, "fast");
	        return; // Stop the form submission
	    }

	    var _this = $(this);
	    start_loader();
	    $.ajax({
	        url:_base_url_+'classes/Users.php?f=save',
	        data: new FormData($(this)[0]),
	        cache: false,
	        contentType: false,
	        processData: false,
	        method: 'POST',
	        success:function(resp){
	            if(resp == 1){
	                location.href = './?page=user/list';
	            } else {
	                $('#msg').html('<div class="alert alert-danger">Username already exists</div>');
	                $("html, body").animate({ scrollTop: 0 }, "fast");
	            }
	            end_loader();
	        }
	    });
	});

	// Show/Hide password functionality
	$(function() {
	    $('#show-password').change(function() {
	        if (this.checked) {
	            $('#password').attr('type', 'text'); // Change type to text
	            $('#confirm-password').attr('type', 'text'); // Change type to text for confirmation
	        } else {
	            $('#password').attr('type', 'password'); // Change type back to password
	            $('#confirm-password').attr('type', 'password'); // Change type back to password for confirmation
	        }
	    });
	});
</script>
