<?php
session_start();
include('config/dbcon.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['add_btn'])) {
        try {
            $fingerprint_data = $_POST['fingerprintData'] ?? '';
            $fingerprint_id = null;
            
            if (!empty($fingerprint_data)) {
                $json_data = json_decode($fingerprint_data, true);
                if ($json_data && isset($json_data['fingerprintId'])) {
                    $fingerprint_id = (int)$json_data['fingerprintId'];
                    error_log("Processing fingerprint ID: " . $fingerprint_id); 

                    $check_query = "SELECT id FROM student_assistant WHERE fingerprint_id = ?";
                    $check_stmt = mysqli_prepare($con, $check_query);
                    mysqli_stmt_bind_param($check_stmt, "i", $fingerprint_id);
                    mysqli_stmt_execute($check_stmt);
                    mysqli_stmt_store_result($check_stmt);
                    
                    if (mysqli_stmt_num_rows($check_stmt) > 0) {
                        mysqli_stmt_close($check_stmt);
                        throw new Exception("This fingerprint ID is already registered");
                    }
                    mysqli_stmt_close($check_stmt);
                } else {
                    error_log("Invalid fingerprint data format: " . $fingerprint_data);
                    throw new Exception("Invalid fingerprint data format");
                }
            }

            if (!$fingerprint_id) {
                error_log("No fingerprint ID provided");
                throw new Exception("Valid fingerprint ID required");
            }

            $fields = array(
                'student_id' => '', 'last_name' => '', 'first_name' => '', 'age' => '', 'sex' => '',
                'civil_status' => '', 'date_of_birth' => '', 'city_address' => '', 'contact_no1' => '',
                'contact_no2' => '', 'contact_no3' => '', 'province_address' => '', 'guardian' => '',
                'honor_award' => '', 'past_scholar' => '', 'program' => '', 'year' => '',
                'present_scholar' => '', 'work_experience' => '', 'special_talent' => '',
                'out_name1' => '', 'comp_add1' => '', 'cn1' => '', 'out_name2' => '',
                'comp_add2' => '', 'cn2' => '', 'out_name3' => '', 'comp_add3' => '',
                'cn3' => '', 'from_wit1' => '', 'comp_add4' => '', 'cn4' => '',
                'from_wit2' => '', 'comp_add5' => '', 'cn5' => '', 'from_wit3' => '',
                'comp_add6' => '', 'cn6' => '', 'fathers_name' => '', 'fathers_occ' => '',
                'fathers_income' => '', 'mothers_name' => '', 'mothers_occ' => '', 'mothers_income' => '',
                'siblings' => ''
            );

            foreach($fields as $field => $default) {
                $$field = isset($_POST[$field]) ? mysqli_real_escape_string($con, $_POST[$field]) : $default;
            }

            foreach($fields as $field => &$value) {
                if(isset($_POST[$field])) {
                    $value = mysqli_real_escape_string($con, $_POST[$field]);
                    error_log("Field $field = $value"); 
                }
            }

            $filename = '';
            if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
                $image = $_FILES['profile_image']['name'];
                if(!empty($image)) {

                    $upload_dir = "../images/uploads/profileImages/";
 
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $extension = pathinfo($image, PATHINFO_EXTENSION);
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $upload_path = $upload_dir . $filename;

                    if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        error_log("File uploaded successfully to: " . $upload_path);
                        $filename = 'images/uploads/profileImages/' . $filename; 
                    } else {
                        error_log("Failed to upload file to: " . $upload_path);
                        $filename = ''; 
                    }
                }
            } elseif(isset($_SESSION['temp_image'])) { 
                $temp_image_path = $_SESSION['temp_image'];
                if(!empty($temp_image_path) && file_exists($temp_image_path)) {

                    $upload_dir = "../images/uploads/profileImages/";
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $extension = pathinfo($temp_image_path, PATHINFO_EXTENSION);
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $final_path = $upload_dir . $filename;

                    if(rename($temp_image_path, $final_path)) {
                        error_log("File moved successfully to: " . $final_path);
                        $filename = 'images/uploads/profileImages/' . $filename;
                        unset($_SESSION['temp_image']);
                    } else {
                        error_log("Failed to move file to: " . $final_path);
                        $filename = ''; 
                    }
                }
            }

            error_log("Final image path being inserted into database: " . $filename);

            $work_in = isset($_POST['work_in']) ? $_POST['work_in'] : [];
            $work_string = !empty($work_in) ? implode(", ", $work_in) : '';

            error_log("Image filename: " . $filename);
            error_log("Work string: " . $work_string);

            $siblings_data = [];
            if (isset($_POST['sibling_name'])) {
                error_log("Received sibling data: " . print_r($_POST, true)); 
                
                $sibling_names = $_POST['sibling_name'] ?? [];
                $sibling_ages = $_POST['sibling_age'] ?? [];
                $sibling_levels = $_POST['sibling_level'] ?? [];
                $sibling_statuses = $_POST['sibling_status'] ?? [];
                
                for($i = 0; $i < count($sibling_names); $i++) {
                    if(!empty($sibling_names[$i])) {
                        $siblings_data[] = [
                            'name' => $sibling_names[$i],
                            'age' => $sibling_ages[$i] ?? '',
                            'level' => $sibling_levels[$i] ?? '',
                            'status' => $sibling_statuses[$i] ?? ''
                        ];
                    }
                }
            }
            if (!empty($siblings_data)) {
                $siblings = json_encode($siblings_data);
                if ($siblings === false) {
                    error_log("JSON encoding error: " . json_last_error_msg());
                    $siblings = json_encode([]);
                }
            } else {
                $siblings = json_encode([]);
            }

            error_log("Final siblings JSON: " . $siblings);


            try {
                $user_query = "INSERT INTO student_assistant (
                    student_id, last_name, first_name, age, sex, image, fingerprint_id,
                    civil_status, date_of_birth, city_address, contact_no1,
                    contact_no2, contact_no3, province_address, guardian,
                    honor_award, past_scholar, program, year, present_scholar,
                    work_experience, special_talent, work,
                    out_name1, comp_add1, cn1,
                    out_name2, comp_add2, cn2,
                    out_name3, comp_add3, cn3,
                    from_wit1, comp_add4, cn4,
                    from_wit2, comp_add5, cn5,
                    from_wit3, comp_add6, cn6,
                    fathers_name, fathers_occ, fathers_income,
                    mothers_name, mothers_occ, mothers_income,
                    siblings
                ) VALUES (" . str_repeat('?,', 46) . "?)";

                $stmt = mysqli_prepare($con, $user_query);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . mysqli_error($con));
                }
                $params = [
                    $student_id, $last_name, $first_name, $age, $sex, 
                    $filename, 
                    $fingerprint_id,
                    $civil_status, $date_of_birth, $city_address, $contact_no1,
                    $contact_no2, $contact_no3, $province_address, $guardian,
                    $honor_award, $past_scholar, $program, $year, $present_scholar,
                    $work_experience, $special_talent, $work_string,
                    $out_name1, $comp_add1, $cn1,
                    $out_name2, $comp_add2, $cn2,
                    $out_name3, $comp_add3, $cn3,
                    $from_wit1, $comp_add4, $cn4,
                    $from_wit2, $comp_add5, $cn5,
                    $from_wit3, $comp_add6, $cn6,
                    $fathers_name, $fathers_occ, $fathers_income,
                    $mothers_name, $mothers_occ, $mothers_income,
                    $siblings
                ];

                $types = 'sssssi' . str_repeat('s', count($params) - 6); 
                mysqli_stmt_bind_param($stmt, $types, 
                    $student_id,
                    $last_name, 
                    $first_name, 
                    $age, 
                    $sex, 
                    $filename, 
                    $fingerprint_id,
                    $civil_status, 
                    $date_of_birth, 
                    $city_address, 
                    $contact_no1,
                    $contact_no2, 
                    $contact_no3, 
                    $province_address, 
                    $guardian,
                    $honor_award, 
                    $past_scholar, 
                    $program, 
                    $year, 
                    $present_scholar,
                    $work_experience, 
                    $special_talent, 
                    $work_string,
                    $out_name1, 
                    $comp_add1, 
                    $cn1,
                    $out_name2, 
                    $comp_add2, 
                    $cn2,
                    $out_name3, 
                    $comp_add3, 
                    $cn3,
                    $from_wit1, 
                    $comp_add4, 
                    $cn4,
                    $from_wit2, 
                    $comp_add5, 
                    $cn5,
                    $from_wit3, 
                    $comp_add6, 
                    $cn6,
                    $fathers_name, 
                    $fathers_occ, 
                    $fathers_income,
                    $mothers_name, 
                    $mothers_occ, 
                    $mothers_income,
                    $siblings
                );

                if(mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = "Student Assistant added successfully";
                    error_log("Student Assistant added successfully with fingerprint ID: " . $fingerprint_id);
                    mysqli_stmt_close($stmt);
                    header("Location: view-register.php");
                    exit();
                } else {
                    error_log("Execute failed: " . mysqli_stmt_error($stmt));
                    throw new Exception("Failed to add student assistant: " . mysqli_stmt_error($stmt));
                }

            } catch(Exception $e) {
                error_log("Database error: " . $e->getMessage());
                $_SESSION['message'] = "Error adding student: " . $e->getMessage();
                if(isset($stmt)) {
                    mysqli_stmt_close($stmt);
                }
                header("Location: add4.php");
                exit();
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['message'] = "Error: " . $e->getMessage();
            header("Location: add4.php");
            exit();
        }
    }

    // Handle fingerprint data update (if applicable)
    if (isset($_POST['sa_id']) && isset($_POST['fingerprintData'])) {
        $sa_id = $_POST['sa_id'];
        $fingerprint_data = $_POST['fingerprintData'];

        if ($sa_id && $fingerprint_data) {
            // Decode the Base64 fingerprint data
            $decoded_fingerprint = base64_decode($fingerprint_data);
            if ($decoded_fingerprint === false) {
                $_SESSION['message'] = "Error decoding fingerprint data.";
                header("Location: add4.php");
                exit();
            }

            // Prepare SQL statement to insert fingerprint as BLOB
            $stmt = $con->prepare("UPDATE student_assistant SET fingerprint_image = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("bi", $fingerprint_blob, $sa_id);
                $fingerprint_blob = $decoded_fingerprint;
                $stmt->send_long_data(0, $fingerprint_blob);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Fingerprint data saved successfully.";
                } else {
                    $_SESSION['message'] = "Failed to save fingerprint data.";
                    error_log("Failed to execute fingerprint update: " . $stmt->error);
                }

                $stmt->close();
            } else {
                $_SESSION['message'] = "Database error: " . $con->error;
                error_log("Failed to prepare fingerprint update statement: " . $con->error);
            }
        } else {
            $_SESSION['message'] = "Incomplete data submitted.";
        }

        header("Location: add4.php");
        exit;
    }
}

