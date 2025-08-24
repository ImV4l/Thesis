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

// Database connection
include('config/dbcon.php');

// Get student information from session
$student_id = $_SESSION['auth_user']['student_id'];

// Initialize variables
$first_name = 'Student';
$last_name = '';
$work = 'N/A';

// Fetch student's name and work assignment from the database
$query = "SELECT first_name, last_name, work FROM student_assistant WHERE student_id = '$student_id' LIMIT 1";
$query_run = mysqli_query($con, $query);

if ($query_run) {
    $student_data = mysqli_fetch_assoc($query_run);
    if ($student_data) {
        $first_name = $student_data['first_name'];
        $last_name = $student_data['last_name'];
        $work = $student_data['work'];
    }
}

?>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>My Weekly Schedule</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                    <h6 class="card-title">Student Name</h6>
                                    <p class="card-text fw-bold"><?= $first_name . ' ' . $last_name ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-id-card fa-2x text-info mb-2"></i>
                                    <h6 class="card-title">Student ID</h6>
                                    <p class="card-text fw-bold"><?= $student_id ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-briefcase fa-2x text-success mb-2"></i>
                                    <h6 class="card-title">Work Assignment</h6>
                                    <p class="card-text fw-bold"><?= $work ?></p>
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
                            <p>You don't have any scheduled work hours yet. Please contact your administrator to get a schedule.</p>
                        </div>
                    </div>
                </div>
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
    document.addEventListener('DOMContentLoaded', async function() {
        const calendarWeek = document.querySelector('.calendar-week');
        const scheduleSummary = document.getElementById('scheduleSummary');
        const noScheduleMessage = document.getElementById('noScheduleMessage');
        const studentId = '<?= $student_id ?>';
        let currentDate = new Date();

        try {
            const response = await fetch(`get_schedule.php?student_id=${studentId}`);
            const scheduleData = await response.json();

            if (scheduleData.length > 0) {
                renderScheduleSummary(scheduleData);
                renderCalendar(currentDate, scheduleData);
                scheduleSummary.style.display = 'block';
                document.querySelector('.calendar-container').style.display = 'block';
                noScheduleMessage.style.display = 'none';
            } else {
                scheduleSummary.style.display = 'none';
                document.querySelector('.calendar-container').style.display = 'none';
                noScheduleMessage.style.display = 'block';
            }
        } catch (error) {
            console.error('Error fetching schedule:', error);
            noScheduleMessage.style.display = 'block';
        }

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

                const daySchedules = scheduleData.filter(s => s.weekday === dayName);

                if (daySchedules.length > 0) {
                    daySchedules.forEach(schedule => {
                        const scheduleItem = document.createElement('div');
                        scheduleItem.classList.add('schedule-item');
                        scheduleItem.style.backgroundColor = '#28a745';
                        scheduleItem.style.color = 'white';
                        scheduleItem.style.fontWeight = 'bold';

                        const formatTime = (time) => {
                            let [hours, minutes] = time.split(':');
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12;
                            hours = hours ? hours : 12;
                            return `${hours}:${minutes} ${ampm}`;
                        };

                        const timeIn = formatTime(schedule.time_in);
                        const timeOut = formatTime(schedule.time_out);

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
?>
