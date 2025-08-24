<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Student Assistants</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Manpower Services</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Manpower Services</h4>
                        <div class="dropdown">
                            <select id="serviceFilter" class="form-select" style="background-color: white; color: black; min-width: 200px;">
                                <option value="">All Services</option>
                                <?php
                                // Get all services from work table
                                $service_query = "SELECT DISTINCT work_name FROM work WHERE type = 'Manpower Services' ORDER BY work_name";
                                $service_result = mysqli_query($con, $service_query);
                                
                                if (mysqli_num_rows($service_result) > 0) {
                                    while ($service_row = mysqli_fetch_assoc($service_result)) {
                                        $selected = (isset($_GET['service']) && $_GET['service'] == $service_row['work_name']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($service_row['work_name']) . '" ' . $selected . '>' . htmlspecialchars($service_row['work_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Program</th>
                                <th>Year</th>
                                <th>Work In</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Build the query with optional service filter
                            $service_filter = isset($_GET['service']) && !empty($_GET['service']) ? $_GET['service'] : '';
                            
                            $query = "
                           SELECT sa.*, w.work_name 
                           FROM student_assistant sa
                           JOIN work w 
                           ON sa.work LIKE CONCAT('%', w.work_name, '%')
                           WHERE w.type = 'Manpower Services'";
                           
                            // Add service filter if selected
                            if (!empty($service_filter)) {
                                $query .= " AND w.work_name = '" . mysqli_real_escape_string($con, $service_filter) . "'";
                            }
                            
                            $query .= " ORDER BY sa.last_name, sa.first_name";
                            
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['student_id']; ?></td>
                                        <td><?= $row['last_name']; ?></td>
                                        <td><?= $row['first_name']; ?></td>
                                        <td><?= $row['program']; ?></td>
                                        <td><?= $row['year']; ?></td>
                                        <td><?= $row['work']; ?></td>
                                        <td style="color: <?= ($row['status1'] == '0') ? 'green' : 'red'; ?>">
                                            <?= ($row['status1'] == '0') ? 'Active' : 'Not Active'; ?>
                                        </td>
                                    <?php
                                }
                            } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No Record Found</td>
                                    </tr>
                                <?php
                            }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceFilter = document.getElementById('serviceFilter');
    
    serviceFilter.addEventListener('change', function() {
        const selectedService = this.value;
        const currentUrl = new URL(window.location.href);
        
        if (selectedService === '') {
            // Remove service parameter if "All Services" is selected
            currentUrl.searchParams.delete('service');
        } else {
            // Set service parameter to selected value
            currentUrl.searchParams.set('service', selectedService);
        }
        
        // Reload the page with the new URL
        window.location.href = currentUrl.toString();
    });
});
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
