<?php
include('authentication.php');
include('includes/user-header.php');
include('includes/user-sidebar.php');



$work_filter = '';
if (isset($_GET['work'])) {
  $work_filter = mysqli_real_escape_string($con, $_GET['work']);
}

$date_filter = '';
if (isset($_GET['date'])) {
  $date_filter = mysqli_real_escape_string($con, $_GET['date']);
}

$query = "SELECT s.id, s.first_name, s.last_name, s.work, a.id AS attid, a.date, a.day, a.time_in, a.time_out, a.status 
          FROM student_assistant s 
          LEFT JOIN attendance a ON a.sa_id = s.id 
          WHERE s.work LIKE '%$work_filter%'";
          
if (!empty($date_filter)) {
  $query .= " AND a.date = '$date_filter'";
}

$result = mysqli_query($con, $query);
?>



<div class="container-fluid px-4">
  <h4 class="mt-4">Student Assistants for: <?php echo htmlspecialchars($work_filter); ?></h4>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="tito_offices_user.php">Back to Office Work</a></li>
    <li class="breadcrumb-item active">Attendance Details</li>

  </ol>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h4>Student Attendance Records</h4>
            </div>
            <div class="col-md-6">
              <div class="d-flex justify-content-end">
                <div class="input-group" style="max-width: 250px;">
                  <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  <input type="date" class="form-control" id="dateFilter" value="<?php echo htmlspecialchars($date_filter); ?>" onchange="filterByDate()">
                  <button class="btn btn-outline-secondary" type="button" onclick="clearDateFilter()" title="Clear Date Filter">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="myTable" class="table table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Student Name</th>
                  <th>Work</th>
                  <th>Date</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Work Hour</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <?php
                  $num_hour = '-';
                  if (!empty($row['time_in']) && !empty($row['time_out'])) {
                    $time_in = new DateTime($row['time_in']);
                    $time_out = new DateTime($row['time_out']);
                    $interval = $time_in->diff($time_out);
                    $num_hour = $interval->format('%H:%I:%S');
                  }
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['work']); ?></td>
                    <td><?php echo htmlspecialchars($row['date'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['time_in'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['time_out'] ?? ''); ?></td>
                    <td><?php echo $num_hour; ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  // Date filter functions
  function filterByDate() {
    const selectedDate = document.getElementById('dateFilter').value;
    const currentUrl = new URL(window.location);
    
    if (selectedDate) {
      currentUrl.searchParams.set('date', selectedDate);
    } else {
      currentUrl.searchParams.delete('date');
    }
    
    window.location.href = currentUrl.toString();
  }
  
  function clearDateFilter() {
    document.getElementById('dateFilter').value = '';
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.delete('date');
    window.location.href = currentUrl.toString();
  }

</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?> 