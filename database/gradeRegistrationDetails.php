<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

class GradeRegistrationDetails
{
    private $dbo;

    public function __construct($dbo)
    {
        $this->dbo = $dbo;
    }

    /**
     * Fetches registered students for a given session and grade.
     *
     * @param int $sessionid The session ID.
     * @param int $gradeid The grade ID.
     * @return array An array of student details (id, roll_no, name).
     */
    public function getRegisteredStudents($sessionid, $gradeid)
    {
        $registeredStudents = [];

        // Validate input parameters
        if (!is_numeric($sessionid) || !is_numeric($gradeid)) {
            return $registeredStudents; // Return empty array if inputs are invalid
        }

        $query = "SELECT sd.id, sd.roll_no, sd.name 
                  FROM student_details AS sd
                  JOIN grade_registration AS crg 
                  ON crg.student_id = sd.id 
                  WHERE crg.session_id = :sessionid 
                  AND crg.grade_id = :gradeid";
        $stmt = $this->dbo->conn->prepare($query);
        try {
            $stmt->execute([":sessionid" => $sessionid, ":gradeid" => $gradeid]);
            $registeredStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching registered students: " . $e->getMessage());
            $registeredStudents = [];
        }
        return $registeredStudents;
    }

    /**
     * Counts the total number of registered students for a given session and grade.
     *
     * @param int $sessionid The session ID.
     * @param int $gradeid The grade ID.
     * @return int The total number of registered students.
     */
    public function countRegisteredStudents($sessionid, $gradeid)
    {
        $count = 0;

        // Validate input parameters
        if (!is_numeric($sessionid) || !is_numeric($gradeid)) {
            return $count; // Return 0 if inputs are invalid
        }

        $query = "SELECT COUNT(*) AS total 
                  FROM grade_registration 
                  WHERE session_id = :sessionid 
                  AND grade_id = :gradeid";
        $stmt = $this->dbo->conn->prepare($query);
        try {
            $stmt->execute([":sessionid" => $sessionid, ":gradeid" => $gradeid]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $result['total'];
        } catch (PDOException $e) {
            error_log("Error counting registered students: " . $e->getMessage());
        }
        return $count;
    }
}