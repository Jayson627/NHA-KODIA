<?php
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
?>
<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header">
        <h3 class="card-title">List of household</h3>
        <div class="card-tools">
            <a href="./?page=students/manage_student" class="btn btn-flat btn-sm btn-primary">
                <span class="fas fa-plus"></span> Household
            </a>
            <button class="btn btn-sm btn-success bg-success btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-hover table-striped" id="household-table">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="10%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="25%">
                        <col width="25%">
                        <col width="10%">
                        <col width="5%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gradient-dark text-light">
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Contact No.</th>
                            <th>Name</th>
                            <th>Block</th>
                            <th>Lot</th>
                            <th>Gender</th>
                            <th>Contact No.</th>
                            <th>Barangay</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT *, concat(lastname, ', ', firstname, ' ', middlename) as fullname FROM `student_list` ORDER BY concat(lastname, ', ', firstname, ' ', middlename) ASC");
                            while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['roll'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['fullname'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['block'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['lot'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['gender'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['contact'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['present_address'] ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['permanent_address'] ?></p></td>
                            <td class="text-center">
                                <?php 
                                    switch ($row['status']){
                                        case 0:
                                            echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Inactive</span>';
                                            break;
                                        case 1:
                                            echo '<span class="rounded-pill badge badge-success bg-gradient-success px-3">Active</span>';
                                            break;
                                    }
                                ?>
                            </td>
                            <td align="center">
                                <a href="./?page=students/view_student&id=<?= $row['id'] ?>" class="btn btn-flat btn-default btn-sm border"><i class="fa fa-eye"></i> View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });

        $('#print').click(function(){
            printTable();
        });
    });

    function printTable() {
        var divToPrint = document.getElementById("household-table").cloneNode(true);
        var rows = divToPrint.rows;
        
        // Remove the last two columns (Status and Action)
        for (var i = 0; i < rows.length; i++) {
            if (i === 0) {
                rows[i].deleteCell(-1);
                rows[i].deleteCell(-1);
            } else {
                rows[i].deleteCell(-1);
                rows[i].deleteCell(-1);
            }
        }

        var newWin = window.open("");
        newWin.document.write('<html><head><title>Print Household List</title>');
        newWin.document.write('<style>');
        newWin.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
        newWin.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }');
        newWin.document.write('table, th, td { border: 1px solid black; }');
        newWin.document.write('th, td { padding: 8px 12px; text-align: left; }');
        newWin.document.write('th { background-color: #f2f2f2; }');
        newWin.document.write('</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<h2>Household List</h2>');
        newWin.document.write(divToPrint.outerHTML);
        newWin.document.write('</body></html>');
        newWin.document.close();
        newWin.print();
    }

    function delete_student($id){
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Master.php?f=delete_student",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp == 'object' && resp.status == 'success'){
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
