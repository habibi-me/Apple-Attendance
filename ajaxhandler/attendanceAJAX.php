<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";
require_once $path . "/attendanceapp/database/sessionDetails.php";
require_once $path . "/attendanceapp/database/facultyDetails.php";
require_once $path . "/attendanceapp/database/gradeRegistrationDetails.php";
require_once $path . "/attendanceapp/database/attendanceDetails.php";

/**
 * Creates a CSV file from a given list of data.
 *
 * @param array $list Data to write to the CSV file
 * @param string $filename Name of the CSV file
 * @return int 0 on success, 1 on error
 */
function createCSVReport($list, $filename) {
    $error = 0;
    $path = $_SERVER['DOCUMENT_ROOT'];
    $finalFileName = $path . $filename;

    // Validate filename to prevent directory traversal
    if (!preg_match('/^[a-zA-Z0-9_\-\.\/]+$/', $filename)) {
        return 1;
    }

    try {
        $fp = fopen($finalFileName, "w");
        if ($fp === false) {
            throw new Exception("Unable to open file for writing.");
        }
        foreach ($list as $line) {
            fputcsv($fp, $line);
        }
        fclose($fp);
    } catch (Exception $e) {
        error_log("Error creating CSV report: " . $e->getMessage());
        $error = 1;
    }
    return $error;
}

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];

    /**
     * Fetches the list of sessions from the database.
     */
    if ($action == "getSession") {
        try {
            $dbo = new Database();
            $sobj = new SessionDetails();
            $rv = $sobj->getSessions($dbo);
            echo json_encode($rv);
        } catch (Exception $e) {
            error_log("Error fetching sessions: " . $e->getMessage());
            echo json_encode(["status" => "ERROR", "message" => "An error occurred while fetching sessions."]);
        }
    }

    /**
     * Fetches the list of grades assigned to a faculty member for a specific session.
     */
    if ($action == "getFacultyGrades") {
        $facid = $_POST['facid'];
        $sessionid = $_POST['sessionid'];

        if (empty($facid) || empty($sessionid)) {
            echo json_encode(["status" => "ERROR", "message" => "Invalid input parameters."]);
            exit();
        }

        try {
            $dbo = new Database();
            $fo = new faculty_details();
            $rv = $fo->getGradesInASession($dbo, $sessionid, $facid);
            echo json_encode($rv);
        } catch (Exception $e) {
            error_log("Error fetching faculty grades: " . $e->getMessage());
            echo json_encode(["status" => "ERROR", "message" => "An error occurred while fetching faculty grades."]);
        }
    }

    /**
     * Fetches the list of students registered for a specific class and session.
     * Also checks which students were marked present on a given date.
     */
    if ($action == "getStudentList") {
        $classid = $_POST['classid'];
        $sessionid = $_POST['sessionid'];
        $facid = $_POST['facid'];
        $ondate = $_POST['ondate'];

        if (empty($classid) || empty($sessionid) || empty($facid) || empty($ondate)) {
            echo json_encode(["status" => "ERROR", "message" => "Invalid input parameters."]);
            exit();
        }

        try {
            $dbo = new Database();
            $crgo = new GradeRegistrationDetails();
            $allstudents = $crgo->getRegisteredStudents($dbo, $sessionid, $classid);

            $ado = new attendanceDetails();
            $presentStudents = $ado->getPresentListOfAClassByAFacOnADate($dbo, $sessionid, $classid, $facid, $ondate);

            // Mark students as present if they are in the present list
            for ($i = 0; $i < count($allstudents); $i++) {
                $allstudents[$i]['isPresent'] = 'NO'; // Default to absent
                for ($j = 0; $j < count($presentStudents); $j++) {
                    if ($allstudents[$i]['id'] == $presentStudents[$j]['student_id']) {
                        $allstudents[$i]['isPresent'] = 'YES';
                        break;
                    }
                }
            }
            echo json_encode($allstudents);
        } catch (Exception $e) {
            error_log("Error fetching student list: " . $e->getMessage());
            echo json_encode(["status" => "ERROR", "message" => "An error occurred while fetching the student list."]);
        }
    }

    /**
     * Saves or updates the attendance status of a student for a specific class, session, and date.
     */
    if ($action == "saveattendance") {
        $gradeid = $_POST['gradeid'];
        $sessionid = $_POST['sessionid'];
        $studentid = $_POST['studentid'];
        $facultyid = $_POST['facultyid'];
        $ondate = $_POST['ondate'];
        $status = $_POST['ispresent'];

        if (empty($gradeid) || empty($sessionid) || empty($studentid) || empty($facultyid) || empty($ondate) || empty($status)) {
            echo json_encode(["status" => "ERROR", "message" => "Invalid input parameters."]);
            exit();
        }

        try {
            $dbo = new Database();
            $ado = new attendanceDetails();
            $rv = $ado->saveAttendance($dbo, $sessionid, $gradeid, $facultyid, $studentid, $ondate, $status);
            echo json_encode($rv);
        } catch (Exception $e) {
            error_log("Error saving attendance: " . $e->getMessage());
            echo json_encode(["status" => "ERROR", "message" => "An error occurred while saving attendance."]);
        }
    }

    /**
     * Generates a CSV report of attendance data for a specific class, session, and faculty member.
     */
    if ($action == "downloadReport") {
        $gradeid = $_POST['classid'];
        $sessionid = $_POST['sessionid'];
        $facultyid = $_POST['facid'];

        if (empty($gradeid) || empty($sessionid) || empty($facultyid)) {
            echo json_encode(["status" => "ERROR", "message" => "Invalid input parameters."]);
            exit();
        }

        try {
            $dbo = new Database();
            $ado = new attendanceDetails();
            $list = $ado->getAttenDanceReport($dbo, $sessionid, $gradeid, $facultyid);

            $filename = "/attendanceapp/report.csv";
            $error = createCSVReport($list, $filename);

            if ($error == 0) {
                $rv = ["filename" => $filename];
                echo json_encode($rv);
            } else {
                echo json_encode(["status" => "ERROR", "message" => "Failed to generate the report."]);
            }
        } catch (Exception $e) {
            error_log("Error generating report: " . $e->getMessage());
            echo json_encode(["status" => "ERROR", "message" => "An error occurred while generating the report."]);
        }
    }
}
?>