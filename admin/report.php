<?php

include('authentication.php');
include('includes/header.php');

// Get selected year from the request or default to the current year
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// if(!isset($_SESSION['auth'])){
//     header("Location: login.php?error_msg=Invalid Access");
// }
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="mt-4">Monthly Attendance Report</h1>
        <!-- Year Picker -->
        <div class="mb-3">
            <form method="GET" action="report.php" class="d-flex align-items-center">
                <label for="year" class="me-2">Select Year:</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    <?php
                    // Generate year options (e.g., from 2020 to current year + 1)
                    $current_year = date('Y');
                    for ($year = 2020; $year <= $current_year + 1; $year++) {
                        $selected = $year == $selected_year ? 'selected' : '';
                        echo "<option value='$year' $selected>$year</option>";
                    }
                    ?>
                </select>
            </form>
        </div>
        
    </div>
    <ol class="breadcrumb mb-4">
    </ol>
    <div class="row">
        <?php
        // Array of months
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Loop through each month
        foreach ($months as $index => $month) {
            $month_number = $index + 1; // Months are 1-indexed
        ?>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body"><?= $month ?>
                        <?php
                        // Query to get total work hours for the month and selected year
                        $query = "SELECT SUM(TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out) / 60.0) AS total_hours
                                  FROM attendance a
                                  WHERE MONTH(a.date) = '$month_number'
                                  AND YEAR(a.date) = '$selected_year'";
                        $result = mysqli_query($con, $query);

                        if ($result && $row = mysqli_fetch_assoc($result)) {
                            $total_hours = $row['total_hours'] ?? 0;
                            echo '<h4 class="mb-0">' . number_format($total_hours, 2) . ' hours</h4>';
                        } else {
                            echo '<h4 class="mb-0">No Data</h4>';
                        }
                        ?>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="monthly_report.php?month=<?= $month_number ?>&year=<?= $selected_year ?>">View Details</a>
                        <div class="small text-white"></div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>