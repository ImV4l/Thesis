<?php
include('authentication.php');
include('includes/header.php');

if (!isset($_GET['month'])) {
    header('Location: report.php');
    exit();
}

$month = intval($_GET['month']);
$month_name = date('F', mktime(0, 0, 0, $month, 10)); // Get month name from number
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Attendance Report for <?= $month_name ?></h1>
    <ol class="breadcrumb mb-4">
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Attendance Details</h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Hours Worked</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT a.date, sa.first_name, sa.last_name, a.time_in, a.time_out,
                                     TIMESTAMPDIFF(MINUTE, a.time_in, a.time_out) / 60.0 AS hours_worked
                                     FROM attendance a
                                     JOIN student_assistant sa ON a.sa_id = sa.id
                                     WHERE MONTH(a.date) = '$month'
                                     AND a.time_in IS NOT NULL
                                     AND a.time_out IS NOT NULL
                                     ORDER BY a.date ASC";
                            $result = mysqli_query($con, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>" . date('M d, Y', strtotime($row['date'])) . "</td>
                                            <td>{$row['first_name']} {$row['last_name']}</td>
                                            <td>" . date('h:i A', strtotime($row['time_in'])) . "</td>
                                            <td>" . date('h:i A', strtotime($row['time_out'])) . "</td>
                                            <td>" . number_format($row['hours_worked'], 2) . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No Records Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>