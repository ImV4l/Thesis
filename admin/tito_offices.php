<?php

include('authentication.php');
include('includes/header.php');
include('includes/sidebar.php');

$query_office = "SELECT w.work_name, COUNT(a.id) AS total 
    FROM student_assistant sa 
    JOIN work w ON sa.work LIKE CONCAT('%', w.work_name, '%') 
    LEFT JOIN attendance a ON a.sa_id = sa.id
    WHERE w.type = 'Office'
    GROUP BY w.work_name
    ORDER BY total DESC";
$result_office = mysqli_query($con, $query_office);

$colors = ["bg-primary", "bg-success", "bg-warning", "bg-info", "bg-danger"];
$i = 0;
?>
<div class="container-fluid px-4">
    <h3>Office</h3>
    <h4 class="mt-4">Time Log Report</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Attendance</li>
        <li class="breadcrumb-item active">Time in / Time out</li>
    </ol>
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result_office)): ?>
            <?php $colorClass = $colors[$i % count($colors)]; $i++; ?>
            <div class="col-xl-3 col-md-6">
                <div class="card <?php echo $colorClass; ?> text-white mb-4">
                    <div class="card-body">
                        <?php echo $row['work_name']; ?><br>
                        <h4 class="mb-0"><?php echo $row['total']; ?></h4>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="attendance_detail.php?work=<?php echo urlencode($row['work_name']); ?>">View</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>