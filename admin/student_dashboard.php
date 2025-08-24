<?php
session_start();
include('includes/student_header.php');
include('includes/navbar.php');

// Initialize variables
$total_salary = 0;
$total_hours_worked = 0;

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

// Fetch student data including image
$query = "SELECT first_name, last_name, student_id, work, age, sex, civil_status, date_of_birth, city_address, 
                 contact_no1, province_address, guardian, present_scholar, 
                 past_scholar, program, year, honor_award, work_experience, 
                 special_talent, out_name1, comp_add1, cn1, out_name2, comp_add2, 
                 cn2, out_name3, comp_add3, cn3, from_wit1, comp_add4, cn4, 
                 from_wit2, comp_add5, cn5, from_wit3, comp_add6, cn6, 
                 fathers_name, fathers_occ, fathers_income, mothers_name, mothers_occ, mothers_income, siblings,
                 image 
          FROM student_assistant 
          WHERE student_id = '$student_id' 
          LIMIT 1";
$query_run = mysqli_query($con, $query);

if (mysqli_num_rows($query_run) > 0) {
    $student = mysqli_fetch_assoc($query_run);

    // Check if required fields exist
    if (!isset($student['last_name']) || !isset($student['first_name'])) {
        die("Required name fields are missing in the database");
    }
} else {
    $_SESSION['message'] = "Student data not found";
    header("Location: login.php");
    exit(0);
}

// After fetching student data, add this code
$student_id = (int)$student_id; // Ensure student_id is an integer

// Use session data for the name
$first_name = $_SESSION['auth_user']['first_name'];
$last_name = $_SESSION['auth_user']['last_name'];

// Display profile picture
$profile_picture = $student['image'] ? '../uploads/profiles/' . $student['image'] : 'assets/default-profile.jpg';
?>

