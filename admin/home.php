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
            color: pink;
        }
        .info-box {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: pink;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .info-box-icon {
            font-size: 2rem;
        }
        /* Responsive styles for charts */
        @media (max-width: 768px) {
            .chart-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
<?php
    // Check if the success message is set
    if (isset($_SESSION['success_message'])) {
        echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
        unset($_SESSION['success_message']);
    }

   // Queries for data (lots, blocks, students, children, males, and females)
   $total_lot = $conn->query("SELECT * FROM `lot_numbers`")->num_rows;
   $total_block = $conn->query("SELECT * FROM `blocks`")->num_rows;
   $total_students = $conn->query("SELECT * FROM `student_list`")->num_rows;
   $total_children = $conn->query("SELECT * FROM `children`")->num_rows;

   // Male and female counts (assuming the gender column is named 'gender' and has values 'male' and 'female')
   $total_male = $conn->query("SELECT * FROM `student_list` WHERE gender = 'male'")->num_rows;
   $total_female = $conn->query("SELECT * FROM `student_list` WHERE gender = 'female'")->num_rows;
?>
    <header>
        <h3>Welcome to <?php echo $_settings->info('id'); ?> - Admin Panel</h3>
        <hr class="border-pink">
    </header>
    <main class="container-fluid">
        <div class="row">
            <!-- Total Lots Display -->
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                <div class="info-box bg-gradient-pink shadow">
                    <span class="info-box-icon bg-gradient-pink elevation-1"><i class="fas fa-cube"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Lots</span>
                        <span class="info-box-number text-right"><?php echo $total_lot; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                <div class="info-box bg-gradient-blue shadow">
                    <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-box"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Blocks</span>
                        <span class="info-box-number text-right"><?php echo $total_block; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                <div class="info-box bg-gradient-yellow shadow">
                    <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-user-friends"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Household Heads</span>
                        <span class="info-box-number text-right"><?php echo $total_students; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
                <div class="info-box bg-gradient-green shadow">
                    <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Children</span>
                        <span class="info-box-number text-right"><?php echo $total_children; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Males Display -->
    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
        <div class="info-box bg-gradient-danger shadow">
            <span class="info-box-icon bg-gradient-danger elevation-1"><i class="fas fa-male"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Males</span>
                <span class="info-box-number text-right"><?php echo $total_male; ?></span>
            </div>
        </div>
    </div>

    <!-- Total Females Display -->
    <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-3">
        <div class="info-box bg-gradient-info shadow">
            <span class="info-box-icon bg-gradient-info elevation-1"><i class="fas fa-female"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Females</span>
                <span class="info-box-number text-right"><?php echo $total_female; ?></span>
            </div>
        </div>
    </div>
</div>
    
        <!-- Chart.js Charts wrapped in responsive containers -->
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Pie Chart: Totals Overview</h5>
                    </div>
                    <div class="card-body chart-container">
                        <canvas id="pieChart" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Bar Chart: Totals Overview</h5>
                    </div>
                    <div class="card-body chart-container">
                        <canvas id="barChart" class="chartjs-render-monitor"></canvas>
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
                    'Total male', 
                    'Total female',
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
                        <?php echo $total_male; ?>,
                        <?php echo $total_female; ?>
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

            function createChart(ctx, type, data) {
                return new Chart(ctx, {
                    type: type,
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: window.innerWidth <= 768 ? false : true,
                        scales: type === 'bar' ? {
                            y: {
                                beginAtZero: true
                            }
                        } : {}
                    }
                });
            }

            // Create the charts
            var pieChart = createChart(pieCtx, 'pie', data);
            var barChart = createChart(barCtx, 'bar', data);

            // Adjust charts on window resize
            window.addEventListener('resize', function() {
                pieChart.options.maintainAspectRatio = window.innerWidth <= 768 ? false : true;
                barChart.options.maintainAspectRatio = window.innerWidth <= 768 ? false : true;
                pieChart.update();
                barChart.update();
            });
        });
    </script>

</body>
</html>
