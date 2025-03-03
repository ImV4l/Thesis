<?php

include('authentication.php');
include('includes/header.php');

// if(!isset($_SESSION['auth'])){
//     header("Location: login.php?error_msg=Invalid Access");
// }
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-building"></i> Offices
                    <?php
                    $dash_work_query = "SELECT sa.*, w.work_name 
                           FROM student_assistant sa
                           JOIN work w 
                           ON sa.work LIKE CONCAT('%', w.work_name, '%')
                           WHERE w.type = 'Office';";
                    $dash_work_query_run = mysqli_query($con, $dash_work_query);

                    if ($work_total = mysqli_num_rows($dash_work_query_run)) {
                        echo '<h4 class="mb-0">' . $work_total . ' </h4>';
                    } else {
                        echo '<h4 class="mb-0"> No Data </h4>';
                    }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="offices.php">View Offices</a>
                    <div class="small text-white"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-flask"></i> Laboratories
                    <?php
                    $dash_work_query = "SELECT sa.*, w.work_name 
                           FROM student_assistant sa
                           JOIN work w 
                           ON sa.work LIKE CONCAT('%', w.work_name, '%')
                           WHERE w.type = 'Laboratory';";
                    $dash_work_query_run = mysqli_query($con, $dash_work_query);

                    if ($work_total = mysqli_num_rows($dash_work_query_run)) {
                        echo '<h4 class="mb-0">' . $work_total . ' </h4>';
                    } else {
                        echo '<h4 class="mb-0"> No Data </h4>';
                    }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="laboratories.php">View Laboratories</a>
                    <div class="small text-white"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <i class="fas fa-users"></i> Manpower Services
                    <?php
                    $dash_work_query = "SELECT sa.*, w.work_name 
                           FROM student_assistant sa
                           JOIN work w 
                           ON sa.work LIKE CONCAT('%', w.work_name, '%')
                           WHERE w.type = 'Manpower Services';";
                    $dash_work_query_run = mysqli_query($con, $dash_work_query);

                    if ($work_total = mysqli_num_rows($dash_work_query_run)) {
                        echo '<h4 class="mb-0">' . $work_total . ' </h4>';
                    } else {
                        echo '<h4 class="mb-0"> No Data </h4>';
                    }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="services.php">View Manpower Services</a>
                    <div class="small text-white"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Analytics Graph Section -->
    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Attendance Chart
                </div>
                <div class="card-body" style="height: 500px;">
                    <canvas id="workHoursChart" width="100%" height="100%"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fetch data for the chart
    fetch('get_daily_work_hours.php')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('workHoursChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Total Work Hours',
                        data: data.hours,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Hours'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>