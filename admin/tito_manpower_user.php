<?php
include('authentication.php');  
include('includes/user-header.php');
include('includes/user-sidebar.php');
include('config/dbcon.php'); // Add database connection

// Query to get manpower services attendance data
$query_manpower = "SELECT w.work_name, COUNT(a.id) AS total 
    FROM work w 
    LEFT JOIN student_assistant sa ON sa.work LIKE CONCAT('%', w.work_name, '%') 
    LEFT JOIN attendance a ON a.sa_id = sa.id
    WHERE w.type = 'Manpower Services' OR w.type = 'Manpower' OR w.type LIKE '%Manpower%'
    GROUP BY w.work_name
    ORDER BY total DESC";
$result_manpower = mysqli_query($con, $query_manpower);

$colors = ["bg-primary", "bg-success", "bg-warning", "bg-info", "bg-danger"];
$i = 0;
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Manpower Services Attendance</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="user-tito.php">Attendance</a></li>
                <li class="breadcrumb-item active">Manpower Services</li>
            </ol>
            
            <div class="row">
                <?php if(mysqli_num_rows($result_manpower) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result_manpower)): ?>
                        <?php $colorClass = $colors[$i % count($colors)]; $i++; ?>
                        <div class="col-xl-3 col-md-6">
                            <div class="card <?php echo $colorClass; ?> text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($row['work_name']); ?></h6>
                                            <h4 class="mb-0"><?php echo $row['total']; ?></h4>
                                            <small>Student Assistants</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="attendance_detail.php?work=<?php echo urlencode($row['work_name']); ?>">
                                        View Details
                                    </a>
                                    <div class="small text-white">
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Manpower Services Data Available</h5>
                                <p class="text-muted">There are currently no manpower services attendance records to display.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Summary Card -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-bar me-2"></i>Manpower Services Attendance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h4 class="text-primary"><?php echo mysqli_num_rows($result_manpower); ?></h4>
                                        <p class="text-muted">Total Manpower Services</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <?php 
                                        $total_query = "SELECT COUNT(DISTINCT a.sa_id) as total_sa 
                                                       FROM attendance a 
                                                       INNER JOIN student_assistant sa ON a.sa_id = sa.id 
                                                       INNER JOIN work w ON sa.work LIKE CONCAT('%', w.work_name, '%') 
                                                       WHERE w.type = 'Manpower Services' OR w.type = 'Manpower' OR w.type LIKE '%Manpower%'";
                                        $total_result = mysqli_query($con, $total_query);
                                        $total_sa = mysqli_fetch_assoc($total_result)['total_sa'];
                                        ?>
                                        <h4 class="text-success"><?php echo $total_sa; ?></h4>
                                        <p class="text-muted">Total Student Assistants</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <?php 
                                        $today_query = "SELECT COUNT(DISTINCT a.sa_id) as today_sa 
                                                       FROM attendance a 
                                                       INNER JOIN student_assistant sa ON a.sa_id = sa.id 
                                                       INNER JOIN work w ON sa.work LIKE CONCAT('%', w.work_name, '%') 
                                                       WHERE (w.type = 'Manpower Services' OR w.type = 'Manpower' OR w.type LIKE '%Manpower%') AND a.date = CURDATE()";
                                        $today_result = mysqli_query($con, $today_query);
                                        $today_sa = mysqli_fetch_assoc($today_result)['today_sa'];
                                        ?>
                                        <h4 class="text-warning"><?php echo $today_sa; ?></h4>
                                        <p class="text-muted">Today's Attendance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
