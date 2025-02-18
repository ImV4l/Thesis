let enrolledTemplate = null;
let reader = null;
let currentCapture = null;
let Fingerprint = window.Fingerprint;
let api = null;

function showMessage(message, isError = false) {
    const dialog = document.getElementById('messageDialog');
    dialog.textContent = message;
    dialog.className = `message-dialog ${isError ? 'error' : 'success'}`;
    dialog.style.display = 'block';
    setTimeout(() => {
        dialog.style.display = 'none';
    }, 3000);
}

// Update the window.onload to ensure SDK is ready
window.onload = function() {
    // Wait for SDK to be ready
    if (window.Fingerprint) {
        initializeWebSDK();
    } else {
        // Poll until SDK is loaded
        const checkInterval = setInterval(() => {
            if (window.Fingerprint) {
                clearInterval(checkInterval);
                initializeWebSDK();
            }
        }, 100);
    }
}

function initializeWebSDK() {
    try {
        var sdk = new Fingerprint.WebApi();
        api = sdk;  // Store in our global api variable

        sdk.onDeviceConnected = function(e) {
            showMessage('Device connected');
            initializeReader();
        };

        sdk.onDeviceDisconnected = function(e) {
            showMessage('Device disconnected', true);
            reader = null;
        };

        sdk.onSamplesAcquired = function(s) {
            currentCapture = s.samples[0];
            let activeElement = document.querySelector('.fp-box.active');
            if (activeElement) {
                let imageId = activeElement.querySelector('.fp-image').id;
                displayImage(imageId, currentCapture);
                showMessage('Fingerprint captured successfully');
            }
        };

        sdk.onErrorOccurred = function(e) {
            showMessage('Error: ' + e.message, true);
            console.error(e);
        };

        sdk.start();  // Start the SDK
    } catch(e) {
        showMessage('Failed to initialize fingerprint API: ' + e.message, true);
        console.error(e);
    }
}

function initializeReader() {
    try {
        api.enumerateDevices()
            .then(function(devices) {
                if (devices.length > 0) {
                    reader = devices[0];
                    showMessage('Reader initialized successfully');
                } else {
                    showMessage('No fingerprint reader detected', true);
                }
            })
            .catch(function(error) {
                showMessage('Failed to enumerate devices: ' + error.message, true);
            });
    } catch(e) {
        showMessage('Error initializing reader: ' + e.message, true);
    }
}

function startEnrollmentCapture() {
    if (!reader) {
        showMessage('Reader not initialized', true);
        return;
    }

    try {
        // Mark enrollment box as active
        document.querySelectorAll('.fp-box').forEach(box => box.classList.remove('active'));
        document.getElementById('enrollmentBox').classList.add('active');

        api.startAcquisition(Fingerprint.SampleFormat.PngImage).then(() => {
            showMessage('Place your finger on the reader');
        }).catch(error => {
            showMessage('Failed to start capture: ' + error.message, true);
        });
    } catch(e) {
        showMessage('Capture error: ' + e.message, true);
    }
}

function startVerificationCapture() {
    if (!reader) {
        showMessage('Reader not initialized', true);
        return;
    }
    if (!enrolledTemplate) {
        showMessage('No fingerprint enrolled yet', true);
        return;
    }

    try {
        // Mark verification box as active
        document.querySelectorAll('.fp-box').forEach(box => box.classList.remove('active'));
        document.getElementById('verificationBox').classList.add('active');

        api.startAcquisition(Fingerprint.SampleFormat.PngImage).then(() => {
            showMessage('Place your finger on the reader');
        }).catch(error => {
            showMessage('Failed to start capture: ' + error.message, true);
        });
    } catch(e) {
        showMessage('Capture error: ' + e.message, true);
    }
}

function clearImages(prefix) {
    const element = document.getElementById(prefix + 'Image');
    const previewElement = document.getElementById(prefix + 'Preview');
    
    if (element) {
        element.style.backgroundImage = 'none';
    }
    if (previewElement) {
        previewElement.style.backgroundImage = 'none';
    }
}

function stopCapture() {
    try {
        api.stopAcquisition().then(() => {
            showMessage('Capture stopped');
            document.querySelectorAll('.fp-box').forEach(box => box.classList.remove('active'));
        }).catch(error => {
            showMessage('Failed to stop capture: ' + error.message, true);
        });
    } catch(e) {
        showMessage('Stop capture error: ' + e.message, true);
    }
}

function submitEnrollment() {
    if (!currentCapture) {
        showMessage('No fingerprint captured', true);
        return;
    }
    
    enrolledTemplate = currentCapture;
    clearImages('enrollment');
    currentCapture = null;
    
    // Enable verification box
    document.querySelector('#verificationBox button[onclick="startVerificationCapture()"]').disabled = false;
    document.querySelector('#verificationBox button[onclick="verifyFingerprint()"]').disabled = false;
    
    showMessage('Fingerprint enrolled successfully');
}

function verifyFingerprint() {
    if (!currentCapture) {
        showMessage('No fingerprint captured', true);
        return;
    }
    if (!enrolledTemplate) {
        showMessage('No fingerprint enrolled yet', true);
        return;
    }
    
    const score = compareFingerprints(currentCapture, enrolledTemplate);
    if (score >= 0.7) {
        showMessage('Fingerprint verified successfully!');
    } else {
        showMessage('Fingerprint verification failed!', true);
    }
}

function displayImage(elementId, captureData) {
    const element = document.getElementById(elementId);
    const previewElement = document.getElementById(elementId + 'Preview');
    
    if (element && captureData) {
        const imageUrl = `url(data:image/png;base64,${captureData.image})`;
        element.style.backgroundImage = imageUrl;
        
        // Update preview
        if (previewElement) {
            previewElement.style.backgroundImage = imageUrl;
        }
    }
}

function compareFingerprints(template1, template2) {
    // This is a simplified comparison.
    // In a real implementation, you would use the SDK's comparison functions
    return DigitalPersona.compareTemplates(template1, template2);
}
