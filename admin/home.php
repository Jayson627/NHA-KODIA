
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
    <?php
// Check if the success message is set
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']);
}

// Queries for data (lots, blocks, students, children, spouses, males, and females)
$total_lot = $conn->query("SELECT * FROM `lot_numbers`")->num_rows;
$total_block = $conn->query("SELECT * FROM `blocks`")->num_rows;
$total_students = $conn->query("SELECT * FROM `student_list`")->num_rows;
$total_children = $conn->query("SELECT * FROM `children`")->num_rows;
$total_spouse = $conn->query("SELECT * FROM `student_list`")->num_rows;

// Combined total of students, children, and spouses
$total_students_children_spouse = $total_students + $total_children + $total_spouse;

// Male and female counts in `student_list`
$total_male_students = $conn->query("SELECT * FROM `student_list` WHERE gender = 'male'")->num_rows;
$total_female_students = $conn->query("SELECT * FROM `student_list` WHERE gender = 'female'")->num_rows;

// Male and female counts in `children`
$total_male_children = $conn->query("SELECT * FROM `children` WHERE gender = 'male'")->num_rows;
$total_female_children = $conn->query("SELECT * FROM `children` WHERE gender = 'female'")->num_rows;

// Male and female counts in `spouses`
$total_male_spouse = $conn->query("SELECT * FROM `student_list` WHERE gender = 'male'")->num_rows;
$total_female_spouse = $conn->query("SELECT * FROM `student_list` WHERE gender = 'female'")->num_rows;

// Combined male and female counts
$total_male_combined = $total_male_students + $total_male_children + $total_male_spouse;
$total_female_combined = $total_female_students + $total_female_children + $total_female_spouse;

// Occupation counts
$total_farmer = $conn->query("SELECT * FROM `student_list` WHERE occupation = 'farmer'")->num_rows;
$total_fisherman = $conn->query("SELECT * FROM `student_list` WHERE occupation = 'fisherman'")->num_rows;
$total_carpenter = $conn->query("SELECT * FROM `student_list`")->num_rows;
$total_vendor = $conn->query("SELECT * FROM `student_list` WHERE occupation = 'vendor'")->num_rows;
$total_driver = $conn->query("SELECT * FROM `student_list` WHERE occupation = 'driver'")->num_rows;
$total_government = $conn->query("SELECT * FROM `student_list` WHERE occupation = 'government'")->num_rows;
$total_unemployed = $conn->query("SELECT * FROM `student_list` WHERE occupation = 'unemployed'")->num_rows;
?>
<header>
        <h3>Welcome to <?php echo $_settings->info('id'); ?> - Admin Panel</h3>
        <hr class="border-pink">
    </header>
    <main class="container-fluid">
        <div class="row">
            <!-- Total Lots Display -->
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

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var pieCtx = document.getElementById('pieChart').getContext('2d');
        var barCtx = document.getElementById('barChart').getContext('2d');

        var genderData = {
            labels: [
                'Total Male',
                'Total Female',
                'Total Residents'
            ],
            datasets: [{
                label: 'Total Counts',
                data: [
                    <?php echo $total_male_combined; ?>,
                    <?php echo $total_female_combined; ?>,
                    <?php echo $total_students_children_spouse; ?>
                ],
                backgroundColor: [
    'rgba(255, 179, 186, 0.6)',  // Pastel Pink (Total Male)
    'rgba(186, 255, 201, 0.6)',  // Pastel Green (Total Female)
    'rgba(186, 225, 255, 0.6)'   // Pastel Blue (Total Students, Children & Spouses)
],
borderColor: [
    'rgba(255, 179, 186, 1)',
    'rgba(186, 255, 201, 1)',
    'rgba(186, 225, 255, 1)'
],
borderWidth: 1
            }]
        };

        var occupationData = {
            labels: [
                'Farmer',
                'Fisherman',
                'Carpenter',
                'Vendor',
                'Driver',
                'Gov-Employee',
                'Unemployed'
            ],
            datasets: [{
                label: 'Total Counts',
                data: [
                    <?php echo $total_farmer; ?>,
                    <?php echo $total_fisherman; ?>,
                    <?php echo $total_carpenter; ?>,
                    <?php echo $total_vendor; ?>,
                    <?php echo $total_driver; ?>,
                    <?php echo $total_government; ?>,
                    <?php echo $total_unemployed; ?>
                ],
                backgroundColor: [
    'rgba(255, 160, 122, 0.6)',  // Light Coral for Farmer
    'rgba(60, 179, 113, 0.6)',   // Medium Sea Green for Fisherman
    'rgba(255, 239, 213, 0.6)',  // Papaya Whip for Carpenter
    'rgba(144, 238, 144, 0.6)',  // Light Green for Vendor
    'rgba(216, 191, 216, 0.6)',  // Thistle for Driver
    'rgba(255, 218, 185, 0.6)',  // Peach Puff for Gov-Employee
    'rgba(220, 220, 220, 0.6)'   // Gainsboro for Unemployed
],
borderColor: [
    'rgba(255, 160, 122, 1)',  // Light Coral for Farmer
    'rgba(60, 179, 113, 1)',   // Medium Sea Green for Fisherman
    'rgba(255, 239, 213, 1)',  // Papaya Whip for Carpenter
    'rgba(144, 238, 144, 1)',  // Light Green for Vendor
    'rgba(216, 191, 216, 1)',  // Thistle for Driver
    'rgba(255, 218, 185, 1)',  // Peach Puff for Gov-Employee
    'rgba(220, 220, 220, 1)'   // Gainsboro for Unemployed
],
borderWidth: 1



            }]
        };

        new Chart(pieCtx, {
            type: 'pie',
            data: genderData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat().format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        new Chart(barCtx, {
            type: 'bar',
            data: occupationData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat().format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
</body>
</html>j
