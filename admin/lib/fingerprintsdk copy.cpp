#include <WiFi.h>
#include <HTTPClient.h>
#include <Adafruit_Fingerprint.h>
#include <WebServer.h>

// WiFi credentials
const char* ssid = "WiFi SSID";
const char* password = "WIfi password";
const char* serverUrl = "http://xx.xx.xx.xx/sa_management_system/fingerprint.php";

// DY50 uses Hardware Serial2 (pins 16,17 on ESP32)
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&Serial2);
WebServer server(80);

// Constants for fingerprint sensor
#define FINGERPRINT_CAPACITY 162  // DY50 can store up to 162 fingerprints
#define TEMPLATE_VERSION 0x01     // DY50 template version

// Update pin definitions
#define RX_PIN 16  // GPIO16 for RX2
#define TX_PIN 17  // GPIO17 for TX2
#define LED_PIN 2  // GPIO2 is the built-in LED

// Global state variables
bool isSensorReady = false;
bool isCapturing = false;

// Add global variable to track last used ID
uint16_t lastUsedId = 0;

void checkPinConnection() {
  Serial.println("\nChecking pin connections...");
  
  // Test TX pin (should be output)
  pinMode(TX_PIN, OUTPUT);
  digitalWrite(TX_PIN, HIGH);
  delay(100);
  
  // Test RX pin (should be input)
  pinMode(RX_PIN, INPUT_PULLUP);
  
  if (digitalRead(RX_PIN) == HIGH) {
    Serial.println("RX pin (GPIO16) connection detected");
  } else {
    Serial.println("Warning: Check RX pin connection");
  }
  
  // Restore pins for UART
  Serial2.end();
  delay(100);
  Serial2.begin(57600, SERIAL_8N1, RX_PIN, TX_PIN);
  delay(100);
}

void setup() {
    Serial.begin(115200);
    delay(100);
    Serial.println("\nFingerprint Sensor (DY50) Test");

    Serial2.begin(57600, SERIAL_8N1, RX_PIN, TX_PIN);
    delay(500);  // Slightly longer delay

    // Begin sensor at 57600; DY50 typically works here
    finger.begin(57600);
    // Additional delay to let DY50 fully init
    delay(1000);

    // Configure sensor settings for better capture
    if (finger.verifyPassword()) {
        // Set lowest security level
        finger.setSecurityLevel(1);
        // Set higher timeout for capture
        finger.setPassword(0x0); // Default password
        // Set LED behavior (if supported)
        finger.LEDcontrol(true);
        
        Serial.println("Sensor configured for optimal capture");
    }

    // Verify password routine
    int attempts = 0;
    bool verified = false;
    while (attempts < 5 && !verified) {
        if (finger.verifyPassword()) {
            verified = true;
            Serial.println("Found DY50 sensor!");
            digitalWrite(LED_PIN, HIGH);
        } else {
            Serial.println("Cannot find sensor (attempt " + String(attempts + 1) + ")");
            delay(1000);
            attempts++;
        }
    }
    if (!verified) {
        Serial.println("ERROR: Could not verify DY50 sensor password");
        while (true) {
            digitalWrite(LED_PIN, HIGH);
            delay(250);
            digitalWrite(LED_PIN, LOW);
            delay(250);
        }
    }

    // Read and display sensor parameters (optional but helpful)
    if (finger.getParameters() == FINGERPRINT_OK) {
        Serial.println("Sensor parameters read successfully");
        Serial.println("Capacity: " + String(finger.capacity));
        Serial.println("Security level: " + String(finger.security_level));
    } else {
        Serial.println("Warning: Unable to read sensor parameters");
    }

    // Connect to WiFi
    Serial.print("Connecting to WiFi");
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
  
    // Print network information
    Serial.println("\n=========================");
    Serial.println("Network Information:");
    Serial.print("Connected to SSID: ");
    Serial.println(WiFi.SSID());
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());
    Serial.print("Subnet Mask: ");
    Serial.println(WiFi.subnetMask());
    Serial.print("Gateway IP: ");
    Serial.println(WiFi.gatewayIP());
    Serial.print("DNS Server: ");
    Serial.println(WiFi.dnsIP());
    Serial.print("Signal Strength (RSSI): ");
    Serial.print(WiFi.RSSI());
    Serial.println(" dBm");
    Serial.println("=========================\n");

    // Setup HTTP endpoints
    setupEndpoints();
    isSensorReady = true;
  
    Serial.println("System ready for fingerprint capture");
}

