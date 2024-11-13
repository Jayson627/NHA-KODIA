<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 0;
    }

	@media (max-width: 768px) {
    /* Make form fields full width */
    .form-group input,
    .form-group select,
    .form-group textarea,
    .card-body .btn {
        width: 100%;
        margin-bottom: 10px; /* Ensure some space between form fields */
    }

    /* Make the table more mobile-friendly */
    .table thead {
        display: none; /* Hide the table header on mobile */
    }
    .table,
    .table tbody,
    .table tr,
    .table td {
        display: block;
        width: 100%;
    }
    .table tr {
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    .table td {
        position: relative;
        padding-left: 50%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: bold;
        text-align: left;
    }
}

/* Modify card headers and footers for mobile */
@media (max-width: 576px) {
    .card-header h3 {
        font-size: 18px; /* Reduce the header size for smaller screens */
    }
    .card-tools {
        font-size: 14px; /* Reduce the tools size */
    }
}
</style>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of System Users</h3>
		<div class="card-tools">
			<a href="?page=user/manage_user" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Avatar</th>
						<th>Name</th>
						<th>Email</th>
						<th>User Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name from `users` where id != '1' order by concat(firstname,' ',lastname) asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center" data-label="#"><?php echo $i++; ?></td>
							<td class="text-center" data-label="Avatar">
								<img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar">
							</td>
							<td data-label="Name"><?php echo ucwords($row['name']) ?></td>
							<td data-label="Email"><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
							<td data-label="User Type"><p class="m-0"><?php echo ($row['type'] == 1 )? "Administrator" : "User" ?></p></td>
							<td align="center" data-label="Action">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item" href="?page=user/manage_user&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
									<div class="dropdown-divider"></div>
									<?php if($row['status'] != 1): ?>
										<a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-name="<?= $row['email'] ?>"><span class="fa fa-check text-primary"></span> Verify</a>
										<div class="dropdown-divider"></div>
									<?php endif; ?>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this User permanently?","delete_user",[$(this).attr('data-id')])
		});
		$('.table td,.table th').addClass('py-1 px-2 align-middle');
		$('.table').dataTable({
			responsive: true
		});
		$('.verify_user').click(function(){
			_conf("Are you sure to verify <b>"+$(this).attr('data-name')+"<b/>?","verify_user",[$(this).attr('data-id')])
		});
	});

	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error: err => {
				console.log(err);
				alert_toast("An error occurred.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp == 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occurred.",'error');
					end_loader();
				}
			}
		});
	}

	function verify_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=verify_user",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error: err => {
				console.log(err);
				alert_toast("An error occurred.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp == 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occurred.",'error');
					end_loader();
				}
			}
		});
	}
</script>
