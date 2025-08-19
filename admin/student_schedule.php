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
    <h4></h4>
    <ol class="breadcrumb mb-4">
        
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
                            $query = "SELECT student_id, last_name, first_name, work FROM student_assistant WHERE status = '0'";
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F16E04; color: white;">
                <h5 class="modal-title" id="addScheduleModalLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Add New Schedule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="code.php" method="POST" id="addScheduleForm">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="modalStudentId">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instructions:</strong> Select the weekday and set the working hours for the student.
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-week me-2"></i>Select Weekday
                            </label>
                            <div class="weekday-selector">
                                <div class="row g-2">
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="monday" value="Monday" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="monday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Monday</div>
                                            <small class="text-muted">Start of week</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="tuesday" value="Tuesday" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="tuesday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Tuesday</div>
                                            <small class="text-muted">Second day</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="wednesday" value="Wednesday" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="wednesday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Wednesday</div>
                                            <small class="text-muted">Mid week</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="thursday" value="Thursday" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="thursday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Thursday</div>
                                            <small class="text-muted">Fourth day</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="friday" value="Friday" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="friday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Friday</div>
                                            <small class="text-muted">End of week</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="saturday" value="Saturday" required>
                                        <label class="btn btn-outline-warning w-100 py-3" for="saturday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Saturday</div>
                                            <small class="text-muted">Weekend</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <input type="radio" class="btn-check" name="weekday" id="sunday" value="Sunday" required>
                                        <label class="btn btn-outline-danger w-100 py-3" for="sunday">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <div>Sunday</div>
                                            <small class="text-muted">Rest day</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="time_in" class="form-label fw-bold">
                                    <i class="fas fa-clock me-2 text-success"></i>Time In
                                </label>
                                <input type="time" name="time_in" id="time_in" class="form-control form-control-lg" required>
                                <div class="form-text">Select the start time for work</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="time_out" class="form-label fw-bold">
                                    <i class="fas fa-clock me-2 text-danger"></i>Time Out
                                </label>
                                <input type="time" name="time_out" id="time_out" class="form-control form-control-lg" required>
                                <div class="form-text">Select the end time for work</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning" id="timeValidation" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Time Out must be later than Time In!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" name="add_schedule" class="btn btn-success" id="saveScheduleBtn">
                        <i class="fas fa-save me-2"></i>Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Schedule Modal -->