void setupSensor() {
    finger.begin(57600);
    if (!finger.verifyPassword()) {
        Serial.println("Fingerprint sensor not found or password invalid");
        // Optionally retry or halt execution here
    } else {
        uint16_t count = finger.getTemplateCount();
        Serial.print("Templates currently stored: ");
        Serial.println(count);
    }
}

void setupEndpoints() {
    // Initialize sensor before starting endpoints
    setupSensor();
    
    // Simple root endpoint to verify connectivity
    server.on("/", HTTP_GET, []() {
        server.sendHeader("Access-Control-Allow-Origin", "*");
        server.send(200, "text/plain", "Fingerprint Sensor is alive");
    });

    // Status endpoint with more details
    server.on("/status", HTTP_GET, []() {
        server.sendHeader("Access-Control-Allow-Origin", "*");
        String response = "{\"status\":\"" + String(isSensorReady ? "ready" : "not_ready") + "\",";
        response += "\"sensor_connected\":" + String(finger.verifyPassword() ? "true" : "false") + ",";
        response += "\"is_capturing\":" + String(isCapturing ? "true" : "false") + "}";
        server.send(200, "application/json", response);
        Serial.println("Status requested: " + response);
    });

    // Enhanced capture endpoint
    server.on("/capture", HTTP_GET, []() {
        server.sendHeader("Access-Control-Allow-Origin", "*");
        
        if (!isSensorReady) {
            server.send(503, "application/json", "{\"error\":\"Sensor not ready\"}");
            return;
        }

        // Turn on LED if supported
        finger.LEDcontrol(true);
        
        String response = captureFingerprint();
        server.send(200, "application/json", response);
        
        // Turn off LED after capture
        finger.LEDcontrol(false);
    });

    // Add new endpoint for storing fingerprint and getting ID
    server.on("/enrollFingerprint", HTTP_GET, []() {
        server.sendHeader("Access-Control-Allow-Origin", "*");
        
        if (!isSensorReady) {
            server.send(503, "application/json", "{\"error\":\"Sensor not ready\"}");
            return;
        }

        String response = enrollFingerprint();
        server.send(200, "application/json", response);
    });

    // Add new endpoint for fingerprint matching and attendance
    server.on("/matchFingerprint", HTTP_GET, []() {
        server.sendHeader("Access-Control-Allow-Origin", "*");
        String response = matchFingerprint(); 
        server.send(200, "application/json", response);
    });

    server.onNotFound([](){
        server.sendHeader("Access-Control-Allow-Origin", "*");
        server.send(404, "application/json", "{\"error\":\"Not found\"}");
    });

    // Print number of stored templates to Serial monitor for debugging
    Serial.print("Templates currently stored: ");
    Serial.println(finger.templateCount);

    server.begin();
    Serial.println("HTTP server started");
}

