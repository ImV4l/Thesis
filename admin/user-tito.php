<?php 
include('authentication.php');
include('includes/user-header.php');

$today = date('Y-m-d');
$query = "SELECT s.id, s.last_name, s.first_name, s.program, s.year, s.work, a.date, a.day, a.time_in, a.time_out, a.status 
          FROM student_assistant s 
          INNER JOIN attendance a ON s.id = a.sa_id 
          WHERE a.date = '$today'";
$result = mysqli_query($con, $query);
?>

<div class="container-fluid px-4">
     <h4 class="mt-4">Student Assistants Timed In Today</h4>
     <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Attendance</li>
    </ol>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">  
                    <h4>Attendance</h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Program</th>
                                <th>Year</th>
                                <th>Work In</th>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['program']); ?></td>
                                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                                    <td><?php echo htmlspecialchars($row['work']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                                    <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                                    <td><?php echo htmlspecialchars($row['time_out']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">No student assistants timed in today.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>

<?php 
include('includes/scripts.php');
?>