<?php
include('authentication.php');
include('includes/header.php');

// Add this section at the top to display session messages
if (isset($_SESSION['status'])) {
    echo '<div class="alert alert-' . $_SESSION['status_type'] . ' alert-dismissible fade show" role="alert">
            ' . $_SESSION['status'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['status']);
    unset($_SESSION['status_type']);
}
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Student Schedules</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Student Schedules</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Student Schedules</h4>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Work In</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT student_id, last_name, first_name, work FROM student_assistant WHERE status1 = 'Active'";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['student_id']; ?></td>
                                        <td><?= $row['last_name']; ?></td>
                                        <td><?= $row['first_name']; ?></td>
                                        <td><?= $row['work']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm view-schedule-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewScheduleModal"
                                                data-id="<?= $row['student_id']; ?>"
                                                data-name="<?= $row['first_name'] . ' ' . $row['last_name']; ?>"
                                                data-work="<?= $row['work']; ?>">
                                                View Schedule
                                            </a>
                                            <a href="#" class="btn btn-success btn-sm add-schedule-btn" data-bs-toggle="modal" data-bs-target="#addScheduleModal" data-id="<?= $row['student_id']; ?>">Add Schedule</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5">No Record Found</td>
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

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="modalStudentId">

                    <div class="mb-3">
                        <label for="weekday" class="form-label">Weekday</label>
                        <select name="weekday" class="form-select" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="time_in" class="form-label">Time In</label>
                        <input type="time" name="time_in" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="time_out" class="form-label">Time Out</label>
                        <input type="time" name="time_out" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_schedule" class="btn btn-primary">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Schedule Modal -->
<div class="modal fade" id="viewScheduleModal" tabindex="-1" aria-labelledby="viewScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewScheduleModalLabel">Student Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Name:</strong> <span id="viewStudentName"></span></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Student ID:</strong> <span id="viewStudentId"></span></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Work In:</strong> <span id="viewWorkIn"></span></p>
                    </div>
                </div>

                <div class="calendar-container">
                    <div class="calendar-week">
                        <!-- Calendar days will be populated here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-container {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
    }

    .calendar-week {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }

    .calendar-day {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        min-height: 100px;
    }

    .calendar-day.active {
        background-color: #f8f9fa;
    }

    .calendar-day-header {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .schedule-item {
        font-size: 0.9em;
        padding: 2px 5px;
        background-color: #e9ecef;
        border-radius: 3px;
        margin-bottom: 3px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Add Schedule button clicks
        document.querySelectorAll('.add-schedule-btn').forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-id');
                document.getElementById('modalStudentId').value = studentId;
            });
        });

        const calendarWeek = document.querySelector('.calendar-week');
        const currentWeekDisplay = document.querySelector('.current-week');
        let currentDate = new Date();
        let currentScheduleData = []; // Add this line to store schedule data

        // Handle View Schedule button clicks
        document.querySelectorAll('.view-schedule-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const studentId = this.getAttribute('data-id');
                const studentName = this.getAttribute('data-name');
                const workIn = this.getAttribute('data-work');

                // Set modal content
                document.getElementById('viewStudentName').textContent = studentName;
                document.getElementById('viewStudentId').textContent = studentId;
                document.getElementById('viewWorkIn').textContent = workIn;

                // Fetch schedule data
                const response = await fetch(`get_schedule.php?student_id=${studentId}`);
                currentScheduleData = await response.json(); // Store the schedule data

                // Initialize calendar
                renderCalendar(currentDate, currentScheduleData);
            });
        });

        function renderCalendar(date, scheduleData = []) {
            const startOfWeek = new Date(date);
            startOfWeek.setDate(date.getDate() - date.getDay());

            calendarWeek.innerHTML = '';

            for (let i = 0; i < 7; i++) {
                const day = new Date(startOfWeek);
                day.setDate(startOfWeek.getDate() + i);

                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                if (day.toDateString() === new Date().toDateString()) {
                    dayElement.classList.add('active');
                }

                const dayHeader = document.createElement('div');
                dayHeader.classList.add('calendar-day-header');
                dayHeader.textContent = day.toLocaleDateString('en-US', {
                    weekday: 'long'
                });
                dayElement.appendChild(dayHeader);

                // Add schedule items
                const daySchedules = scheduleData.filter(s => {
                    return s.weekday === day.toLocaleDateString('en-US', {
                        weekday: 'long'
                    });
                });

                daySchedules.forEach(schedule => {
                    const scheduleItem = document.createElement('div');
                    scheduleItem.classList.add('schedule-item');

                    // Convert time to 12-hour format with AM/PM
                    const formatTime = (time) => {
                        let [hours, minutes] = time.split(':');
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // the hour '0' should be '12'
                        return `${hours}:${minutes} ${ampm}`;
                    };

                    const timeIn = formatTime(schedule.time_in);
                    const timeOut = formatTime(schedule.time_out);

                    scheduleItem.textContent = `${timeIn} - ${timeOut}`;
                    dayElement.appendChild(scheduleItem);
                });

                calendarWeek.appendChild(dayElement);
            }
        }
    });
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>