String captureFingerprint() {
    Serial.println("Starting capture sequence...");
    
    // Remove the emptyDatabase() call
    
    uint8_t p = FINGERPRINT_NOFINGER;
    int timeout = 0;
    
    while (p != FINGERPRINT_OK && timeout < 30) {
        p = finger.getImage();
        if (p == FINGERPRINT_OK) {
            Serial.println("First image taken");
            
            // Convert first image
            p = finger.image2Tz(1);
            if (p != FINGERPRINT_OK) {
                return "{\"status\":\"error\",\"message\":\"First conversion failed\"}";
            }
            
            Serial.println("First conversion successful");
            return "{\"status\":\"firstCaptureOk\",\"message\":\"First capture successful\"}";
        }
        delay(100);
        timeout++;
    }

    // Second image capture
    timeout = 0;
    p = FINGERPRINT_NOFINGER;
    
    while (p != FINGERPRINT_OK && timeout < 30) {
        p = finger.getImage();
        if (p == FINGERPRINT_OK) {
            Serial.println("Second image taken");
            
            // Convert second image
            p = finger.image2Tz(2);
            if (p != FINGERPRINT_OK) {
                return "{\"status\":\"error\",\"message\":\"Second conversion failed\"}";
            }
            
            Serial.println("Second conversion successful");
            
            // Create and verify model
            p = finger.createModel();
            if (p != FINGERPRINT_OK) {
                return "{\"status\":\"error\",\"message\":\"Failed to create model\"}";
            }

            // Store model
            p = finger.storeModel(1);
            if (p == FINGERPRINT_OK) {
                return "{\"status\":\"captured\",\"message\":\"Fingerprint captured successfully\",\"quality\":\"" + String(finger.confidence) + "\"}";
            }
            
            return "{\"status\":\"error\",\"message\":\"Failed to store model\"}";
        }
        delay(100);
        timeout++;
    }
    
    return "{\"status\":\"error\",\"message\":\"Capture timeout\"}";
}

String enrollFingerprint() {
    uint8_t p = -1;
    int id = -1;

    // First check the real last used ID from the sensor
    for (int i = 1; i <= finger.capacity; i++) {
        if (finger.loadModel(i) == FINGERPRINT_OK) {
            if (i > lastUsedId) {
                lastUsedId = i;
            }
        }
    }

    // Find next available ID
    id = lastUsedId + 1;
    if (id > finger.capacity) {
        id = 1; // Start over if we reached capacity
    }

    // Verify this slot is actually free
    while (finger.loadModel(id) == FINGERPRINT_OK && id <= finger.capacity) {
        id++;
        if (id > finger.capacity) {
            id = 1; // Wrap around to beginning
        }
        if (id == lastUsedId + 1) {
            return "{\"status\":\"error\",\"message\":\"No free slots available\"}";
        }
    }

    // First capture
    while (p != FINGERPRINT_OK) {
        p = finger.getImage();
        switch (p) {
            case FINGERPRINT_OK:
                break;
            case FINGERPRINT_NOFINGER:
                continue;
            default:
                return "{\"status\":\"error\",\"message\":\"Error capturing first image\"}";
        }
    }

    p = finger.image2Tz(1);
    if (p != FINGERPRINT_OK) {
        return "{\"status\":\"error\",\"message\":\"Error converting first image\"}";
    }

    // Wait for finger removal
    while (finger.getImage() != FINGERPRINT_NOFINGER) {
        delay(100);
    }

    // Second capture for verification
    p = -1;
    while (p != FINGERPRINT_OK) {
        p = finger.getImage();
        switch (p) {
            case FINGERPRINT_OK:
                break;
            case FINGERPRINT_NOFINGER:
                continue;
            default:
                return "{\"status\":\"error\",\"message\":\"Error capturing second image\"}";
        }
    }

    p = finger.image2Tz(2);
    if (p != FINGERPRINT_OK) {
        return "{\"status\":\"error\",\"message\":\"Error converting second image\"}";
    }

    // Create model
    p = finger.createModel();
    if (p != FINGERPRINT_OK) {
        return "{\"status\":\"error\",\"message\":\"Failed to create model\"}";
    }

    // Store model with our selected ID
    p = finger.storeModel(id);
    if (p != FINGERPRINT_OK) {
        return "{\"status\":\"error\",\"message\":\"Failed to store model\"}";
    }

    // Update lastUsedId only after successful storage
    lastUsedId = id;
    
    // Save lastUsedId to EEPROM or similar for persistence
    
    String response = "{\"status\":\"success\",\"fingerprintId\":" + String(id) + 
                     ",\"confidence\":" + String(finger.confidence) + "}";
    return response;
}