function validateFingerprint($template, $quality) {
    if (empty($template)) {
        throw new Exception("Fingerprint template required");
    }

    if (!preg_match('/^[0-9A-F]{512,}$/i', $template)) {
        throw new Exception("Invalid template format");
    }

    if ($quality < 60) {
        throw new Exception("Fingerprint quality too low (min 60%)");
    }

    if (isDuplicateTemplate($template)) {
        throw new Exception("Fingerprint already registered");
    }

    return true;
}

function isDuplicateTemplate($template) {
    global $con;
    
    $binary = hex2bin($template);
    $stmt = $con->prepare("SELECT COUNT(*) FROM student_assistant WHERE fingerprint_image = ?");
    $stmt->bind_param("b", $binary);
    $stmt->execute();
    $count = 0;
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
    return $count > 0;
}
function storeFingerprint($studentId, $template) {
    global $con;
    
    $binary = hex2bin($template);
    $stmt = $con->prepare("UPDATE student_assistant SET fingerprint_image = ? WHERE id = ?");
    $stmt->bind_param("bi", $binary, $studentId);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to store fingerprint");
    }
    
    return true;
}

function validateFingerprintData($data) {

    if (!preg_match('/^[0-9A-F]+$/i', $data)) {
        throw new Exception("Invalid fingerprint data format");
    }

    if (strlen($data) < 100) {
        throw new Exception("Fingerprint template too short");
    }
    
    return true;
}
function isBase64($str) {
    return base64_encode(base64_decode($str, true)) === $str;
}
?>