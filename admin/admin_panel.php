<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for total lots
$total_lot = $conn->query("SELECT * FROM `lots`")->num_rows;

// Query for total blocks
$total_block = $conn->query("SELECT * FROM `blocks`")->num_rows;

// Query for total household heads
$total_students = $conn->query("SELECT * FROM `student_list`")->num_rows;

// Query for total children
$total_academics = $conn->query("SELECT * FROM `academic_history`")->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
        .back-button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 0.5rem;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Admin Panel</h1>
        <hr class="border-red">
    </header>
    <main class="container">
        <div class="row">
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
                        <span class="info-box-number text-right"><?php echo $total_academics; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart.js Canvas -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-outline card-navy shadow rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">Graph: Totals Overview</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="totalsChart" style="height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Back Button -->
        <a href="index.php" class="back-button">Back to Home</a>
    </main>
    
    <!-- Initialize Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('totalsChart').getContext('2d');
            var totalsChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Lots', 'Blocks', 'Household Heads', 'Children'],
                    datasets: [{
                        label: 'Total Counts',
                        data: [
                            <?php echo $total_lot; ?>,
                            <?php echo $total_block; ?>,
                            <?php echo $total_students; ?>,
                            <?php echo $total_academics; ?>
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
                },
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

<?php
// Close the database connection
$conn->close();
?>
