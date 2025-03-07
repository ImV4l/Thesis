<?php
session_start();
ob_start();
include('authentication.php');
include('includes/header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['work_in'])) {
    $_SESSION['sa_form_data'] = array_merge($_SESSION['sa_form_data'] ?? [], $_POST);
    header('Location: add3.php');
    exit();
}

$formData = $_SESSION['sa_form_data'] ?? [];
?>

<div class="container px-4">
    <ol class="breadcrumb mb-4"></ol>
    <div class="row">

        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header" style="background-color: #F16E04; color: white;">
                    <h4>Register Student Assistants</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3" action="add3.php" method="POST" enctype="multipart/form-data">
                        <?php if (isset($_FILES['image'])): ?>
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($_FILES['image']['name']); ?>">
                            <?php
                            $tmpImage = $_FILES['image']['tmp_name'];
                            if (!empty($tmpImage)) {
                                $_SESSION['temp_image'] = $tmpImage;
                            }
                            ?>
                        <?php endif; ?>
                        <?php
                        foreach ($_POST as $key => $value) {
                            if (is_array($value)) {
                                foreach ($value as $item) {
                                    echo '<input type="hidden" name="' . $key . '[]" value="' . htmlspecialchars($item) . '">';
                                }
                            } else {
                                echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">';
                            }
                        }
                        ?>

                        <?php
                        $query = "SELECT * FROM work";
                        $query_run = mysqli_query($con, $query);

                        $offices = [];
                        $laboratories = [];
                        $manpower_services = [];

                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                switch ($row['type']) {
                                    case 'Office':
                                        $offices[] = $row['work_name'];
                                        break;
                                    case 'Laboratory':
                                        $laboratories[] = $row['work_name'];
                                        break;
                                    case 'Manpower Services':
                                        $manpower_services[] = $row['work_name'];
                                        break;
                                }
                            }
                        }
                        ?>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="offices" class="form-label"><strong>Offices</strong></label>
                                <select name="work_in[]" class="form-select" id="offices">
                                    <option value="">Select an office</option>
                                    <?php foreach ($offices as $office): ?>
                                        <option value="<?php echo htmlspecialchars($office); ?>"
                                            <?php echo in_array($office, $formData['work_in'] ?? []) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($office); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="laboratories" class="form-label"><strong>Laboratories</strong></label>
                                <select name="work_in[]" class="form-select" id="laboratories">
                                    <option value="">Select a laboratory</option>
                                    <?php foreach ($laboratories as $laboratory): ?>
                                        <option value="<?php echo htmlspecialchars($laboratory); ?>"
                                            <?php echo in_array($laboratory, $formData['work_in'] ?? []) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($laboratory); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="manpower_services" class="form-label"><strong>Manpower Services</strong></label>
                                <select name="work_in[]" class="form-select" id="manpower_services">
                                    <option value="">Select a service</option>
                                    <?php foreach ($manpower_services as $service): ?>
                                        <option value="<?php echo htmlspecialchars($service); ?>"
                                            <?php echo in_array($service, $formData['work_in'] ?? []) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($service); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
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

<?php
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
    $temp_dir = "../images/uploads/temp/";
    if (!is_dir($temp_dir)) {
        mkdir($temp_dir, 0777, true);
    }

    $image = $_FILES['profile_image']['name'];
    $tmp_image = $_FILES['profile_image']['tmp_name'];

    $extension = pathinfo($image, PATHINFO_EXTENSION);
    $temp_filename = 'temp_' . time() . '_' . uniqid() . '.' . $extension;
    $temp_filepath = $temp_dir . $temp_filename;

    if (move_uploaded_file($tmp_image, $temp_filepath)) {
        $_SESSION['temp_image'] = $temp_filepath;
    } else {
        error_log("Failed to move uploaded file to temp directory");
    }
}

include('includes/footer.php');
include('includes/scripts.php');
?>

<style>
    /* Add this to your CSS file or in a style tag */
    .modal-body {
        max-height: calc(100vh - 150px);
        overflow-y: auto;
        padding: 0.5rem;
    }

    .form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .form-label.small {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .card-header {
        padding: 0.5rem 1rem;
    }

    .card-body {
        padding: 0.5rem;
    }
</style>