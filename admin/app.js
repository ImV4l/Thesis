var test = null;

var state = null; // Move this to be set after DOM loads

var myVal = ""; // Drop down selected value of reader 
var disabled = true;
var startEnroll = false;

var currentFormat = Fingerprint.SampleFormat.PngImage;
var deviceTechn = {
               0: "Unknown",
               1: "Optical",
               2: "Capacitive",
               3: "Thermal",
               4: "Pressure"
            }

var deviceModality = {
               0: "Unknown",
               1: "Swipe",
               2: "Area",
               3: "AreaMultifinger"
            }

var deviceUidType = {
               0: "Persistent",
               1: "Volatile"
            }

var FingerprintSdkTest = (function () {
    function FingerprintSdkTest() {
        var _instance = this;
        this.operationToRestart = null;
        this.acquisitionStarted = false;
        this.sdk = new Fingerprint.WebApi;
        this.sdk.onDeviceConnected = function (e) {
            // Enable the start button when device is connected
            $('#start').prop('disabled', false);
            $('#stop').prop('disabled', true);
            showMessage("Scanner connected. Click Start Capture to begin.");
        };
        this.sdk.onDeviceDisconnected = function (e) {
            // Disable buttons when device is disconnected
            $('#start').prop('disabled', true);
            $('#stop').prop('disabled', true);
            showMessage("Scanner disconnected");
        };
        this.sdk.onCommunicationFailed = function (e) {
            showMessage("Communication Failed: " + e.message);
        };
        this.sdk.onSamplesAcquired = function (s) {
            // Sample acquired event triggers this function
                sampleAcquired(s);
        };
        this.sdk.onQualityReported = function (e) {
            // Quality of sample aquired - Function triggered on every sample acquired
                document.getElementById("qualityInputBox").value = Fingerprint.QualityCode[(e.quality)];
        }

    }

    FingerprintSdkTest.prototype.startCapture = function () {
        if (this.acquisitionStarted) return;
    
        var _instance = this;
        showMessage("Starting capture...");
        
        // Ensure we have a valid device
        if (!myVal) {
            showMessage("No scanner selected");
            return;
        }
    
        // Set capture parameters
        currentFormat = Fingerprint.SampleFormat.Intermediate;
        
        this.sdk.startAcquisition(currentFormat, myVal).then(function () {
            _instance.acquisitionStarted = true;
            showMessage("Place your finger on the scanner");
            $('#start').prop('disabled', true);
            $('#stop').prop('disabled', false);
        }, function (error) {
            showMessage("Error starting capture: " + error.message);
            console.error(error);
        });
    };
    FingerprintSdkTest.prototype.stopCapture = function () {
        if (!this.acquisitionStarted) //Monitor if already stopped capturing
            return;
        var _instance = this;
        showMessage("");
        this.sdk.stopAcquisition().then(function () {
            _instance.acquisitionStarted = false;

            //Disabling stop once stoped
            disableEnableStartStop();

        }, function (error) {
            showMessage(error.message);
        });
    };

    FingerprintSdkTest.prototype.getInfo = function () {
        var _instance = this;
        return this.sdk.enumerateDevices();
    };

    FingerprintSdkTest.prototype.getDeviceInfoWithID = function (uid) {
        var _instance = this;
        return  this.sdk.getDeviceInfo(uid);
    };

    
    return FingerprintSdkTest;
})();

function showMessage(message){
    var statusElement = document.getElementById('status');
    if (statusElement) {
        statusElement.innerHTML = message;
    }
}

