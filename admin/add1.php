<?php
session_start();
include('authentication.php');
include('includes/header.php');

if (!isset($_SESSION['sa_form_data'])) {
    $_SESSION['sa_form_data'] = array();
}

$formData = $_SESSION['sa_form_data'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['sa_form_data'] = array_merge($_SESSION['sa_form_data'], $_POST);
    header('Location: add2.php');
    exit();
}

?>

<div class="container px-4">
    <ol class="breadcrumb mb-4"></ol>
    <div class="row">

        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Register Student Assistants
                        <a href="view-register.php" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form class="row g-3" action="add2.php" method="POST" enctype="multipart/form-data">


                        <div class="col-md-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Student ID</label>
                            <input name="student_id" required class="form-control" id="student_id" value="<?php echo htmlspecialchars($formData['student_id'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Last Name</label>
                            <input name="last_name" required class="form-control" value="<?php echo htmlspecialchars($formData['last_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">First Name</label>
                            <input name="first_name" required class="form-control" value="<?php echo htmlspecialchars($formData['first_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Age</label>
                            <input name="age" required class="form-control" id="age" value="<?php echo htmlspecialchars($formData['age'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select name="sex" required class="form-select">
                                <option value="">Choose...</option>
                                <option value="Male" <?php echo (isset($formData['sex']) && $formData['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($formData['sex']) && $formData['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cs" class="form-label">Civil Status</label>
                            <select name="civil_status" class="form-select">
                                <option value="Single" <?php echo (isset($formData['civil_status']) && $formData['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                <option value="Divorce" <?php echo (isset($formData['civil_status']) && $formData['civil_status'] == 'Divorce') ? 'selected' : ''; ?>>Divorce</option>
                                <option value="Marriage" <?php echo (isset($formData['civil_status']) && $formData['civil_status'] == 'Marriage') ? 'selected' : ''; ?>>Marriage</option>
                                <option value="Widowed" <?php echo (isset($formData['civil_status']) && $formData['civil_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                <option value="" <?php echo (isset($formData['civil_status']) && $formData['civil_status'] == '') ? 'selected' : 'hidden'; ?>>Choose...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input placeholder="mm-dd-yyyy" name="date_of_birth" class="form-control" id="dob" value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? ''); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">City Address</label>
                            <textarea name="city_address" class="form-control" row="4"><?php echo htmlspecialchars($formData['city_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact No.</label>
                            <input name="contact_no1" class="form-control" id="cn1" value="<?php echo htmlspecialchars($formData['contact_no1'] ?? ''); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Provincial Address</label>
                            <textarea name="province_address" class="form-control" row="4"><?php echo htmlspecialchars($formData['province_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact No.</label>
                            <input name="contact_no2" class="form-control" id="cn2" value="<?php echo htmlspecialchars($formData['contact_no2'] ?? ''); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Guardian/s</label>
                            <input name="guardian" class="form-control" id="gar" value="<?php echo htmlspecialchars($formData['guardian'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact No.</label>
                            <input name="contact_no3" class="form-control" id="cn3" value="<?php echo htmlspecialchars($formData['contact_no3'] ?? ''); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Honors/Awards</label>
                            <textarea name="honor_award" class="form-control" row="4"><?php echo htmlspecialchars($formData['honor_award'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Past Scholarships</label>
                            <textarea name="past_scholar" class="form-control" row="4"><?php echo htmlspecialchars($formData['past_scholar'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">I intend to enroll/continue in the Program</label>
                            <input type="pro" name="program" class="form-control" value="<?php echo htmlspecialchars($formData['program'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year</label>
                            <input name="year" class="form-control" value="<?php echo htmlspecialchars($formData['year'] ?? ''); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Scholarships enjoyed at the present</label>
                            <textarea name="present_scholar" class="form-control" row="4"><?php echo htmlspecialchars($formData['present_scholar'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Work Experience (Describe briefly)</label>
                            <textarea name="work_experience" class="form-control" row="4"><?php echo htmlspecialchars($formData['work_experience'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Special Talents</label>
                            <textarea name="special_talent" class="form-control" row="4"><?php echo htmlspecialchars($formData['special_talent'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    window.onbeforeunload = function() {
        if (document.querySelector('form').checkValidity()) {
            return;
        }
        return "You have unsaved changes. Are you sure you want to leave?";
    };
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>