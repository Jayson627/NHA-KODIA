<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo $_settings->info('name'); ?></title>
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
    <header>
        <h1>Welcome to <?php echo $_settings->info('id'); ?> - Admin Panel</h1>
        <hr class="border-red">
    </header>
    <main class="container">
        <div class="row">
            <?php 
                // Query for total lots
                $total_lot = $conn->query("SELECT * FROM `lot_numbers`")->num_rows;
                
                // Query for total blocks
                $total_block = $conn->query("SELECT * FROM `blocks`")->num_rows;
               
                // Query for total household heads
                $total_students = $conn->query("SELECT * FROM `student_list`")->num_rows;
                
                // Query for total children
                $total_children = $conn->query("SELECT * FROM `children`")->num_rows;
            ?>
            
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="info-box bg-gradient-pink shadow">
                    <span class="info-box-icon bg-gradient-pink elevation-1"><i class="fas fa-building"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Lots</span>
                        <span class="info-box-number text-right"><?php echo $total_lot; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="info-box bg-gradient-blue shadow">
                    <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-scroll"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Blocks</span>
                        <span class="info-box-number text-right"><?php echo $total_block; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="info-box bg-gradient-yellow shadow">
                    <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-user-friends"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Household Heads</span>
                        <span class="info-box-number text-right"><?php echo $total_students; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="info-box bg-gradient-green shadow">
                    <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Children</span>
                        <span class="info-box-number text-right"><?php echo $total_children; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart.js Canvas -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Pie Chart: Totals Overview</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart" style="height: 400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Bar Chart: Totals Overview</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart" style="height: 400px;"></canvas>
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
                labels: ['Lots', 'Blocks', 'Household Heads', 'Children'],
                datasets: [{
                    label: 'Total Counts',
                    data: [
                        <?php echo $total_lot; ?>,
                        <?php echo $total_block; ?>,
                        <?php echo $total_students; ?>,
                        <?php echo $total_children; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 105, 180, 0.6)', // Pink for lots
                        'rgba(54, 162, 235, 0.6)',  // Blue for blocks
                        'rgba(255, 255, 0, 0.6)',   // Bright yellow for household heads
                        'rgba(75, 192, 75, 0.6)'    // Green for children
                    ],
                    borderColor: [
                        'rgba(255, 105, 180, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 255, 0, 1)',   // Bright yellow for household heads
                        'rgba(75, 192, 75, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            var pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                }
            });

            var barChart = new Chart(barCtx, {
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