// Modified matchFingerprint function to use fingerFastSearch directly
String matchFingerprint() {
    uint8_t p = finger.getImage();
    if (p != FINGERPRINT_OK) {
        if (p == FINGERPRINT_NOFINGER) {
            Serial.println("Waiting for finger...");
            return "{\"status\":\"waiting\",\"message\":\"Waiting for finger\"}";
        }
        Serial.println("Error capturing image");
        return "{\"status\":\"error\",\"message\":\"Error capturing fingerprint\"}";
    }

    Serial.println("Image captured, converting...");
    p = finger.image2Tz();
    if (p != FINGERPRINT_OK) {
        Serial.println("Conversion failed");
        return "{\"status\":\"error\",\"message\":\"Error converting image\"}";
    }

    Serial.println("Searching for match...");
    p = finger.fingerFastSearch();
    if (p == FINGERPRINT_OK) {
        Serial.println("\n=========================");
        Serial.println("Found fingerprint match!");
        Serial.print("Found ID #"); Serial.println(finger.fingerID);
        Serial.print("Confidence: "); Serial.println(finger.confidence);
        Serial.println("=========================\n");
        
        String response = "{\"status\":\"success\",\"fingerprintId\":" + String(finger.fingerID) +
                         ",\"confidence\":" + String(finger.confidence) + "}";
        return response;
    } 
    
    if (p == FINGERPRINT_NOTFOUND) {
        Serial.println("No match found");
        return "{\"status\":\"not_found\",\"message\":\"No matching fingerprint found\"}";
    }
    
    Serial.println("Unknown error");
    return "{\"status\":\"error\",\"message\":\"Search error\"}";
}

// Remove or comment out captureAndGetTemplate since we're not using it anymore
// String captureAndGetTemplate() { ... }

void printSensorDetails() {
  // Get sensor parameters
  uint8_t p = finger.getParameters();
  if (p != FINGERPRINT_OK) {
    Serial.println("Error getting sensor parameters");
    return;
  }
  
  Serial.println("\nSensor Details:");
  Serial.print("Status: 0x"); Serial.println(finger.status_reg, HEX);
  Serial.print("System ID: 0x"); Serial.println(finger.system_id, HEX);
  Serial.print("Capacity: "); Serial.println(finger.capacity);
  Serial.print("Security level: "); Serial.println(finger.security_level);
}

uint8_t getFingerprintID() {
  uint8_t p = finger.getImage();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    case FINGERPRINT_NOFINGER:
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK) {
    Serial.println("Image conversion failed");
    return p;
  }

  p = finger.fingerFastSearch();
  if (p == FINGERPRINT_OK) {
    Serial.print("Found ID #"); Serial.println(finger.fingerID);
    sendAttendance(finger.fingerID);
  } else if (p == FINGERPRINT_NOTFOUND) {
    Serial.println("Did not find a match");
  }
  
  return p;
}

void sendAttendance(uint16_t fingerID) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    
    String jsonData = "{\"finger_id\":" + String(fingerID) + "}";
    int httpCode = http.POST(jsonData);
    
    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("Server response: " + response);
    }
    http.end();
  }
}

void loop() {
    server.handleClient();
    
    // Print periodic sensor status - only log when state changes
    static bool lastFingerState = false;
    static unsigned long lastCheck = 0;
    
    if (millis() - lastCheck >= 1000) { // Check every second
        lastCheck = millis();
        
        if (isSensorReady) {
            uint8_t p = finger.getImage();
            bool currentFingerState = (p != FINGERPRINT_NOFINGER);
            
            if (currentFingerState != lastFingerState) {
                if (currentFingerState) {
                    Serial.println("Finger detected!");
                } else {
                    Serial.println("Finger removed");
                }
                lastFingerState = currentFingerState;
            }
        }
    }
    
    delay(10);
}