// Update window.onload function
window.onload = function () {
    try {
        state = document.getElementById('imagediv').parentElement; // Set state to parent container
        localStorage.clear();
        test = new FingerprintSdkTest();
        
        // Initialize scanner
        test.sdk.enumerateDevices().then(function(device_list) {
            if(device_list && device_list.length > 0) {
                myVal = device_list[0]; // Use first available device
                currentFormat = Fingerprint.SampleFormat.PngImage;
                $('#start').prop('disabled', false);
                showMessage("Scanner ready. Click Start Capture to begin.");
            } else {
                showMessage("No scanner detected");
            }
        }).catch(function(error) {
            showMessage("Error initializing scanner: " + error.message);
            console.error(error);
        });
        
    } catch(error) {
        showMessage("Failed to initialize scanner: " + error.message);
        console.error(error);
    }
};

// Add new function to handle scanner initialization
function initScannerAndUI() {
    test.getInfo().then(function(successObj) {
        if(successObj && successObj.length > 0) {
            // Enable start button if scanner is connected
            $('#start').prop('disabled', false);
            $('#stop').prop('disabled', true);
            showMessage("Scanner ready. Click Start Capture to begin.");
            // Automatically select the first available scanner
            myVal = successObj[0];
        } else {
            $('#start').prop('disabled', true);
            $('#stop').prop('disabled', true);
            showMessage("No scanner detected. Please connect a scanner.");
        }
    }, function(error) {
        showMessage(error.message);
        $('#start').prop('disabled', true);
        $('#stop').prop('disabled', true);
    });
}

function onStart() {
    if(!test || !test.sdk) {
        showMessage("Scanner not initialized");
        return;
    }
    
    try {
        currentFormat = Fingerprint.SampleFormat.PngImage;
        test.startCapture();
    } catch(error) {
        showMessage("Error: " + error.message);
        console.error(error);
    }
}

function onStop() {
    test.stopCapture();
    $('#start').prop('disabled', false);
    $('#stop').prop('disabled', true);
    showMessage("Capture stopped");
}

function onGetInfo() {
    var allReaders = test.getInfo();    
    allReaders.then(function (sucessObj) {
        populateReaders(sucessObj);
    }, function (error){
        showMessage(error.message);
    });
}
function onDeviceInfo(id, element){
    var myDeviceVal = test.getDeviceInfoWithID(id);
    myDeviceVal.then(function (sucessObj) {
            console.log('sucessObj', sucessObj);
            var deviceId = sucessObj.DeviceID;
            var uidTyp = deviceUidType[sucessObj.eUidType];
            var modality = deviceModality[sucessObj.eDeviceModality];
            var deviceTech = deviceTechn[sucessObj.eDeviceTech];
            //Another method to get Device technology directly from SDK
            //Uncomment the below logging messages to see it working, Similarly for DeviceUidType and DeviceModality
            //console.log(Fingerprint.DeviceTechnology[sucessObj.eDeviceTech]);            
            //console.log(Fingerprint.DeviceModality[sucessObj.eDeviceModality]);
            //console.log(Fingerprint.DeviceUidType[sucessObj.eUidType]);
            var retutnVal = //"Device Info -"
                 "Id : " +  deviceId
                +"<br> Uid Type : "+ uidTyp
                +"<br> Device Tech : " +  deviceTech
                +"<br> Device Modality : " +  modality;

            document.getElementById(element).innerHTML = retutnVal;

        }, function (error){
            showMessage(error.message);
        });

}
function onClear() {
         var vDiv = document.getElementById('imagediv');
         vDiv.innerHTML = "";
         localStorage.setItem("imageSrc", "");
         localStorage.setItem("wsq", "");
         localStorage.setItem("raw", "");
         localStorage.setItem("intermediate", "");

         disableEnableExport(true);
}

function toggle_visibility(ids) {
    document.getElementById("qualityInputBox").value = "";
    onStop();
    enableDisableScanQualityDiv(ids[0]); // To enable disable scan quality div
    for (var i=0;i<ids.length;i++) {        
       var e = document.getElementById(ids[i]);
        if(i == 0){
            e.style.display = 'block';
            state = e;
            disableEnable();
        }
       else{
            e.style.display = 'none';
       }
   }
}


