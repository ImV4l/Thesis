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
if (empty($profileImage)) {
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

$latest_sa_query = "SELECT a.*, sa.* 
                    FROM attendance a 
                    JOIN student_assistant sa ON a.sa_id = sa.id 
                    WHERE sa.status != '2' AND a.date = CURDATE() 
                    ORDER BY COALESCE(a.time_out, a.time_in) DESC 
                    LIMIT 1";
$result_sa = mysqli_query($con, $latest_sa_query);
$latest_sa = mysqli_fetch_assoc($result_sa);

$profileImage = $latest_sa['image'] ?? 'images/defaultProfile.png';
$name = isset($latest_sa['first_name'], $latest_sa['last_name']) ? $latest_sa['first_name'] . ' ' . $latest_sa['last_name'] : 'No SA Time In Today Yet';
$work = $latest_sa['work'] ?? 'Work';
$year = isset($latest_sa['year']) ? 'Year ' . $latest_sa['year'] : 'Year';
$program = $latest_sa['program'] ?? 'Program';

?>

<div class="container mt-3">
    <?php if (isset($_SESSION['message'])): ?>
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
                <div class="card-header text-white" style="background-color: #F16E04;">
                    <h5 class="card-title mb-0">Student Assistant Profile</h5>
                </div>
                <div class="card-body text-center">
                    <img src="<?= $profileImage ?>"
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
                        $query = "SELECT a.*, sa.first_name, sa.last_name, sa.work, sa.year, sa.image,
                                  DAYNAME(a.date) as day_name 
                                  FROM attendance a 
                                  JOIN student_assistant sa ON a.sa_id = sa.id 
                                  WHERE a.date = CURDATE() 
                                  ORDER BY a.date DESC, COALESCE(a.time_out, a.time_in) DESC";
                        $query_run = mysqli_query($con, $query);
                        ?>
                        <table class="table table-bordered table-striped table-hover" style="font-size: 14px;">
                            <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Work</th>
                                    <th>Year</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Day</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        $work_hours = ($row['num_hour'] > 0) ? number_format($row['num_hour'], 2) : '-';
                                ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= !empty($row['image']) ? $row['image'] : 'images/defaultProfile.png'; ?>"
                                                        alt="Profile"
                                                        class="rounded-circle me-2"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                    <?= $row['first_name']; ?>
                                                </div>
                                            </td>
                                            <td><?= $row['last_name']; ?></td>
                                            <td><?= $row['work']; ?></td>
                                            <td><?= $row['year']; ?></td>
                                            <td><?= $row['date']; ?></td>
                                            <td><?= !empty($row['time_in']) ? date('H:i:s', strtotime($row['time_in'])) : '-'; ?></td>
                                            <td><?= !empty($row['time_out']) ? date('H:i:s', strtotime($row['time_out'])) : '-'; ?></td>
                                            <td><?= $row['day_name'] ?? date('l'); ?></td>
                                            <td><?= $row['status'] ?? 'Pending'; ?></td>
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
function matchFingerprint($template)
{
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
function compareTemplates($template1, $template2)
{
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

function processAttendance($sa_id, $con)
{
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $query = "SELECT * FROM attendance WHERE sa_id = ? AND date = ? AND time_out IS NULL";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "is", $sa_id, $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Time Out - Calculate work hours
        $row = mysqli_fetch_assoc($result);
        $time_in = new DateTime($row['time_in']);
        $time_out = new DateTime($time);

        // Calculate the difference in hours
        $interval = $time_in->diff($time_out);
        $hours = $interval->h + ($interval->days * 24);
        $minutes = $interval->i;
        $total_hours = $hours + ($minutes / 60);

        $status = 'Completed';
        $query = "UPDATE attendance 
                 SET time_out = ?, 
                     status = ?, 
                     num_hour = ? 
                 WHERE sa_id = ? AND date = ? AND time_out IS NULL";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssids", $time, $status, $total_hours, $sa_id, $date);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return [
                'status' => $status,
                'success' => true,
                'message' => 'Time out successful',
                'hours' => number_format($total_hours, 2)
            ];
        }
    } else {
        // Time In
        $status = 'Present';
        $query = "INSERT INTO attendance (sa_id, date, time_in, status, num_hour) 
                 VALUES (?, ?, ?, ?, 0)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "isss", $sa_id, $date, $time, $status);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return [
                'status' => $status,
                'success' => true,
                'message' => 'Time in successful',
                'hours' => 0
            ];
        }
    }

    mysqli_stmt_close($stmt);
    return [
        'status' => 'Error',
        'success' => false,
        'message' => 'Database error'
    ];
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
        'message' => $result['message'],
        'sa' => [
            'id' => $match['id'],
            'name' => $match['first_name'] . ' ' . $match['last_name'],
            'image' => $match['image']
        ],
        'hours' => $result['hours']
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
        const ESP32_URL = 'http://192.168.1.12';
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
                updateStatus('Processing...', 'info');
                document.getElementById('fingerprintIcon').style.color = '#007bff';

                let bodyData = {
                    fingerprintId: fingerprintId
                };
                if (confirmLogout) {
                    bodyData.confirm = true;
                }

                const response = await fetch('process_attendance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(bodyData)
                });

                const result = await response.json();

                if (result.confirmation_required) {
                    showLogoutConfirmation(fingerprintId);
                } else if (result.success) {
                    document.getElementById('fingerprintIcon').style.color = '#28a745';
                    updateStatus(result.message, 'success');

                    // Show work hours alert if time out was processed
                    if (result.hours) {
                        const hours = parseFloat(result.hours);
                        const hoursText = hours === 1 ? 'hour' : 'hours';
                        const alertMessage = `Time out successful! You worked for ${hours.toFixed(2)} ${hoursText}`;

                        // Create and show Bootstrap alert
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${alertMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;

                        // Insert alert at the top of the container
                        const container = document.querySelector('.container.mt-3');
                        container.insertBefore(alertDiv, container.firstChild);

                        // Auto dismiss after 5 seconds
                        setTimeout(() => {
                            alertDiv.classList.remove('show');
                            setTimeout(() => alertDiv.remove(), 150);
                        }, 5000);
                    }

                    if (result.data) {
                        updateAttendanceDisplay(result.data);
                    }
                    await refreshAttendanceData();
                } else {
                    document.getElementById('fingerprintIcon').style.color = '#dc3545';
                    updateStatus(result.message || 'Failed to process attendance', 'warning');
                }

                setTimeout(() => {
                    document.getElementById('fingerprintIcon').style.color = '#007bff';
                }, 1000);

            } catch (error) {
                console.error('Attendance processing error:', error);
                document.getElementById('fingerprintIcon').style.color = '#dc3545';
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
                if (result.success && result.data) {
                    const sa = result.data;
                    document.getElementById('profilePic').src = sa.image || 'images/defaultProfile.png';
                    document.getElementById('profileName').textContent =
                        `${sa.first_name} ${sa.last_name}`;
                    document.getElementById('profileProgram').textContent = sa.program;
                    document.getElementById('profileWork').textContent = sa.work;
                    document.getElementById('profileYear').textContent = 'Year ' + sa.year;

                    const todayDate = new Date().toISOString().split('T')[0];
                    if (sa.date === todayDate) {
                        if (sa.time_out) {
                            document.getElementById('profileName').innerHTML +=
                                ' <span class="badge bg-danger">Timed Out</span>';
                        } else {
                            document.getElementById('profileName').innerHTML +=
                                ' <span class="badge bg-success">Timed In</span>';
                        }
                    }
                }
            } catch (error) {
                console.error('Profile update error:', error);
            }
        }

        setInterval(updateStudentProfile, 1000);

        async function refreshAttendanceData() {
            try {
                const response = await fetch('get_attendance_data.php');
                const data = await response.json();

                if (data.success) {
                    const latestLogTbody = document.querySelector('.card:nth-of-type(4) tbody');
                    if (latestLogTbody && data.latestLogs) {
                        latestLogTbody.innerHTML = data.latestLogs.map(log => `
                        <tr>
                            <td>${log.id}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${log.image || 'images/defaultProfile.png'}" 
                                         alt="Profile" 
                                         class="rounded-circle me-2" 
                                         style="width: 30px; height: 30px; object-fit: cover;">
                                    ${log.first_name} ${log.last_name}
                                </div>
                            </td>
                            <td>${log.date}</td>
                            <td>${log.time_in}</td>
                            <td>${log.time_out || '-'}</td>
                        </tr>
                    `).join('') || '<tr><td colspan="5" class="text-center">No logs available</td></tr>';
                    }

                    const attendanceSheetTbody = document.querySelector('.col-md-9 tbody');
                    if (attendanceSheetTbody && data.attendanceSheet) {
                        attendanceSheetTbody.innerHTML = data.attendanceSheet.map(sa => `
                        <tr>
                            <td>${sa.id}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${sa.image || 'images/defaultProfile.png'}" 
                                         alt="Profile" 
                                         class="rounded-circle me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    ${sa.first_name}
                                </div>
                            </td>
                            <td>${sa.last_name}</td>
                            <td>${sa.work}</td>
                            <td>${sa.year}</td>
                            <td>${sa.date || '-'}</td>
                            <td>${sa.time_in || '-'}</td>
                            <td>${sa.time_out || '-'}</td>
                            <td>${sa.day_name || new Date().toLocaleString('en-us', {weekday:'long'})}</td>
                            <td>${sa.status || 'Pending'}</td>
                        </tr>
                    `).join('') || '<tr><td colspan="10">No Record Found</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error refreshing attendance data:', error);
            }
        }

        setInterval(refreshAttendanceData, 1000);
    });
</script>