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
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            min-width: 600px;
        }
        .table th,
        .table td {
            white-space: nowrap;
        }
        .table th {
            font-size: 12px;
        }
        .table td {
            font-size: 11px;
        }
        .badge {
            font-size: 10px;
        }
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header">
        <h3 class="card-title">List of household</h3>
        <div class="card-tools">
            <a href="./?page=students/manage_student" class="btn btn-flat btn-sm btn-primary">
                <span class="fas fa-plus"></span> Household
            </a>
            <button onclick="printTable()" class="btn btn-flat btn-sm" style="background-color: #28a745; color: white;">
    <i class="fa fa-print"></i> Print
</button>

        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="table-responsive">
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
                    </colgroup>
                    <thead>
    <tr class="bg-gradient-dark text-light">
        <th>#</th>
        <th>Date Created</th>
        <th>House no.</th>
        <th>Name</th>
        <th>Block</th>
        <th>Lot</th>
        <th>Gender</th>
        <th>Contact No.</th>
        <th>Barangay</th>
        <th>Remarks</th>
        <th>Status</th>
        <th>Action</th> <!-- Re-added Action column header -->
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
        <td><p class="m-0 truncate-1"><?php echo $row['block_no'] ?></p></td>
        <td><p class="m-0 truncate-1"><?php echo $row['lot_no'] ?></p></td>
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
        <td align="center"> <!-- Re-added Action button for each row -->
            <a href="./?page=students/view_student&id=<?= $row['id'] ?>" class="btn btn-flat btn-default btn-sm border">
                <i class="fa fa-eye"></i> View
            </a>
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
    function printTable() {
        const rows = document.querySelectorAll('#household-table tbody tr');
        let printContent = '<table style="width: 100%; border-collapse: collapse;">';
        printContent += `
            <thead>
                <tr>
                    <th>Date Created</th>
                    <th>House no.</th>
                    <th>Name</th>
                    <th>Block</th>
                    <th>Lot</th>
                    <th>Gender</th>
                    <th>Contact No.</th>
                    <th>Barangay</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
        `;

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            printContent += '<tr>';
            printContent += `<td>${cells[1].innerHTML}</td>`; // Date Created
            printContent += `<td>${cells[2].innerHTML}</td>`; // House no.
            printContent += `<td>${cells[3].innerHTML}</td>`; // Name
            printContent += `<td>${cells[4].innerHTML}</td>`; // Block
            printContent += `<td>${cells[5].innerHTML}</td>`; // Lot
            printContent += `<td>${cells[6].innerHTML}</td>`; // Gender
            printContent += `<td>${cells[7].innerHTML}</td>`; // Contact No.
            printContent += `<td>${cells[8].innerHTML}</td>`; // Barangay
            printContent += `<td>${cells[9].innerHTML}</td>`; // Remarks
            printContent += '</tr>';
        });

        printContent += '</tbody></table>';

        const printWindow = window.open('', '', 'width=900,height=650');
        printWindow.document.open();
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Household Information</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                        }
                        h3 {
                            text-align: center;
                            font-weight: bold;
                            margin-bottom: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        th {
                            background-color: #4CAF50;
                            color: white;
                        }
                        tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }
                        tr:hover {
                            background-color: #ddd;
                        }
                        td, th {
                            font-size: 14px;
                            padding: 10px;
                        }
                    </style>
                </head>
                <body>
                    <h3>Household Information</h3>
                    ${printContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
</script>