$("#save").on("click",function(){
    if(localStorage.getItem("imageSrc") == "" || localStorage.getItem("imageSrc") == null || document.getElementById('imagediv').innerHTML == ""){
        alert("Error -> Fingerprint not available");
    }else{
        // Store fingerprint data in hidden field
        document.getElementById('fingerprintData').value = localStorage.getItem("imageSrc").split(',')[1];
        
        var vDiv = document.getElementById('imageGallery');
        if(vDiv.children.length < 5){
            var image = document.createElement("img");
            image.id = "galleryImage";
            image.className = "img-thumbnail";
            image.src = localStorage.getItem("imageSrc");
            vDiv.appendChild(image);

            localStorage.setItem("imageSrc"+vDiv.children.length,localStorage.getItem("imageSrc"));
        }else{
            document.getElementById('imageGallery').innerHTML = "";
            $("#save").click();
        }
    }
});

function populateReaders(readersArray) {
        var _deviceInfoTable = document.getElementById("deviceInfo");
        _deviceInfoTable.innerHTML = "";
        if(readersArray.length != 0){
            _deviceInfoTable.innerHTML += "<h4>Available Readers</h4>"
            for (i=0;i<readersArray.length;i++){ 
                _deviceInfoTable.innerHTML += 
                "<div id='dynamicInfoDivs' align='left'>"+
                    "<div data-toggle='collapse' data-target='#"+readersArray[i]+"'>"+
                        "<img src='images/info.png' alt='Info' height='20' width='20'> &nbsp; &nbsp;"+readersArray[i]+"</div>"+
                        "<p class='collapse' id="+'"' + readersArray[i] + '"'+">"+onDeviceInfo(readersArray[i],readersArray[i])+"</p>"+
                    "</div>";
            }
        }
    };

function sampleAcquired(s){   
    try {
        const samples = JSON.parse(s.samples);
        if (!samples || samples.length === 0) return;

        // Always capture intermediate template 
        currentFormat = Fingerprint.SampleFormat.Intermediate;
        const templateData = Fingerprint.b64UrlTo64(samples[0].Data);
        document.getElementById('fingerprintData').value = templateData;

        // Show fingerprint icon 
        const imagediv = document.getElementById('imagediv');
        imagediv.innerHTML = '<i class="fas fa-fingerprint" style="font-size: 100px; color: #007bff;"></i>';

        // Automatically submit for matching
        submitFingerprint(templateData);
    } catch(error) {
        console.error('Sample processing error:', error);
        showMessage('Error processing fingerprint: ' + error.message);
    }
}

function submitFingerprint(fingerprintData) {
    $.ajax({
        url: 'tito_log.php',
        method: 'POST',
        data: {
            action: 'match_fingerprint',
            fingerprintData: fingerprintData
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if(result.success) {
                    // Stop capture after successful match
                    test.stopCapture();
                    
                    Swal.fire({
                        title: 'Match Found!',
                        text: `${result.sa.first_name} ${result.sa.last_name} - ${result.message}`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to refresh attendance data
                        location.reload();
                    });
                } else {
                    showMessage("No matching fingerprint found");
                    // Re-enable start button for another try
                    $('#start').prop('disabled', false);
                }
            } catch(e) {
                console.error('Parse error:', e);
                showMessage('Error processing response');
            }
        },
        error: function() {
            showMessage('Failed to process fingerprint match');
            $('#start').prop('disabled', false);
        }
    });
}

