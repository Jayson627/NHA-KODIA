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
<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
            <div class="mb-3">
                <label for="search-input" class="form-label">Search:</label>
                <input type="text" id="search-input" class="form-control" placeholder="Search by any field...">
            </div>
            <div class="mb-3">
                <label for="entries-select" class="form-label">Show Entries:</label>
                <select id="entries-select" class="form-control" style="width: auto; display: inline-block;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
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
                            <td><?php echo $row['roll']; ?></td>
                            <td><?php echo $row['fullname']; ?></td>
                            <td><?php echo $row['owner_age']; ?></td>
                            <td><?php echo !empty($row['spouse_fullname']) ? $row['spouse_fullname'] : 'N/A'; ?></td>
                            <td><?php echo $row['spouse_age']; ?></td>
                            <td><?php echo $row['block_no']; ?></td>
                            <td><?php echo $row['lot_no']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['contact']; ?></td>
                            <td><?php echo $row['present_address']; ?></td>
                            <td><?php echo $row['permanent_address']; ?></td>
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
<script>
    document.getElementById('search-input').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#household-table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('entries-select').addEventListener('change', function () {
        const value = parseInt(this.value);
        const rows = document.querySelectorAll('#household-table tbody tr');
        rows.forEach((row, index) => {
            row.style.display = index < value ? '' : 'none';
        });
    });
</script>
