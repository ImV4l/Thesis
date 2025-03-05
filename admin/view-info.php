<?php
include('authentication.php');
include('includes/header.php');

// Initialize variables
$total_salary = 0;
$total_hours_worked = 0;

?>

<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4"></ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Student Assistant Information
                        <a href="view-register.php" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_GET['id'])) {
                        $user_id = $_GET['id'];
                        $users = "SELECT * FROM student_assistant WHERE id='$user_id'";
                        $users_run = mysqli_query($con, $users);

                        if (mysqli_num_rows($users_run) > 0) {
                            foreach ($users_run as $user) {
                                // Calculate total hours and salary
                                $attendance_query = "SELECT * FROM attendance WHERE sa_id = '$user_id'";
                                $attendance_run = mysqli_query($con, $attendance_query);

                                if (mysqli_num_rows($attendance_run) > 0) {
                                    while ($attendance = mysqli_fetch_assoc($attendance_run)) {
                                        if ($attendance['time_in'] && $attendance['time_out']) {
                                            $time_in = new DateTime($attendance['time_in']);
                                            $time_out = new DateTime($attendance['time_out']);
                                            $interval = $time_in->diff($time_out);
                                            $hours_worked = $interval->h + ($interval->i / 60);
                                            $total_hours_worked += $hours_worked;
                                            $total_salary += $hours_worked * 15;
                                        }
                                    }
                                }
                    ?>
                                <div class="row">
                                    <!-- Left Column - Personal Information -->
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #F16E04; color: white;">
                                                <h5 class="mb-0">Personal Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4 text-center">
                                                        <img src="../images/profile.png" class="img-fluid rounded-circle mb-3" alt="Profile Photo" style="width: 150px; height: 150px; object-fit: cover;">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h3><?= $user['last_name'] ?>, <?= $user['first_name'] ?></h3>
                                                        <h5><?= $user['student_id'] ?></h5>
                                                        <p class="text-muted"><?= $user['work'] ?></p>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label for="age" class="form-label">Age</label>
                                                        <input type="number" name="age" value="<?= $user['age']; ?>" class="form-control" id="age">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="sex" class="form-label">Sex</label>
                                                        <select name="sex" id="sex" class="form-select">
                                                            <option value="Male" <?= ($user['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                            <option value="Female" <?= ($user['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="civil_status" class="form-label">Civil Status</label>
                                                        <select name="civil_status" id="cs" class="form-select">
                                                            <option value="Single" <?= ($user['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                                            <option value="Divorced" <?= ($user['civil_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                                            <option value="Married" <?= ($user['civil_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                                            <option value="Widowed" <?= ($user['civil_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="dob" class="form-label">Date of Birth</label>
                                                        <input name="date_of_birth" value="<?= $user['date_of_birth']; ?>" class="form-control" id="dob">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="city_address" class="form-label">City Address</label>
                                                        <input name="city_address" value="<?= $user['city_address']; ?>" class="form-control" id="ct">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="contact_no" class="form-label">Contact No.</label>
                                                        <input name="contact_no1" value="<?= $user['contact_no1']; ?>" class="form-control" id="cn1">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="provincial_address" class="form-label">Provincial Address</label>
                                                        <input name="province_address" value="<?= $user['province_address']; ?>" class="form-control" id="prov">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="guardian" class="form-label">Guardian/s</label>
                                                        <input name="guardian" value="<?= $user['guardian']; ?>" class="form-control" id="gar">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="present_scholarship" class="form-label">Scholarship enjoyed at the present</label>
                                                        <input name="present_scholar" value="<?= $user['present_scholar']; ?>" class="form-control" id="scho1">

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="scholarship" class="form-label">Past Scholarship</label>
                                                        <input name="past_scholar" value="<?= $user['past_scholar']; ?>" class="form-control" id="scho">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="intent" class="form-label">I intend to enroll/continue this program</label>
                                                    <input name="program" value="<?= $user['program']; ?>" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Year</label>
                                                    <input name="year" value="<?= $user['year']; ?>" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="honors" class="form-label">Honors/Awards</label>
                                                    <input name="honor_award" value="<?= $user['honor_award']; ?>" class="form-control" id="hon">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="work_experience" class="form-label">Work Experiences</label>
                                                    <input name="work_experience" value="<?= $user['work_experience']; ?>" class="form-control" id="dis">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="talents" class="form-label">Special Talents</label>
                                                    <input name="special_talent" value="<?= $user['special_talent']; ?>" class="form-control" id="tal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column - References & Family -->
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #F16E04; color: white;">
                                                <h5 class="mb-0">References</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <label for="reference_name_outside" class="form-label">Outside WIT - Name</label>
                                                        <input type="text" name="out_name1" value="<?= $user['out_name1']; ?>" class="form-control" id="reference_name_outside">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="comp_add1" class="form-label">Company/Address</label>
                                                        <input type="text" name="comp_add1" value="<?= $user['comp_add1']; ?>" class="form-control" id="comp_add1">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="cn1" class="form-label">Contact No.</label>
                                                        <input type="tel" name="cn1" value="<?= $user['cn1']; ?>" class="form-control" id="cn1">
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <input type="text" name="out_name2" value="<?= $user['out_name2']; ?>" class="form-control" id="out_name2">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" name="comp_add2" value="<?= $user['comp_add2']; ?>" class="form-control" id="comp_add2">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="tel" name="cn2" value="<?= $user['cn2']; ?>" class="form-control" id="cn2">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <input type="text" name="out_name3" value="<?= $user['out_name3']; ?>" class="form-control" id="out_name3">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" name="comp_add3" value="<?= $user['comp_add3']; ?>" class="form-control" id="comp_add3">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="tel" name="cn3" value="<?= $user['cn3']; ?>" class="form-control" id="cn3">
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <label for="reference_name_wit" class="form-label">From WIT - Name</label>
                                                        <input type="text" name="from_wit1" value="<?= $user['from_wit1']; ?>" class="form-control" id="reference_name_wit">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="comp_add4" class="form-label">Company/Address</label>
                                                        <input type="text" name="comp_add4" value="<?= $user['comp_add4']; ?>" class="form-control" id="comp_add4">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="reference_contact_wit" class="form-label">Contact No.</label>
                                                        <input type="tel" name="cn4" value="<?= $user['cn4']; ?>" class="form-control" id="reference_contact_wit">
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <input type="text" name="from_wit2" value="<?= $user['from_wit2']; ?>" class="form-control" id="from_wit2">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" name="comp_add5" value="<?= $user['comp_add5']; ?>" class="form-control" id="comp_add5">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="tel" name="cn5" value="<?= $user['cn5']; ?>" class="form-control" id="cn5">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <input type="text" name="from_wit3" value="<?= $user['from_wit3']; ?>" class="form-control" id="from_wit3">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" name="comp_add6" value="<?= $user['comp_add6']; ?>" class="form-control" id="comp_add6">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="tel" name="cn6" value="<?= $user['cn6']; ?>" class="form-control" id="cn6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #F16E04; color: white;">
                                                <h5 class="mb-0">Family Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label for="fathers_name" class="form-label">Father's Name</label>
                                                        <input type="text" name="fathers_name" value="<?= $user['fathers_name']; ?>" class="form-control" id="fathers_name">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="fathers_occ" class="form-label">Occupation</label>
                                                        <input type="text" name="fathers_occ" value="<?= $user['fathers_occ']; ?>" class="form-control" id="fathers_occ">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="fathers_income" class="form-label">Father's Approx. Income/Mon.</label>
                                                        <input type="number" name="fathers_income" value="<?= $user['fathers_income']; ?>" class="form-control" id="fathers_income">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label for="mothers_name" class="form-label">Mother's Name</label>
                                                        <input type="text" name="mothers_name" value="<?= $user['mothers_name']; ?>" class="form-control" id="mothers_name">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="mothers_occ" class="form-label">Occupation</label>
                                                        <input type="text" name="mothers_occ" value="<?= $user['mothers_occ']; ?>" class="form-control" id="mothers_occ">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="mothers_income" class="form-label">Mother's Approx. Income/Mon.</label>
                                                        <input type="number" name="mothers_income" value="<?= $user['mothers_income']; ?>" class="form-control" id="mothers_income">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="siblings" class="form-label">Brothers & Sisters</label>
                                                    <textarea name="siblings" class="form-control" id="siblings" rows="3"><?= $user['siblings']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attendance Section -->
                                <div class="card mt-4">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #F16E04; color: white;">
                                        <h5 class="mb-0">Daily Time Record</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2" style="font-size: 0.8rem;">
                                                <div class="input-group input-group-sm">
                                                    <input type="date" id="startDate" class="form-control">
                                                    <span class="input-group-text">to</span>
                                                    <input type="date" id="endDate" class="form-control">
                                                </div>
                                            </div>
                                            <button class='btn btn-primary btn-sm payslip-btn me-2'
                                                data-id='<?= $user['id'] ?>'
                                                data-name='<?= $user['first_name'] ?> <?= $user['last_name'] ?>'
                                                data-work='<?= $user['work'] ?>'
                                                data-hours='<?= number_format($total_hours_worked, 2) ?>'
                                                data-rate='15'
                                                data-salary='<?= number_format($total_salary, 2) ?>'>
                                                <i class='fa fa-print'></i> DTR
                                            </button>
                                            <button class='btn btn-success btn-sm payroll-btn me-2'
                                                data-id='<?= $user['id'] ?>'
                                                data-name='<?= $user['first_name'] ?> <?= $user['last_name'] ?>'
                                                data-work='<?= $user['work'] ?>'
                                                data-hours='<?= number_format($total_hours_worked, 2) ?>'
                                                data-rate='15'
                                                data-salary='<?= number_format($total_salary, 2) ?>'>
                                                <i class='fa fa-print'></i> Payroll
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Time In</th>
                                                        <th>Time Out</th>
                                                        <th>Hours Worked</th>
                                                        <th>Salary (₱15/hour)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Fetch attendance records for this student assistant
                                                    $attendance_query = "SELECT * FROM attendance WHERE sa_id = '$user_id' ORDER BY date DESC";
                                                    $attendance_run = mysqli_query($con, $attendance_query);

                                                    if (mysqli_num_rows($attendance_run) > 0) {
                                                        while ($attendance = mysqli_fetch_assoc($attendance_run)) {
                                                            $time_in = new DateTime($attendance['time_in']);
                                                            $time_out = new DateTime($attendance['time_out']);
                                                            $interval = $time_in->diff($time_out);
                                                            $hours_worked = $interval->h + ($interval->i / 60);
                                                            $salary = $hours_worked * 15;
                                                    ?>
                                                            <tr>
                                                                <td><?= date('M d, Y', strtotime($attendance['date'])) ?></td>
                                                                <td><?= date('h:i A', strtotime($attendance['time_in'])) ?></td>
                                                                <td><?= date('h:i A', strtotime($attendance['time_out'])) ?></td>
                                                                <td><?= number_format($hours_worked, 2) ?> hours</td>
                                                                <td>₱<?= number_format($salary, 2) ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">No attendance records found</td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <?php
                                                    // Calculate total salary
                                                    $total_salary = 0;
                                                    $attendance_query = "SELECT * FROM attendance WHERE sa_id = '$user_id'";
                                                    $attendance_run = mysqli_query($con, $attendance_query);

                                                    if (mysqli_num_rows($attendance_run) > 0) {
                                                        while ($attendance = mysqli_fetch_assoc($attendance_run)) {
                                                            $time_in = new DateTime($attendance['time_in']);
                                                            $time_out = new DateTime($attendance['time_out']);
                                                            $interval = $time_in->diff($time_out);
                                                            $hours_worked = $interval->h + ($interval->i / 60);
                                                            $total_salary += $hours_worked * 15;
                                                        }
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th colspan="4" class="text-end">Total Salary</th>
                                                        <th>₱<?= number_format($total_salary, 2) ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                    <?php
                            }
                        } else {
                            echo '<div class="alert alert-warning">No Record Found</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Modify the date picker initialization
    document.addEventListener('DOMContentLoaded', function() {
        const currentDate = new Date();
        const startDate = new Date(currentDate.setDate(currentDate.getDate() - currentDate.getDay()));
        const endDate = new Date(currentDate.setDate(currentDate.getDate() + 6));

        // Format dates as MM-DD-YYYY
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${month}-${day}-${year}`;
        }

        document.getElementById('startDate').value = formatDate(startDate);
        document.getElementById('endDate').value = formatDate(endDate);
    });

    // Modify the Payroll Generation to include total salary calculation
    document.querySelectorAll('.payroll-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const name = this.getAttribute('data-name');
            const work = this.getAttribute('data-work');
            const userId = this.getAttribute('data-id');

            // Get selected dates from the date pickers
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            // Validate dates
            if (!startDateInput.value || !endDateInput.value) {
                alert('Please select both start and end dates');
                return;
            }

            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate > endDate) {
                alert('Start date cannot be after end date');
                return;
            }

            // Fetch attendance data for the selected range
            const response = await fetch(`get_attendance.php?user_id=${userId}&start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`);
            const attendanceData = await response.json();

            // Calculate total salary
            let totalSalary = 0;
            for (const times of Object.values(attendanceData)) {
                const amTimeIn = times.am.timeIn ? new Date(`2000-01-01T${times.am.timeIn}`) : null;
                const amTimeOut = times.am.timeOut ? new Date(`2000-01-01T${times.am.timeOut}`) : null;
                const pmTimeIn = times.pm.timeIn ? new Date(`2000-01-01T${times.pm.timeIn}`) : null;
                const pmTimeOut = times.pm.timeOut ? new Date(`2000-01-01T${times.pm.timeOut}`) : null;

                const amHours = amTimeIn && amTimeOut ? ((amTimeOut - amTimeIn) / (1000 * 60 * 60)) : 0;
                const pmHours = pmTimeIn && pmTimeOut ? ((pmTimeOut - pmTimeIn) / (1000 * 60 * 60)) : 0;
                totalSalary += (amHours + pmHours) * 15;
            }

            const content = `
                <div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #000;">
                    <h2 style="text-align: center;">Student Assistant Payroll</h2>
                    <p>Date Range: ${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}</p>
                    <hr>
                    <table style="width: 100%; border-collapse: collapse; text-align: center;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #000; padding: 10px;">Employee Name</th>
                                <th style="border: 1px solid #000; padding: 10px;">Work In</th>
                                <th style="border: 1px solid #000; padding: 10px;">Gross Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #000; padding: 10px;">${name}</td>
                                <td style="border: 1px solid #000; padding: 10px;">${work}</td>
                                <td style="border: 1px solid #000; padding: 10px;">₱${totalSalary.toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #000; padding: 10px;"><strong>Total</strong></td>
                                <td style="border: 1px solid #000; padding: 10px;">₱${totalSalary.toFixed(2)}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top: 30px;">
                        <p>Certified Correct:</p>
                        <p style="margin-top: 30px; text-align: center;">_______________________</p>
                        <p style="text-align: center;">Coordinator's Signature</p>
                    </div>
                </div>
            `;

            const printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(`<!DOCTYPE html>
                <html>
                <head>
                    <title>Payroll</title>
                    <style>
                        @media print {
                            button { display: none; }
                        }
                        body { margin: 0; padding: 20px; }
                        table { page-break-inside: auto; }
                        tr { page-break-inside: avoid; page-break-after: auto; }
                    </style>
                </head>
                <body>
                    ${content}
                    <button onclick="window.print()" style="margin-top: 20px;">Print/Save as PDF</button>
                </body>
                </html>`);
            printWindow.document.close();
        });
    });

    // DTR Generation
    document.querySelectorAll('.payslip-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const name = this.getAttribute('data-name');
            const work = this.getAttribute('data-work');
            const userId = this.getAttribute('data-id');

            // Get selected dates from the date pickers
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            // Validate dates
            if (!startDateInput.value || !endDateInput.value) {
                alert('Please select both start and end dates');
                return;
            }

            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate > endDate) {
                alert('Start date cannot be after end date');
                return;
            }

            // Fetch attendance data for the selected range
            const response = await fetch(`get_attendance.php?user_id=${userId}&start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`);
            const attendanceData = await response.json();

            const content = `
                <div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #000;">
                    <h2 style="text-align: center;">DAILY TIME RECORD</h2>
                    <div style="margin: 20px 0;">
                        <p><strong>Name:</strong> ${name}</p>
                        <p><strong>Work In:</strong> ${work}</p>
                        <p><strong>Date Range:</strong> ${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}</p>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #000; padding: 10px;">Date</th>
                                <th style="border: 1px solid #000; padding: 10px;" colspan="2">AM</th>
                                <th style="border: 1px solid #000; padding: 10px;" colspan="2">PM</th>
                                <th style="border: 1px solid #000; padding: 10px;">Total Hours</th>
                            </tr>
                            <tr>
                                <th style="border: 1px solid #000; padding: 10px;"></th>
                                <th style="border: 1px solid #000; padding: 10px;">Time In</th>
                                <th style="border: 1px solid #000; padding: 10px;">Time Out</th>
                                <th style="border: 1px solid #000; padding: 10px;">Time In</th>
                                <th style="border: 1px solid #000; padding: 10px;">Time Out</th>
                                <th style="border: 1px solid #000; padding: 10px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generateAttendanceRows(attendanceData)}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" style="border: 1px solid #000; padding: 10px; text-align: right;"><strong>Total Hours:</strong></td>
                                <td style="border: 1px solid #000; padding: 10px;">${calculateTotalHours(attendanceData)}</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="border: 1px solid #000; padding: 10px; text-align: left;">
                                    <p style="margin-top: 30px;">Certified Correct:</p>
                                    <p style="margin-top: 30px; text-align: center;">_______________________</p>
                                    <p style="text-align: center;">Supervisor's Signature</p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            const printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(`<!DOCTYPE html>
                <html>
                <head>
                    <title>Daily Time Record</title>
                    <style>
                        @media print {
                            button { display: none; }
                        }
                        body { margin: 0; padding: 20px; }
                        table { page-break-inside: auto; }
                        tr { page-break-inside: avoid; page-break-after: auto; }
                    </style>
                </head>
                <body>
                    ${content}
                    <button onclick="window.print()" style="margin-top: 20px;">Print/Save as PDF</button>
                </body>
                </html>`);
            printWindow.document.close();
        });
    });

    // Helper function to generate attendance rows
    function generateAttendanceRows(attendanceData) {
        let rows = '';
        let totalHours = 0;

        for (const [date, times] of Object.entries(attendanceData)) {
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString();

            // Calculate hours worked for AM and PM
            const amTimeIn = times.am.timeIn ? new Date(`2000-01-01T${times.am.timeIn}`) : null;
            const amTimeOut = times.am.timeOut ? new Date(`2000-01-01T${times.am.timeOut}`) : null;
            const pmTimeIn = times.pm.timeIn ? new Date(`2000-01-01T${times.pm.timeIn}`) : null;
            const pmTimeOut = times.pm.timeOut ? new Date(`2000-01-01T${times.pm.timeOut}`) : null;

            const amHours = amTimeIn && amTimeOut ? ((amTimeOut - amTimeIn) / (1000 * 60 * 60)).toFixed(2) : 0;
            const pmHours = pmTimeIn && pmTimeOut ? ((pmTimeOut - pmTimeIn) / (1000 * 60 * 60)).toFixed(2) : 0;
            const totalDayHours = (parseFloat(amHours) + parseFloat(pmHours)).toFixed(2);
            totalHours += parseFloat(totalDayHours);

            rows += `
                <tr>
                    <td style="border: 1px solid #000; padding: 10px;">${formattedDate}</td>
                    <td style="border: 1px solid #000; padding: 10px;">${times.am.timeIn ? formatTime(times.am.timeIn) : ''}</td>
                    <td style="border: 1px solid #000; padding: 10px;">${times.am.timeOut ? formatTime(times.am.timeOut) : ''}</td>
                    <td style="border: 1px solid #000; padding: 10px;">${times.pm.timeIn ? formatTime(times.pm.timeIn) : ''}</td>
                    <td style="border: 1px solid #000; padding: 10px;">${times.pm.timeOut ? formatTime(times.pm.timeOut) : ''}</td>
                    <td style="border: 1px solid #000; padding: 10px;">${totalDayHours}</td>
                </tr>
            `;
        }

        return rows;
    }

    // Helper function to calculate total hours
    function calculateTotalHours(attendanceData) {
        let totalHours = 0;

        for (const times of Object.values(attendanceData)) {
            const amTimeIn = times.am.timeIn ? new Date(`2000-01-01T${times.am.timeIn}`) : null;
            const amTimeOut = times.am.timeOut ? new Date(`2000-01-01T${times.am.timeOut}`) : null;
            const pmTimeIn = times.pm.timeIn ? new Date(`2000-01-01T${times.pm.timeIn}`) : null;
            const pmTimeOut = times.pm.timeOut ? new Date(`2000-01-01T${times.pm.timeOut}`) : null;

            const amHours = amTimeIn && amTimeOut ? ((amTimeOut - amTimeIn) / (1000 * 60 * 60)) : 0;
            const pmHours = pmTimeIn && pmTimeOut ? ((pmTimeOut - pmTimeIn) / (1000 * 60 * 60)) : 0;
            totalHours += amHours + pmHours;
        }

        return totalHours.toFixed(2);
    }

    // Helper function to format time
    function formatTime(timeStr) {
        const date = new Date(`2000-01-01T${timeStr}`);
        return date.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>