function updatePreview(imageData) {
    const img = new Image();
    img.onload = function() {
        const canvas = document.getElementById('fingerprint-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        }
    };
    img.src = imageData;
}

function matchWithDatabase(templateData) {
    $.ajax({
        url: 'tito_log.php',
        method: 'POST',
        data: {
            action: 'match_fingerprint',
            fingerprintData: templateData
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if(result.success) {
                    // Stop capture after successful match
                    test.stopCapture();
                    
                    Swal.fire({
                        title: 'Match Found!',
                        text: `${result.sa.first_name} ${result.sa.last_name} - ${result.message}`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to refresh attendance data
                        location.reload();
                    });
                } else {
                    showMessage("No matching fingerprint found");
                    // Re-enable capture for another try
                    $('#start').prop('disabled', false);
                }
            } catch(e) {
                console.error('Parse error:', e);
                showMessage('Error processing response');
            }
        },
        error: function() {
            showMessage('Failed to process fingerprint match');
        }
    });
}

// Add fingerprint matching functions
function matchFingerprint(template1, template2) {
    try {
        // Convert templates to proper format if needed
        const t1 = base64ToArrayBuffer(template1);
        const t2 = base64ToArrayBuffer(template2);
        
        // Use WebSDK matching function
        return Fingerprint.compareTemplates(t1, t2);
    } catch(error) {
        console.error('Matching error:', error);
        return 0;
    }
}

function base64ToArrayBuffer(base64) {
    const binaryString = window.atob(base64);
    const bytes = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
}

// Add new function to capture intermediate template
function captureIntermediateTemplate() {
    currentFormat = Fingerprint.SampleFormat.Intermediate;
    test.sdk.startAcquisition(currentFormat, myVal).then(function() {
        console.log("Started intermediate template capture");
    }, function(error) {
        console.error("Failed to start intermediate capture:", error);
        showMessage("Error capturing template: " + error.message);
    });
}

function readersDropDownPopulate(checkForRedirecting){ // Check for redirecting is a boolean value which monitors to redirect to content tab or not
    myVal = "";
    var allReaders = test.getInfo();
    allReaders.then(function (sucessObj) {        
        var readersDropDownElement = document.getElementById("readersDropDown");
        readersDropDownElement.innerHTML ="";
        //First ELement
        var option = document.createElement("option");
        option.selected = "selected";
        option.value = "";
        option.text = "Select Reader";
        readersDropDownElement.add(option);
        for (i=0;i<sucessObj.length;i++){ 
            var option = document.createElement("option");
            option.value = sucessObj[i];
            option.text = 'Digital Persona (' + sucessObj[i] + ')';
            readersDropDownElement.add(option);
        }

    //Check if readers are available get count and  provide user information if no reader available, 
    //if only one reader available then select the reader by default and sennd user to capture tab
    checkReaderCount(sucessObj,checkForRedirecting);

    }, function (error){
        showMessage(error.message);
    });
}

function checkReaderCount(sucessObj,checkForRedirecting){
   if(sucessObj.length == 0){
    alert("No reader detected. Please connect a reader.");
   }else if(sucessObj.length == 1){
        document.getElementById("readersDropDown").selectedIndex = "1";
        if(checkForRedirecting){
            toggle_visibility(['content-capture','content-reader']);    
            enableDisableScanQualityDiv("content-capture"); // To enable disable scan quality div
            setActive('Capture','Reader'); // Set active state to capture
        }
   }

    selectChangeEvent(); // To make the reader selected
}

function selectChangeEvent(){
    var readersDropDownElement = document.getElementById("readersDropDown");
    myVal = readersDropDownElement.options[readersDropDownElement.selectedIndex].value;
    disableEnable();
    onClear();
    document.getElementById('imageGallery').innerHTML = "";

    //Make capabilities button disable if no user selected
    if(myVal == ""){
        $('#capabilities').prop('disabled', true);
    }else{
        $('#capabilities').prop('disabled', false);
    }
}

function populatePopUpModal(){
    var modelWindowElement = document.getElementById("ReaderInformationFromDropDown");
    modelWindowElement.innerHTML = "";
    if(myVal != ""){
        onDeviceInfo(myVal,"ReaderInformationFromDropDown");
    }else{
        modelWindowElement.innerHTML = "Please select a reader";
    }
}

