<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";
require_once $path . "/attendanceapp/database/facultyDetails.php";

$action = $_REQUEST["action"];
if (!empty($action)) {
    if ($action == "verifyUser") {
        // Retrieve username and password from the request
        $un = $_POST["user_name"];
        $pw = $_POST["password"];

        // Validate inputs
        if (empty($un) || empty($pw)) {
            echo json_encode(["status" => "ERROR", "message" => "Username and password are required."]);
            exit();
        }

        // Verify user credentials
        try {
            $dbo = new Database();
            $fdo = new faculty_details();
            $rv = $fdo->verifyUser($dbo, $un, $pw);

            if ($rv['status'] == "ALL OK") {
                // Start a secure session
                session_start([
                    'cookie_lifetime' => 86400, // 1 day
                    'cookie_secure' => true, // Only send cookies over HTTPS
                    'cookie_httponly' => true, // Prevent JavaScript access to cookies
                ]);
                $_SESSION['current_user'] = $rv['id'];
            }
        } catch (Exception $e) {
            error_log("Error during login: " . $e->getMessage());
            $rv = ["status" => "ERROR", "message" => "An error occurred during login."];
        }

        // Send response
        echo json_encode($rv);
    }
    if ($action == "getFacultyGrades") {
      error_log("Fetching grades for faculty: " . $facid . " and session: " . $sessionid);
      // Rest of the code
  }
}
?>