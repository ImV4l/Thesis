<?php
session_start();
include('authentication.php');
include('includes/header.php');
?>

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="css/bootstrap-min.css">
  <link rel="stylesheet" href="app.css" type="text/css" />
  <script type="text/javascript">
    window.onerror = function(msg, url, line) {
        console.error('Error: ' + msg + '\nURL: ' + url + '\nLine: ' + line);
        return false;
    };
  </script>
</head>

<body>
  <div class="container">
    <div class="fingerprint-container">
      <h4 class="text-center mb-4">Register Fingerprint
        <a href="add3.php" class="btn btn-danger btn-sm float-end">Back</a>
      </h4>
      <div class="alert alert-primary mb-4" role="alert">
        <h5 class="alert-heading">Fingerprint Capture Instructions:</h5>
        <ol>
          <li>Place your finger on the sensor for first capture</li>
          <li>Remove finger when prompted</li>
          <li>Wait for 2 seconds</li>
          <li>Place the SAME finger again when prompted</li>
          <li>Wait for confirmation before clicking Register</li>
        </ol>
      </div>

      <form action="addcode.php" method="POST">
        <?php
        foreach($_POST as $key => $value) {
            if(is_array($value)) {
                foreach($value as $item) {
                    echo '<input type="hidden" name="'.$key.'[]" value="'.htmlspecialchars($item).'">';
                }
            } else {
                echo '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($value).'">';
            }
        }
        
        if(isset($_SESSION['temp_image'])) {
            echo '<input type="hidden" name="profile_image" value="'.htmlspecialchars($_SESSION['temp_image']).'">';
        }
        ?>
        <input type="hidden" id="fingerprintData" name="fingerprintData">
        <input type="hidden" id="fingerprintQuality" name="fingerprintQuality">
        <div class="status-container">
          <div id="status" class="alert alert-info">
            Initializing fingerprint scanner...
          </div>
          <div id="stepIndicator" class="alert alert-secondary" style="display:none">
            Waiting for first capture...
          </div>
          <div class="progress" style="display:none" id="captureProgress">
            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
          </div>
        </div>
        <div class="preview-container">
          <div id="previewBox" class="text-center mb-3">
            <img id="fingerprintImage" class="img-fluid" style="display:none; max-width: 200px;">
            <i id="fingerprintIcon" class="fas fa-fingerprint" style="font-size: 100px; color: #007bff;"></i>
          </div>
          <div id="qualityIndicator" class="text-center mb-2"></div>
        </div>
        <div class="control-buttons d-flex justify-content-center gap-2">
          <button type="button" class="btn btn-primary" id="recapture" style="display:none">
            Recapture
          </button>
          <button type="submit" name="add_btn" class="btn btn-success" id="register" disabled>
            Register Student Assistant
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="lib/jquery.min.js"></script>
  <script src="lib/bootstrap.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ESP32_URL = 'http://192.168.1.12'; // Your ESP32's IP
        let capturedTemplate = null;
        let isCapturing = false;
        let deviceConnected = false;
        let captureStep = 1; 
        async function checkDeviceConnection() {
            try {
                const response = await fetch(`${ESP32_URL}/status`);
                if (response.ok) {
                    deviceConnected = true;
                    updateStatus('Device connected. Place your finger on the sensor.', 'info');
                    return true;
                }
            } catch (error) {
                console.error('Connection error:', error);
                updateStatus('Cannot connect to fingerprint device. Check if device is powered on.', 'danger');
                return false;
            }
            return false;
        }

        async function initFingerprintCapture() {
            if (!await checkDeviceConnection()) {
                setTimeout(initFingerprintCapture, 5000);
                return;
            }

            updateStatus('Ready to capture. Place finger on sensor.', 'info');
            startCapture();
        }

        async function startCapture() {
            isCapturing = true;
            updateStepIndicator('first');
            
            try {
                const response = await fetch(`${ESP32_URL}/enrollFingerprint`);
                if (!response.ok) throw new Error('Enrollment failed');
                
                const data = await response.json();
                console.log('Enrollment response:', data);
                
                if (data.status === 'success') {
                    document.getElementById('fingerprintData').value = JSON.stringify({
                        fingerprintId: data.fingerprintId,
                        confidence: data.confidence
                    });
                    updateStatus('Fingerprint enrolled successfully!', 'success');
                    updateStepIndicator('complete');
                    document.getElementById('register').disabled = false;
                    document.getElementById('recapture').style.display = 'block';
                } else {
                    updateStatus(data.message || 'Enrollment failed. Please try again.', 'danger');
                }
            } catch (error) {
                console.error('Enrollment error:', error);
                updateStatus('Error during enrollment. Please try again.', 'danger');
            }
            
            isCapturing = false;
        }

        function updateStatus(message, type) {
            const statusDiv = document.getElementById('status');
            statusDiv.className = `alert alert-${type}`;
            statusDiv.textContent = message;
            console.log('Status update:', message);
        }

        function updateStepIndicator(step) {
            const indicator = document.getElementById('stepIndicator');
            indicator.style.display = 'block';
            
            switch(step) {
                case 'first':
                    indicator.className = 'alert alert-warning';
                    indicator.textContent = 'Step 1: Place your finger on the sensor';
                    break;
                case 'remove':
                    indicator.className = 'alert alert-info';
                    indicator.textContent = 'Please remove your finger';
                    break;
                case 'second':
                    indicator.className = 'alert alert-warning';
                    indicator.textContent = 'Step 2: Place the SAME finger again';
                    break;
                case 'complete':
                    indicator.className = 'alert alert-success';
                    indicator.textContent = 'Fingerprint captured successfully! Click Register to continue.';
                    break;
                default:
                    indicator.style.display = 'none';
            }
        }
        initFingerprintCapture();
        document.getElementById('recapture').addEventListener('click', function() {
            capturedTemplate = null;
            document.getElementById('fingerprintData').value = '';
            document.getElementById('register').disabled = true;
            document.getElementById('fingerprintImage').style.display = 'none';
            initFingerprintCapture();
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            const fingerprintData = document.getElementById('fingerprintData').value;
            if (!fingerprintData) {
                e.preventDefault();
                updateStatus('Please capture a fingerprint first', 'warning');
                return;
            }
            
            try {
                const data = JSON.parse(fingerprintData);
                if (!data.fingerprintId) {
                    e.preventDefault();
                    updateStatus('Invalid fingerprint data', 'warning');
                    return;
                }
                console.log('Submitting form with fingerprint ID:', data.fingerprintId);
            } catch (error) {
                e.preventDefault();
                console.error('Error parsing fingerprint data:', error);
                updateStatus('Invalid fingerprint data format', 'warning');
            }
        });
    });
  </script>
</body>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>