//Enable disable buttons
function disableEnable(){

    if(myVal != ""){
        disabled = false;
        $('#start').prop('disabled', false);
        $('#stop').prop('disabled', false);
        showMessage("");
        disableEnableStartStop();
    }else{
        disabled = true;
        $('#start').prop('disabled', true);
        $('#stop').prop('disabled', true);
        showMessage("Please select a reader");
        onStop();
    }
}


// Start-- Optional to make GUi user frindly 
//To make Start and stop buttons selection mutually exclusive
$('body').click(function(){disableEnableStartStop();});

function disableEnableStartStop(){
     if(!myVal == ""){
        if(test.acquisitionStarted){
            $('#start').prop('disabled', true);
            $('#stop').prop('disabled', false);
        }else{
            $('#start').prop('disabled', false);
            $('#stop').prop('disabled', true); 
        }
    }
}

// Stop-- Optional to make GUI user freindly


function enableDisableScanQualityDiv(id){
    if(id == "content-reader"){
        document.getElementById('Scores').style.display = 'none';
    }else{
        document.getElementById('Scores').style.display = 'block';
    }
}


function setActive(element1,element2){
    document.getElementById(element2).className = "";

    // And make this active
   document.getElementById(element1).className = "active";

}



// For Download and formats starts

function onImageDownload(){
    if(currentFormat == Fingerprint.SampleFormat.PngImage){
        if(localStorage.getItem("imageSrc") == "" || localStorage.getItem("imageSrc") == null || document.getElementById('imagediv').innerHTML == "" ){
           alert("No image to download");
        }else{
            //alert(localStorage.getItem("imageSrc"));
            downloadURI(localStorage.getItem("imageSrc"), "sampleImage.png", "image/png");
        }
    }

    else if(currentFormat == Fingerprint.SampleFormat.Compressed){
         if(localStorage.getItem("wsq") == "" || localStorage.getItem("wsq") == null || document.getElementById('imagediv').innerHTML == "" ){
           alert("WSQ data not available.");
        }else{
            downloadURI(localStorage.getItem("wsq"), "compressed.wsq","application/octet-stream");
        }
    }

    else if(currentFormat == Fingerprint.SampleFormat.Raw){
         if(localStorage.getItem("raw") == "" || localStorage.getItem("raw") == null || document.getElementById('imagediv').innerHTML == "" ){
           alert("RAW data not available.");
        }else{

            downloadURI("data:application/octet-stream;base64,"+localStorage.getItem("raw"), "rawImage.raw", "application/octet-stream");
        }
    }

    else if(currentFormat == Fingerprint.SampleFormat.Intermediate){
         if(localStorage.getItem("intermediate") == "" || localStorage.getItem("intermediate") == null || document.getElementById('imagediv').innerHTML == "" ){
           alert("Intermediate data not available.");
        }else{

            downloadURI("data:application/octet-stream;base64,"+localStorage.getItem("intermediate"), "FeatureSet.bin", "application/octet-stream");
        }
    }

    else{
        alert("Nothing to download.");
    }
}


function downloadURI(uri, name, dataURIType) {
    if (IeVersionInfo() > 0){ 
    //alert("This is IE " + IeVersionInfo());
    var blob = dataURItoBlob(uri,dataURIType);
    window.navigator.msSaveOrOpenBlob(blob, name);

    }else {
        //alert("This is not IE.");
        var save = document.createElement('a');
        save.href = uri;
        save.download = name;
        var event = document.createEvent("MouseEvents");
            event.initMouseEvent(
                    "click", true, false, window, 0, 0, 0, 0, 0
                    , false, false, false, false, 0, null
            );
        save.dispatchEvent(event);
    }
}

