<?php

include('authentication.php');
include('includes/header.php');

// Get selected date from the request or default to today
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Parse the selected date
$date_parts = explode('-', $selected_date);
$selected_year = $date_parts[0];
$selected_month = $date_parts[1];
$selected_day = $date_parts[2];

// if(!isset($_SESSION['auth'])){
//     header("Location: login.php?error_msg=Invalid Access");
// }
?>


<div class="container-fluid px-4">
    <h6></h6>
    <div class="card">
        <div class="card-header" style="background-color: #F16E04; color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="m-0">Daily Attendance Report</h4>
                <!-- Date Picker -->
                <form method="GET" action="report.php" class="d-flex align-items-center gap-2">
                    <label for="date" class="me-2 mb-0">Select Date:</label>
                    <input type="date" name="date" id="date" class="form-control" style="width: auto;" value="<?= $selected_date ?>" min="2020-01-01" max="<?= date('Y-m-d', strtotime('+1 year')) ?>">
                    <button type="submit" class="btn btn-light btn-sm">View Report</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3">Attendance Report for <?= date('F j, Y', strtotime($selected_date)) ?></h5>
                    
                    <?php
                    // Query to get attendance data for the selected date
                    $query = "SELECT 
                                sa.first_name, 
                                sa.last_name, 
                                a.time_in, 
                                a.time_out,
                                TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out) / 60.0 AS work_hours
                              FROM attendance a
                              JOIN student_assistant sa ON a.sa_id = sa.id
                              WHERE DATE(a.date) = '$selected_date'
                              ORDER BY a.time_in";
                    
                    $result = mysqli_query($con, $query);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-hover">';
                        echo '<thead class="table-dark">';
                        echo '<tr>';
                        echo '<th>Employee Name</th>';
                        echo '<th>Time In</th>';
                        echo '<th>Time Out</th>';
                        echo '<th>Work Hours</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        $total_hours = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $work_hours = $row['work_hours'] ?? 0;
                            $total_hours += $work_hours;
                            
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>';
                            echo '<td>' . ($row['time_in'] ? date('h:i A', strtotime($row['time_in'])) : 'N/A') . '</td>';
                            echo '<td>' . ($row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : 'N/A') . '</td>';
                            echo '<td>' . number_format($work_hours, 2) . ' hours</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        
                        // Summary card
                        echo '<div class="row mt-4">';
                        echo '<div class="col-md-4">';
                        echo '<div class="card bg-success text-white">';
                        echo '<div class="card-body">';
                        echo '<h5>Total Work Hours</h5>';
                        echo '<h3>' . number_format($total_hours, 2) . ' hours</h3>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="col-md-4">';
                        echo '<div class="card bg-info text-white">';
                        echo '<div class="card-body">';
                        echo '<h5>Total Employees</h5>';
                        echo '<h3>' . mysqli_num_rows($result) . '</h3>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        
                    } else {
                        echo '<div class="alert alert-info" role="alert">';
                        echo '<h5>No Attendance Data</h5>';
                        echo '<p>No attendance records found for ' . date('F j, Y', strtotime($selected_date)) . '</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
