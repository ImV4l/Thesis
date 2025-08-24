<?php
session_start();
include('includes/student_header.php');
include('includes/navbar.php');

// Check if user is logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth_role'] != 'student') {
    $_SESSION['message'] = "You are not authorized to access this page";
    header("Location: login.php");
    exit(0);
}
?>

<style>
    .references-header {
        background: linear-gradient(45deg, #F16E04, #FF9F4B);
        color: white;
        padding: 1rem;
        border-radius: 10px 10px 0 0;
        margin-bottom: 1rem;
    }

    .input-group-sm .input-group-text {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
    }

    .input-group-sm .form-control {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .input-group-sm {
        max-width: 400px;
    }

    .input-group-sm .btn {
        padding: 0.25rem 0.75rem;
        border-radius: 0 0.25rem 0.25rem 0;
        white-space: nowrap;
    }

    .input-group-sm .btn i {
        margin: 0;
    }
</style>

<div class="py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Add Daily Time Record Section -->
                <div class="card mt-4">
                    <div class="card-header references-header">
                        <h5 class="mb-0 text-white">Daily Time Record
                            <div class="float-end">
                                <div class="d-flex align-items-center" style="max-width: 500px;">
                                    <div class="input-group input-group-sm">
                                        <input type="date" id="startDate" class="form-control">
                                        <span class="input-group-text">to</span>
                                        <input type="date" id="endDate" class="form-control">
                                        <button class="btn btn-primary" onclick="filterAttendance()">
                                            <i class="fa fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="attendanceTable">
                            <!-- Table content will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterAttendance() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (!startDate || !endDate) {
            alert('Please select both start and end dates');
            return;
        }

        fetch(`student_dtr.php?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('attendanceTable').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    }

    // Initialize date pickers with default range (current month)
    document.addEventListener('DOMContentLoaded', function() {
        const currentDate = new Date();
        const startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const endDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

        document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('endDate').value = endDate.toISOString().split('T')[0];

        // Load initial data
        filterAttendance();
    });
</script>

<?php
include('includes/footer.php');
?>
