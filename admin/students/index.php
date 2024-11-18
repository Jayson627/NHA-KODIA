<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message variable
$error_message = "";
?>
<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet"> <!-- DataTables CSS -->

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
            font-size: 14px; /* Increased font size for better readability */
        }
        .table td {
            font-size: 12px; /* Increased font size for better readability */
        }
        .badge {
            font-size: 10px;
        }
    }
    /* General table styles */
    table {
        width: 100%;
        border-collapse: collapse; /* Ensures borders are merged */
    }
    th, td {
        border: 1px solid #ddd; /* Adds borders to table cells */
        padding: 12px; /* Increased padding for better spacing */
        text-align: left; /* Align text to the left */
        font-size: 14px; /* Default font size */
    }
    th {
        background-color: #4CAF50; /* Header background color */
        color: white; /* Header text color */
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; /* Zebra striping for better visual separation */
    }
    tr:hover {
        background-color: #ddd; /* Highlight row on hover */
    }
</style>

<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header">
        <h3 class="card-title">List of Household</h3>
        <div class="card-tools">
            <a href="./?page=students/manage_student" class="btn btn-flat btn-sm btn-primary">
                <span class="fas fa-plus"></span> Household
            </a>
            <button onclick="printTable()" class="btn btn-flat btn-sm" style="background-color: #28a745; color: white;">
                <i class="fa fa-print"></i> Print
            </button>
            <a href="backup_list.php" class="btn btn-sm btn-warning btn-flat">
                <i class="fas fa-trash-alt"></i>
            </a>
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
                        <col width="50%">
                        <col width="5%">
                        <col width="50%">
                        <col width="5%">
                        <col width="15%">
                        <col width="15%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gradient-dark text-light">
                            <th>#</th>
                            <th>Date Created</th>
                            <th>House No.</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Spouse Name</th>
                            <th>Spouse Age</th>
                            <th>Block</th>
                            <th>Lot</th>
                            <th>Sex</th>
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
                        $qry = $conn->query("
                            SELECT *, 
                            concat(lastname, ', ', firstname, ' ', middlename,'', owner_extension) as fullname,
                            concat(spouse_lastname, ', ', spouse_firstname, ' ', spouse_middlename,' ',spouse_extension) as spouse_fullname 
                            FROM `student_list` 
                            ORDER BY concat(lastname, ', ', firstname, ' ', middlename) ASC
                        ");
                        while ($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])); ?></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['roll']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['fullname']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['owner_age']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo !empty($row['spouse_fullname']) ? $row['spouse_fullname'] : 'N/A'; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['spouse_age']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['block_no']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['lot_no']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['gender']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['contact']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['present_address']; ?></p></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['permanent_address']; ?></p></td>
                            <td class="text-center">
                                <?php 
                                switch ($row['status']) {
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> <!-- DataTables JS -->

<script>
$(document).ready(function(){
    // Initialize DataTables with search bar and pagination
    $('#household-table').DataTable({
        "paging": true,         // Enable pagination
        "searching": true,      // Enable search bar
        "ordering": true,       // Enable column sorting
        "lengthMenu": [10, 25, 50, 100], // Allow user to select number of rows per page
        "pageLength": 10,       // Set default number of rows per page
        columnDefs: [
            { orderable: false, targets: 13 } // Disable sorting on 'Action' column
        ]
    });
});

// Print table function
function printTable() {
    const rows = document.querySelectorAll('#household-table tbody tr');
    let printContent = '<table style="width: 100%; border-collapse: collapse;">';
    printContent += `
        <thead>
            <tr>
                <th>Date Created</th>
                <th>House No.</th>
                <th>Name</th>
                <th>Age</th>
                <th>Spouse Name</th>
                <th>Spouse Age</th>
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
        printContent += `<td>${cells[2].innerHTML}</td>`; // House No.
        printContent += `<td>${cells[3].innerHTML}</td>`; // Name
        printContent += `<td>${cells[4].innerHTML}</td>`; // Age
        printContent += `<td>${cells[5].innerHTML}</td>`; // Spouse Name
        printContent += `<td>${cells[6].innerHTML}</td>`; // Spouse Age
        printContent += `<td>${cells[7].innerHTML}</td>`; // Block
        printContent += `<td>${cells[8].innerHTML}</td>`; // Lot
        printContent += `<td>${cells[9].innerHTML}</td>`; // Gender
        printContent += `<td>${cells[10].innerHTML}</td>`; // Contact No.
        printContent += `<td>${cells[11].innerHTML}</td>`; // Barangay
        printContent += `<td>${cells[12].innerHTML}</td>`; // Remarks
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
                        display: inline-block;
                        font-weight: bold;
                        margin-left: 20px;
                        vertical-align: middle;
                    }
                    .header-container {
                        display: flex;
                        align-items: center;
                        justify-content: center; /* This centers the content horizontally */
                        margin-bottom: 20px;
                        width: 100%;
                    }

                    .logo {
                        width: 60px;
                        height: 60px;
                        margin-right: 10px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 12px;
                        text-align: left;
                    }
                    th {
                        background-color: #4CAF50;
                        color: white;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: center;
                    }
                    .signature-line {
                        margin-top: 30px;
                        font-size: 16px;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <div class="header-container">
                    <img src="lo.png" alt="Logo" class="logo">
                    <h3 style="text-align: center; flex-grow: 1;">Household Information  <br> Baranggay Kodia final list  Beneficiaries </h3>
                </div>
                ${printContent}
                <div class="footer">
                    <p style="text-align: right;">__________________________</p>
                    <p style="text-align: right;">Captain: Jayson S. Alcantara</p>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