dataURItoBlob = function(dataURI, dataURIType) {
    var binary = atob(dataURI.split(',')[1]);
    var array = [];
    for(var i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    return new Blob([new Uint8Array(array)], {type: dataURIType});
}


function IeVersionInfo() {
  var sAgent = window.navigator.userAgent;
  var IEVersion = sAgent.indexOf("MSIE");

  // If IE, return version number.
  if (IEVersion > 0) 
    return parseInt(sAgent.substring(IEVersion+ 5, sAgent.indexOf(".", IEVersion)));

  // If IE 11 then look for Updated user agent string.
  else if (!!navigator.userAgent.match(/Trident\/7\./)) 
    return 11;

  // Quick and dirty test for Microsoft Edge
  else if (document.documentMode || /Edge/.test(navigator.userAgent))
    return 12;

  else
    return 0; //If not IE return 0
}


$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});

function checkOnly(stayChecked)
{
    disableEnableExport(true);
    onClear();
    onStop();
with(document.myForm)
  {
  for(i = 0; i < elements.length; i++)
    {
    if(elements[i].checked == true && elements[i].name != stayChecked.name)
      {
      elements[i].checked = false;
      }
    }
    //Enable disable save button
    for(i = 0; i < elements.length; i++)
    {
    if(elements[i].checked == true)
      {
        if(elements[i].name =="PngImage"){
            disableEnableSaveThumbnails(false);
        }else{
            disableEnableSaveThumbnails(true);
        }
      }
    }
  }
}         

function assignFormat(){
    currentFormat = "";
    with(document.myForm){
        for(i = 0; i < elements.length; i++){
            if(elements[i].checked == true){
                if(elements[i].name == "Raw"){
                    currentFormat = Fingerprint.SampleFormat.Raw;
                }
                if(elements[i].name == "Intermediate"){
                    currentFormat = Fingerprint.SampleFormat.Intermediate;
                }
                if(elements[i].name == "Compressed"){
                    currentFormat = Fingerprint.SampleFormat.Compressed;
                }
                if(elements[i].name == "PngImage"){
                    currentFormat = Fingerprint.SampleFormat.PngImage;
                }
                if(elements[i].name == "IsoTemplate"){
                    currentFormat = Fingerprint.SampleFormat.ISO;
                }
                if(elements[i].name == "AnsiTemplate"){
                    currentFormat = Fingerprint.SampleFormat.ANSI;
                }
            }
        }
    }
}


function disableEnableExport(val){
    if(val){
        $('#saveImagePng').prop('disabled', true);
    }else{
        $('#saveImagePng').prop('disabled', false); 
    }
}


function disableEnableSaveThumbnails(val){
    if(val){
        $('#save').prop('disabled', true);
    }else{
        $('#save').prop('disabled', false); 
    }
}


function delayAnimate(id,visibility){
                if(elements[i].name == "Raw"){
{
   document.getElementById(id).style.display = visibility;
}

// For Download and formats ends
                    currentFormat = Fingerprint.SampleFormat.Raw;
                }
                if(elements[i].name == "Intermediate"){
                    currentFormat = Fingerprint.SampleFormat.Intermediate;
                }
                if(elements[i].name == "Compressed"){
                    currentFormat = Fingerprint.SampleFormat.Compressed;
                }
                if(elements[i].name == "PngImage"){
                    currentFormat = Fingerprint.SampleFormat.PngImage;
                }
            }


function disableEnableExport(val){
    if(val){
        $('#saveImagePng').prop('disabled', true);
    }else{
        $('#saveImagePng').prop('disabled', false); 
    }
}


function disableEnableSaveThumbnails(val){
    if(val){
        $('#save').prop('disabled', true);
    }else{
        $('#save').prop('disabled', false); 
    }
}


function delayAnimate(id,visibility)
{
   document.getElementById(id).style.display = visibility;
}

// For Download and formats ends

// Update form submission handling
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fingerprintData = document.getElementById('fingerprintData').value;
    if (!fingerprintData) {
        Swal.fire('Error', 'Please capture fingerprint first', 'error');
        return;
    }

    // Submit the form
    this.submit();
});