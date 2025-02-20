<?php
include('authentication.php');
include('includes/header.php');
include('includes/sidebar.php');



$work_filter = '';
if (isset($_GET['work'])) {
  $work_filter = mysqli_real_escape_string($con, $_GET['work']);
}

$query = "SELECT s.id, s.first_name, s.last_name, s.work, a.id AS attid, a.date, a.day, a.time_in, a.time_out, a.status 
          FROM student_assistant s 
          LEFT JOIN attendance a ON a.sa_id = s.id 
          WHERE s.work LIKE '%$work_filter%'";

$result = mysqli_query($con, $query);
?>



<div class="container-fluid px-4">
  <h4 class="mt-4">Student Assistants for: <?php echo htmlspecialchars($work_filter); ?></h4>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="tito_offices.php">Back to Office Work</a></li>
    <li class="breadcrumb-item active">Attendance Details</li>

  </ol>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Student Attendance Records
            <a class="btn btn-primary float-end" data-id='<?php echo $row['attid']; ?>'><i class='fa fa-print'></i> DTR</a>
          </h4>
        </div>
        <div class="card-body">
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
                <th>Option</th>
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
                  <td>
                    <button class='btn btn-success btn-sm btn-flat edit'
                      data-id='<?php echo $row['attid']; ?>'
                      data-timein='<?php echo date("H:i:s", strtotime($row['time_in'])); ?>'
                      data-timeout='<?php echo date("H:i:s", strtotime($row['time_out'])); ?>'
                      data-date='<?php echo $row['date']; ?>'>
                      <i class='fa fa-edit'></i> Edit
                    </button>
                    <button class='btn btn-danger btn-sm btn-flat delete' data-id='<?php echo $row['attid']; ?>'><i class='fa fa-trash'></i> Delete</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Attendance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editForm" method="POST" action="attendance_update.php">
        <div class="modal-body">
          <input type="hidden" name="attid" id="editAttId">
          <div class="mb-3">
            <label for="editDate" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" id="editDate" required>
          </div>
          <div class="mb-3">
            <label for="editTimeIn" class="form-label">Time In</label>
            <input type="time" step="1" class="form-control" name="time_in" id="editTimeIn" required>
          </div>
          <div class="mb-3">
            <label for="editTimeOut" class="form-label">Time Out</label>
            <input type="time" step="1" class="form-control" name="time_out" id="editTimeOut" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Attendance Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="deleteForm" method="POST" action="attendance_delete.php">
        <div class="modal-body">
          <input type="hidden" name="attid" id="deleteAttId">
          <p>Are you sure you want to delete this attendance record?</p>
          <p class="text-danger"><strong>This action cannot be undone!</strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete Record</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit').forEach(button => {
      button.addEventListener('click', function() {
        let attid = this.getAttribute('data-id');
        let timeIn = this.getAttribute('data-timein');
        let timeOut = this.getAttribute('data-timeout');
        let date = this.getAttribute('data-date');

        document.getElementById('editAttId').value = attid;
        document.getElementById('editTimeIn').value = timeIn;
        document.getElementById('editTimeOut').value = timeOut;
        document.getElementById('editDate').value = date;

        new bootstrap.Modal(document.getElementById('editModal')).show();
      });
    });

    document.getElementById('editForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);

      fetch('attendance_update.php', {
          method: 'POST',
          headers: {
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Attendance updated successfully!');
            location.reload();
          } else {
            alert('Failed to update attendance. Please check the server response.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while updating attendance.');
        });
    });

    // Delete button functionality
    document.querySelectorAll('.delete').forEach(button => {
      button.addEventListener('click', function() {
        let attid = this.getAttribute('data-id');
        document.getElementById('deleteAttId').value = attid;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
      });
    });

    // Delete form submission
    document.getElementById('deleteForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);

      fetch('attendance_delete.php', {
          method: 'POST',
          headers: {
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Attendance record deleted successfully!');
            location.reload();
          } else {
            alert('Failed to delete attendance record. Please check the server response.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while deleting the attendance record.');
        });
    });
  });
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>