<?php if($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif; ?>

<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 0;
    }

    @media (max-width: 768px) {
        .table thead {
            display: none; /* Hide the header on small screens */
        }
        .table, .table tbody, .table tr, .table td {
            display: block; /* Make each row a block */
            width: 100%; /* Full width */
        }
        .table tr {
            margin-bottom: 15px; /* Space between rows */
            border: 1px solid #dee2e6; /* Add a border */
        }
        .table td {
            text-align: right; /* Right align text */
            position: relative; /* Position relative for pseudo-elements */
            padding-left: 50%; /* Add padding for the label */
        }
        .table td::before {
            content: attr(data-label); /* Use data-label for each cell */
            position: absolute; /* Position absolute for the label */
            left: 0;
            padding-left: 10px;
            font-weight: bold;
            text-align: left; /* Align to the left */
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
                        <th>User </th>
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
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar"></td>
                            <td><?php echo ucwords($row['name']) ?></td>
                            <td ><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
                            <td ><p class="m-0"><?php echo ($row['type'] == 1 )? "Adminstrator" : "Staff" ?></p></td>
                            <td align="center">
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
    $(document).ready(function() {
        // Initialize the DataTable
        var table = $('.table').DataTable({
            responsive: true,       // Ensure table is responsive
            searching: false,       // Disable search box
            lengthChange: false,    // Disable "Show entries" dropdown
            pageLength: 10,         // Set the default number of rows per page
            paging: true,           // Ensure pagination is enabled
            info: true,             // Show table info (e.g., showing x to y of z entries)
            language: {
                paginate: {
                    previous: "<",   // Customize the previous button text
                    next: ">"        // Customize the next button text
                }
            }
        });

        // Delete action
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this User permanently?","delete_user",[$(this).attr('data-id')])
        });

        // Verify action
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
