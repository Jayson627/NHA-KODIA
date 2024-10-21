<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo $_settings->info('name'); ?></title>
    <!-- Bootstrap CSS for responsiveness -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/your/css">
    <style>
        #website-cover {
            width: 100%;
            height: 30em;   
            object-fit: cover;
            object-position: center center;
        }
        .info-box {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f5f5f5;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .info-box-icon {
            font-size: 2rem;
        }
    </style>
</head>
<body>
<?php
    // Check if the success message is set
    if (isset($_SESSION['success_message'])) {
        echo "<script>alert('" . $_SESSION['success_message'] . "');</script>"; // Show the alert
        unset($_SESSION['success_message']); // Unset the message after displaying
    }
    ?>
    <header>
        <h3>Welcome to <?php echo $_settings->info('id'); ?> - Admin Panel</h3>
        <hr class="border-black">
    </header>
    <main class="container-fluid">
        <div class="row">
        <?php 
            // Queries for data (lots, blocks, students, children)
            $total_lot = $conn->query("SELECT * FROM `lot_numbers`")->num_rows;
           
            $total_block = $conn->query("SELECT * FROM `blocks`")->num_rows;
           
            $total_students = $conn->query("SELECT * FROM `student_list`")->num_rows;
            $total_children = $conn->query("SELECT * FROM `children`")->num_rows;
        ?>
        
        <!-- Info boxes with responsive Bootstrap classes -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="info-box bg-gradient-pink shadow">
                <span class="info-box-icon bg-gradient-pink elevation-1"><i class="fas fa-cube"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Lots</span>
                    <span class="info-box-number text-right"><?php echo $total_lot; ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="info-box bg-gradient-blue shadow">
                <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Blocks</span>
                    <span class="info-box-number text-right"><?php echo $total_block; ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="info-box bg-gradient-yellow shadow">
                <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-user-friends"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Household Heads</span>
                    <span class="info-box-number text-right"><?php echo $total_students; ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-3">
            <div class="info-box bg-gradient-green shadow">
                <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Children</span>
                    <span class="info-box-number text-right"><?php echo $total_children; ?></span>
                </div>
            </div>
      

        <!-- Chart.js Charts wrapped in responsive containers -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Pie Chart: Totals Overview</h5>
                    </div>
                    <div class="card-body">
                        <div style="overflow-x:auto;">
                            <canvas id="pieChart" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Bar Chart: Totals Overview</h5>
                    </div>
                    <div class="card-body">
                        <div style="overflow-x:auto;">
                            <canvas id="barChart" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Initialize Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
    document.addEventListener('DOMContentLoaded', function () {
        var pieCtx = document.getElementById('pieChart').getContext('2d');
        var barCtx = document.getElementById('barChart').getContext('2d');

        var data = {
            labels: [
                'Total Lots', 
                'Total Blocks', 
                'Household Heads', 
                'Children'
            ],
            datasets: [{
                label: 'Total Counts',
                data: [
                    <?php echo $total_lot; ?>,
                    <?php echo $total_block; ?>,
                    <?php echo $total_students; ?>,
                    <?php echo $total_children; ?>
                ],
                backgroundColor: [
                    'rgba(255, 105, 180, 0.6)', // Total Lots
                    'rgba(54, 162, 235, 0.6)',  // Total Blocks
                    'rgba(255, 255, 0, 0.6)',   // Household Heads
                    'rgba(75, 192, 75, 0.6)'    // Children
                ],
                borderColor: [
                    'rgba(255, 105, 180, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 255, 0, 1)',
                    'rgba(75, 192, 75, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Pie chart initialization
        new Chart(pieCtx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
            }
        });

        // Bar chart initialization
        new Chart(barCtx, {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</body>
</html>
