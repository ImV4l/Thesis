<?php
session_start();
include('admin/config/dbcon.php');
include('includes/header.php');
include('includes/navbar_tito.php');

// bootstrap link for modal
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';

// font for fingerprint icon
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">';

date_default_timezone_set('Asia/Manila');

$id = isset($_GET['id']) ? $_GET['id'] : null;

$sa_id = $_SESSION['sa_id'] ?? null;
$profileImage = $_SESSION['sa_image'] ?? '';
if(empty($profileImage)) {
    $profileImage = 'images/defaultProfile.png';
}

$latestLog = null;
$saDetails = null;
$query = "SELECT a.*, sa.* 
          FROM attendance a 
          JOIN student_assistant sa ON a.sa_id = sa.id 
          WHERE sa.status != '2' 
          ORDER BY a.date DESC, 
          CASE 
              WHEN a.time_out IS NOT NULL THEN a.time_out 
              ELSE a.time_in 
          END DESC";
$result_latest = mysqli_query($con, $query);
?>

<div class="container mt-3">
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</div>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class= "card-header text-white" style="background-color: #F16E04;">
                    <h5 class="card-title mb-0">Student Assistant Profile</h5>
                </div>
                <div class="card-body text-center">
                    <?php 
                    $imagePath = 'images/defaultProfile.png';
                    $name = "No Student Assistant Has Time In Today Yet";
                    $work = "Work";
                    $year = "Year";
                    $program = "Program";

                    if ($saDetails && $latestLog && $latestLog['date'] === date('Y-m-d')) {
                        $imagePath = !empty($saDetails['image']) ? $saDetails['image'] : 'images/defaultProfile.png';
                        $name = $saDetails['first_name'] . ' ' . $saDetails['last_name'];
                        $work = $saDetails['work'];
                        $year = 'Year ' . $saDetails['year'];
                        $program = $saDetails['program'];
                    }
                    ?>
                    <img src="<?= $imagePath ?>" 
                         id="profilePic"
                         class="mx-auto d-block rounded-circle" 
                         alt="Profile Photo"    
                         style="width: 150px; 
                                height: 150px;
                                object-fit: cover;
                                border: 3px solid #F16E04;
                                margin-bottom: 15px;">
                    
                    <div class="card-body px-3 py-2">
                        <h5 class="card-title mb-2" id="profileName"><?= $name ?></h5>
                        <div class="text-muted mb-2">
                            <i class="fas fa-graduation-cap me-1"></i> 
                            <span id="profileProgram"><?= $program ?></span>
                        </div>
                        <div class="text-muted mb-2">
                            <i class="fas fa-user-clock me-1"></i> 
                            <span id="profileWork"><?= $work ?></span>
                        </div>
                        <div class="badge bg-primary" id="profileYear"><?= $year ?></div>
                    </div>  
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header text-white" style="background-color: #F16E04;">
                    <h5 class="card-title mb-0">Fingerprint Time In/Out</h5>
                </div>
                <div class="card-body">
                    <div id="status" class="alert alert-info">
                        Initializing fingerprint scanner...
                    </div>
                    <div id="stepIndicator" class="alert alert-secondary" style="display:none">
                        Waiting for finger...
                    </div>
                    <div id="matchStatus" class="alert alert-info" style="display:none">
                    </div>

                    <div id="imagediv" class="text-center mb-3">
                        <img id="fingerprintPreview" src="" style="display:none; max-width: 200px;">
                        <i id="fingerprintIcon" class="fas fa-fingerprint" style="font-size: 100px; color: #007bff; display: block; transition: color 0.3s ease;"></i>
                    </div>
                    <form id="attendance-form" method="post">
                        <input type="hidden" name="sa_id" id="sa_id">
                        <input type="hidden" name="fingerprintData" id="fingerprintData">
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header" style="background-color: #F16E04;">
                    <h5 class="card-title text-white mb-0">Latest Time Log</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0" style="font-size: 12px;">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (mysqli_num_rows($result_latest) > 0) {
                            while ($row = mysqli_fetch_assoc($result_latest)) {
                                $logImage = !empty($row['image']) ? $row['image'] : 'images/defaultProfile.png';
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $logImage ?>" 
                                                 alt="Profile" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                            <?= $row['first_name'] . ' ' . $row['last_name'] ?>
                                        </div>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($row['date'])) ?></td>
                                    <td><?= date('H:i:s', strtotime($row['time_in'])) ?></td>
                                    <td><?= $row['time_out'] ? date('H:i:s', strtotime($row['time_out'])) : '-' ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">No logs available</td>
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
        <div class="col-md-9">
            <div class="card">
                <div class="card-header" style="background-color: #F16E04;">
                    <h4 class="card-title text-white mb-0">Attendance Sheet</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="height: 730px; overflow-y: auto;">
                        <?php
                        $query = "SELECT sa.*, a.date, a.day, a.time_in, a.time_out 
                                  FROM student_assistant sa 
                                  LEFT JOIN attendance a ON sa.id = a.sa_id AND a.date = CURDATE() 
                                  WHERE sa.status!='2'";
                        $query_run = mysqli_query($con, $query);
                        ?>
                        <table class="table table-bordered table-striped table-hover" style="font-size: 14px;">
                            <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                <tr>
                                    <th>ID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Work in</th>
                                    <th>Year</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Time in</th>
                                    <th>Time out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $row) {
                                        $status_display = "Pending";                                  
                                ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= !empty($row['image']) ? $row['image'] : 'images/defaultProfile.png'; ?>" 
                                                 alt="Profile" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <?= $row['last_name']; ?>
                                        </div>
                                    </td>
                                    <td><?= $row['first_name']; ?></td>
                                    <td><?= $row['work']; ?></td>
                                    <td><?= $row['year']; ?></td>
                                    <td><?= !empty($row['date']) ? date('Y-m-d', strtotime($row['date'])) : '-'; ?></td>
                                    <td><?= $row['day'] ?? '-'; ?></td>
                                    <td><?= !empty($row['time_in']) ? date('H:i:s', strtotime($row['time_in'])) : '-'; ?></td>
                                    <td><?= !empty($row['time_out']) ? date('H:i:s', strtotime($row['time_out'])) : '-'; ?></td>
                                    <td><?= $status_display; ?></td>
                                </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="10">No Record Found</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function matchFingerprint($template) {
    global $con;

    $query = "SELECT id, first_name, last_name, image, fingerprint_image 
              FROM student_assistant 
              WHERE fingerprint_image IS NOT NULL AND status != '2'";
    $result = mysqli_query($con, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        if (compareTemplates($template, $row['fingerprint_image'])) {
            return $row;
        }
    }
    return null;
}
function compareTemplates($template1, $template2) {
    $t1 = base64_decode($template1);
    $t2 = $template2;
    
    $similarity = 0;
    $minLen = min(strlen($t1), strlen($t2));
    
    for ($i = 0; $i < $minLen; $i++) {
        if ($t1[$i] === $t2[$i]) {
            $similarity++;
        }
    }
    
    return ($similarity / $minLen) > 0.7; 
}

function processAttendance($sa_id, $con) {
    $date = date('Y-m-d');
    $time = date('H:i:s');
    
    $query = "SELECT * FROM attendance WHERE sa_id = ? AND date = ? AND time_out IS NULL";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "is", $sa_id, $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $status = 'Completed';
        $query = "UPDATE attendance SET time_out = ?, status = ? WHERE sa_id = ? AND date = ? AND time_out IS NULL";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssis", $time, $status, $sa_id, $date);
    } else {
        $status = 'Present';
        $query = "INSERT INTO attendance (sa_id, date, time_in, status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "isss", $sa_id, $date, $time, $status);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $status;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'match') {
    header('Content-Type: application/json');
    
    $fingerprintData = $_POST['template'] ?? null;
    if (!$fingerprintData) {
        echo json_encode(['success' => false, 'message' => 'No template provided']);
        exit;
    }

    $match = matchFingerprint($fingerprintData);
    if (!$match) {
        echo json_encode(['success' => false, 'message' => 'No match found']);
        exit;
    }

    $result = processAttendance($match['id'], $con);
    
    echo json_encode([
        'success' => true,
        'message' => $result,
        'sa' => [
            'id' => $match['id'],
            'name' => $match['first_name'] . ' ' . $match['last_name'],
            'image' => $match['image']
        ]
    ]);
    exit;
}

?>
<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutConfirmModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to log out (Time Out)?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelLogout">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmLogout">Yes, Log Out</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ESP32_URL = 'http://192.168.254.202';
    let retryTimeout;

    async function startFingerprintMatching() {
        try {
            const response = await fetch(`${ESP32_URL}/matchFingerprint`);
            const data = await response.json();
            console.log('Match response:', data);
            
            if (data.status === 'success') {
                updateStatus(`Match found! Processing attendance...`, 'success');
                document.getElementById('fingerprintIcon').style.color = '#28a745';
                await processAttendance(data.fingerprintId);
                await new Promise(resolve => setTimeout(resolve, 2000));
                document.getElementById('fingerprintIcon').style.color = '#007bff';
            } else if (data.status === 'waiting') {
                updateStatus('Place your finger on the sensor', 'info');
                document.getElementById('fingerprintIcon').style.color = '#007bff';
            } else if (data.status === 'not_found') {
                updateStatus('Fingerprint not recognized. Please try again.', 'warning');
                document.getElementById('fingerprintIcon').style.color = '#dc3545';
                await new Promise(resolve => setTimeout(resolve, 2000));
                document.getElementById('fingerprintIcon').style.color = '#007bff';
            } else {
                console.error('Error response:', data);
                updateStatus(data.message || 'Device error, retrying...', 'warning');
                await new Promise(resolve => setTimeout(resolve, 2000));
            }
        } catch (error) {
            console.error('Matching error:', error);
            updateStatus('Connection error. Retrying...', 'danger');
            await new Promise(resolve => setTimeout(resolve, 5000));
        }

        if (retryTimeout) clearTimeout(retryTimeout);
        retryTimeout = setTimeout(startFingerprintMatching, 1000);
    }
    async function processAttendance(fingerprintId, confirmLogout = false) {
        try {
            let bodyData = { fingerprintId: fingerprintId };
            if (confirmLogout) {
                bodyData.confirm = true;
            }
            const response = await fetch('process_attendance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(bodyData)
            });
            const result = await response.json();
            
            if (result.confirmation_required) {
                showLogoutConfirmation(fingerprintId);
            } else if (result.success) {
                updateStatus(result.message, 'success');
                if (result.data) {
                    updateAttendanceDisplay(result.data);
                }
            } else {
                updateStatus(result.message || 'Failed to process attendance', 'warning');
            }
        } catch (error) {
            console.error('Attendance processing error:', error);
            updateStatus('Error processing attendance', 'danger');
        }
    }

    function showLogoutConfirmation(fingerprintId) {
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'), {});
        logoutModal.show();
        document.getElementById('confirmLogout').onclick = function() {
            logoutModal.hide();
            processAttendance(fingerprintId, true);
        };
        document.getElementById('cancelLogout').onclick = function() {
            logoutModal.hide();
            updateStatus('Logout cancelled', 'info');
        };
    }

    function updateStatus(message, type) {
        const statusDiv = document.getElementById('status');
        if (statusDiv) {
            statusDiv.className = `alert alert-${type}`;
            statusDiv.textContent = message;
        }
        console.log(`Status (${type}):`, message); 
    }

    function updateAttendanceDisplay(data) {
        const tbody = document.querySelector('.table-responsive tbody');
        if (tbody && data) {
            const imagePath = data.image || 'images/defaultProfile.png';
            
            const row = `
                <tr>
                    <td>${data.sa_id}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${imagePath}" 
                                 alt="Profile" 
                                 class="rounded-circle me-2" 
                                 style="width: 40px; height: 40px; object-fit: cover;">
                            ${data.name}
                        </div>
                    </td>
                    <td>${new Date().toISOString().split('T')[0]}</td>
                    <td>${data.action === 'Time In' ? data.time : '-'}</td>
                    <td>${data.action === 'Time Out' ? data.time : '-'}</td>
                </tr>`;
            tbody.innerHTML = row;
        }
    
        if (data) {
            document.querySelector('.card-title').textContent = data.name;
            if (data.program) document.querySelector('.fa-graduation-cap').parentNode.textContent = ' ' + data.program;
            if (data.work) document.querySelector('.fa-user-clock').parentNode.textContent = ' ' + data.work;
            if (data.year) document.querySelector('.badge').textContent = 'Year ' + data.year;
            if (data.image) {
                const profileImg = document.querySelector('.mx-auto.d-block');
                profileImg.src = data.image;
            }
        }
    }

    startFingerprintMatching();

    window.addEventListener('beforeunload', () => {
        if (retryTimeout) clearTimeout(retryTimeout);
    });

    async function updateStudentProfile() {
        try {
            const response = await fetch('get_latest_sa.php');
            const result = await response.json();
            if(result.success && result.data) {
                const sa = result.data;
                document.getElementById('profilePic').src = sa.image || 'images/defaultProfile.png';
                document.getElementById('profileName').textContent = sa.first_name + ' ' + sa.last_name;
                document.getElementById('profileProgram').textContent = sa.program;
                document.getElementById('profileWork').textContent = sa.work;
                document.getElementById('profileYear').textContent = 'Year ' + sa.year;
            }
        } catch (error) {
            console.error('Profile update error:', error);
        }
    }
    setInterval(updateStudentProfile, 1000);
});
</script>