<style>
    .student-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        max-width: 95vw;
        margin: 2rem auto;
    }

    .student-header {
        background: linear-gradient(45deg, #F16E04, #FF9F4B);
        border-radius: 20px 20px 0 0;
        padding: 2rem;
        text-align: center;
    }

    .student-body {
        padding: 2.5rem;
    }

    .info-item {
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .info-label {
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .info-value {
        font-size: 1.15rem;
        color: #333;
        padding-left: 0.5rem;
    }

    .profile-picture-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 20px;
        border: 3px solid #F16E04;
    }

    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .section-title {
        color: #F16E04;
        margin-bottom: 2rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #F16E04;
        font-size: 1.5rem;
    }

    .container {
        max-width: 100%;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    .card {
        margin-bottom: 2rem;
    }

    .row {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    .col-md-4,
    .col-md-6,
    .col-md-8,
    .col-md-12 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .compact-info .info-item {
        margin-bottom: 1rem;
        padding: 0.75rem;
    }

    .compact-info .info-label {
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .compact-info .info-value {
        font-size: 1rem;
        padding-left: 0.25rem;
    }

    .compact-info .row {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }

    .compact-info [class^="col-"] {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    /* Add these new styles for the right column */
    .compact-right .card {
        margin-bottom: 1rem;
        /* Reduced from 2rem */
    }

    .compact-right .info-item {
        margin-bottom: 0.75rem;
        /* Reduced from 1.5rem */
        padding: 0.5rem;
        /* Reduced from 1.25rem */
    }

    .compact-right .info-label {
        font-size: 0.9rem;
        /* Reduced from 1rem */
        margin-bottom: 0.25rem;
        /* Reduced from 0.5rem */
    }

    .compact-right .info-value {
        font-size: 0.95rem;
        /* Reduced from 1.15rem */
        padding-left: 0.25rem;
        /* Reduced from 0.5rem */
    }

    .compact-right .row {
        margin-left: -0.5rem;
        /* Reduced gutter spacing */
        margin-right: -0.5rem;
    }

    .compact-right [class^="col-"] {
        padding-left: 0.5rem;
        /* Reduced gutter spacing */
        padding-right: 0.5rem;
    }

    /* Add this new style for the references header */
    .references-header {
        background: linear-gradient(45deg, #F16E04, #FF9F4B);
        color: white;
        padding: 1rem;
        border-radius: 10px 10px 0 0;
        margin-bottom: 1rem;
    }

    /* Add this to your existing styles */
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
                <div class="card student-card border-0 overflow-hidden">
                    <div class="student-header">
                        <h3 class="text-white mb-0 fw-bold"><i class="fas fa-user-graduate me-2"></i>Student Dashboard</h3>
                    </div>
                    <div class="card-body student-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-12 compact-info">
                                <!-- Personal Information Header -->
                                <div class="mb-3">
                                    <div class="references-header">
                                        <h5 class="mb-0">Personal Information</h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 text-center">
                                        <div class="profile-picture-container">
                                            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-picture">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h4><?= htmlspecialchars($student['last_name'] ?? '') ?>, <?= htmlspecialchars($student['first_name'] ?? 'N/A') ?></h4>
                                        <h6><?= htmlspecialchars($student['student_id'] ?? 'N/A') ?></h6>
                                        <p class="text-muted small"><?= htmlspecialchars($student['work'] ?? 'N/A') ?></p>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <div class="info-label">Age</div>
                                            <div class="info-value"><?= htmlspecialchars($student['age'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <div class="info-label">Sex</div>
                                            <div class="info-value"><?= htmlspecialchars($student['sex'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <div class="info-label">Civil Status</div>
                                            <div class="info-value"><?= htmlspecialchars($student['civil_status'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-item">
                                            <div class="info-label">Date of Birth</div>
                                            <div class="info-value">
                                                <?= htmlspecialchars($student['date_of_birth'] ? date('F j, Y', strtotime($student['date_of_birth'])) : 'N/A') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Address</div>
                                            <div class="info-value">
                                                <?php
                                                $city = htmlspecialchars($student['province_address'] ?? '');
                                                $province = htmlspecialchars($student['city_address'] ?? '');
                                                echo trim($city . ($city && $province ? ', ' : '') . $province) ?: 'N/A';
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Contact Number</div>
                                            <div class="info-value"><?= htmlspecialchars($student['contact_no1'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Guardian</div>
                                            <div class="info-value"><?= htmlspecialchars($student['guardian'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Current Scholarship</div>
                                            <div class="info-value"><?= htmlspecialchars($student['present_scholar'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Past Scholarship</div>
                                            <div class="info-value"><?= htmlspecialchars($student['past_scholar'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Program</div>
                                            <div class="info-value"><?= htmlspecialchars($student['program'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Year</div>
                                            <div class="info-value"><?= htmlspecialchars($student['year'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Honors/Awards</div>
                                            <div class="info-value"><?= htmlspecialchars($student['honor_award'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Work Experience</div>
                                            <div class="info-value"><?= htmlspecialchars($student['work_experience'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Special Talents</div>
                                            <div class="info-value"><?= htmlspecialchars($student['special_talent'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Right Column -->
                            <div class="col-md-12 compact-right">


                                <!-- Family Information Section -->
                                <div class="mb-3">
                                    <div class="references-header">
                                        <h5 class="mb-0">Family Information</h5>
                                    </div>
                                    <div>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <div class="info-label">Father's Name</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['fathers_name'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <div class="info-label">Occupation</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['fathers_occ'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <div class="info-label">Income/Month</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['fathers_income'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 mt-2">
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <div class="info-label">Mother's Name</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['mothers_name'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <div class="info-label">Occupation</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['mothers_occ'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <div class="info-label">Income/Month</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['mothers_income'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 mt-2">
                                            <div class="col-md-12">
                                                <div class="info-item">
                                                    <div class="info-label">Siblings</div>
                                                    <div class="info-value"><?= htmlspecialchars($student['siblings'] ?? 'N/A') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