<div class="modal fade" id="viewScheduleModal" tabindex="-1" aria-labelledby="viewScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F16E04; color: white;">
                <h5 class="modal-title" id="viewScheduleModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>Student Weekly Schedule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                <h6 class="card-title">Student Name</h6>
                                <p class="card-text fw-bold" id="viewStudentName"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-id-card fa-2x text-info mb-2"></i>
                                <h6 class="card-title">Student ID</h6>
                                <p class="card-text fw-bold" id="viewStudentId"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-briefcase fa-2x text-success mb-2"></i>
                                <h6 class="card-title">Work Assignment</h6>
                                <p class="card-text fw-bold" id="viewWorkIn"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="schedule-summary mb-4" id="scheduleSummary">
                    <!-- Schedule summary will be populated here -->
                </div>

                <div class="calendar-container">
                    <h6 class="mb-3">
                        <i class="fas fa-calendar-week me-2"></i>Weekly Schedule Overview
                    </h6>
                    <div class="calendar-week">
                        <!-- Calendar days will be populated here -->
                    </div>
                </div>

                <div class="no-schedule-message" id="noScheduleMessage" style="display: none;">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h5>No Schedule Found</h5>
                        <p>This student doesn't have any scheduled work hours yet. Use the "Add Schedule" button to create a schedule.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
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
        const timeInInput = document.getElementById('time_in');
        const timeOutInput = document.getElementById('time_out');
        const timeValidation = document.getElementById('timeValidation');
        const saveScheduleBtn = document.getElementById('saveScheduleBtn');
        const addScheduleForm = document.getElementById('addScheduleForm');

        // Handle Add Schedule button clicks
        document.querySelectorAll('.add-schedule-btn').forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-id');
                document.getElementById('modalStudentId').value = studentId;
                
                // Reset form
                addScheduleForm.reset();
                timeValidation.style.display = 'none';
                saveScheduleBtn.disabled = false;
            });
        });

        // Time validation
        function validateTime() {
            const timeIn = timeInInput.value;
            const timeOut = timeOutInput.value;
            
            if (timeIn && timeOut) {
                if (timeOut <= timeIn) {
                    timeValidation.style.display = 'block';
                    saveScheduleBtn.disabled = true;
                    return false;
                } else {
                    timeValidation.style.display = 'none';
                    saveScheduleBtn.disabled = false;
                    return true;
                }
            }
            return true;
        }

        // Add event listeners for time validation
        timeInInput.addEventListener('change', validateTime);
        timeOutInput.addEventListener('change', validateTime);

        // Form submission validation
        addScheduleForm.addEventListener('submit', function(e) {
            if (!validateTime()) {
                e.preventDefault();
                return false;
            }
        });

        const calendarWeek = document.querySelector('.calendar-week');
        const scheduleSummary = document.getElementById('scheduleSummary');
        const noScheduleMessage = document.getElementById('noScheduleMessage');
        let currentDate = new Date();
        let currentScheduleData = [];

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

                try {
                    // Fetch schedule data
                    const response = await fetch(`get_schedule.php?student_id=${studentId}`);
                    currentScheduleData = await response.json();

                    if (currentScheduleData.length > 0) {
                        // Show schedule
                        renderScheduleSummary(currentScheduleData);
                        renderCalendar(currentDate, currentScheduleData);
                        scheduleSummary.style.display = 'block';
                        document.querySelector('.calendar-container').style.display = 'block';
                        noScheduleMessage.style.display = 'none';
                    } else {
                        // Show no schedule message
                        scheduleSummary.style.display = 'none';
                        document.querySelector('.calendar-container').style.display = 'none';
                        noScheduleMessage.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error fetching schedule:', error);
                    noScheduleMessage.style.display = 'block';
                }
            });
        });

        function renderScheduleSummary(scheduleData) {
            let totalHours = 0;
            let scheduledDays = scheduleData.length;
            
            scheduleData.forEach(schedule => {
                const timeIn = new Date(`2000-01-01 ${schedule.time_in}`);
                const timeOut = new Date(`2000-01-01 ${schedule.time_out}`);
                const hours = (timeOut - timeIn) / (1000 * 60 * 60);
                totalHours += hours;
            });

            scheduleSummary.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                <h5>Scheduled Days</h5>
                                <h3>${scheduledDays}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h5>Total Hours/Week</h5>
                                <h3>${totalHours.toFixed(1)}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <h5>Avg Hours/Day</h5>
                                <h3>${(totalHours / scheduledDays).toFixed(1)}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderCalendar(date, scheduleData = []) {
            const startOfWeek = new Date(date);
            startOfWeek.setDate(date.getDate() - date.getDay());

            calendarWeek.innerHTML = '';

            const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            for (let i = 0; i < 7; i++) {
                const day = new Date(startOfWeek);
                day.setDate(startOfWeek.getDate() + i);
                const dayName = weekdays[i];

                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                
                // Highlight today
                if (day.toDateString() === new Date().toDateString()) {
                    dayElement.classList.add('active');
                    dayElement.style.backgroundColor = '#e3f2fd';
                }

                const dayHeader = document.createElement('div');
                dayHeader.classList.add('calendar-day-header');
                dayHeader.innerHTML = `
                    <div style="color: #F16E04; font-weight: bold;">${dayName}</div>
                    <small class="text-muted">${day.getDate()}/${day.getMonth() + 1}</small>
                `;
                dayElement.appendChild(dayHeader);

                // Add schedule items for this day
                const daySchedules = scheduleData.filter(s => s.weekday === dayName);

                if (daySchedules.length > 0) {
                    daySchedules.forEach(schedule => {
                        const scheduleItem = document.createElement('div');
                        scheduleItem.classList.add('schedule-item');
                        scheduleItem.style.backgroundColor = '#28a745';
                        scheduleItem.style.color = 'white';
                        scheduleItem.style.fontWeight = 'bold';

                        // Convert time to 12-hour format with AM/PM
                        const formatTime = (time) => {
                            let [hours, minutes] = time.split(':');
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12;
                            hours = hours ? hours : 12;
                            return `${hours}:${minutes} ${ampm}`;
                        };

                        const timeIn = formatTime(schedule.time_in);
                        const timeOut = formatTime(schedule.time_out);

                        // Calculate duration
                        const timeInDate = new Date(`2000-01-01 ${schedule.time_in}`);
                        const timeOutDate = new Date(`2000-01-01 ${schedule.time_out}`);
                        const duration = (timeOutDate - timeInDate) / (1000 * 60 * 60);

                        scheduleItem.innerHTML = `
                            <div><i class="fas fa-clock me-1"></i>${timeIn} - ${timeOut}</div>
                            <small>(${duration.toFixed(1)} hours)</small>
                        `;
                        dayElement.appendChild(scheduleItem);
                    });
                } else {
                    const noScheduleItem = document.createElement('div');
                    noScheduleItem.style.color = '#6c757d';
                    noScheduleItem.style.fontStyle = 'italic';
                    noScheduleItem.style.textAlign = 'center';
                    noScheduleItem.style.marginTop = '20px';
                    noScheduleItem.innerHTML = '<small>No schedule</small>';
                    dayElement.appendChild(noScheduleItem);
                }

                calendarWeek.appendChild(dayElement);
            }
        }
    });
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
