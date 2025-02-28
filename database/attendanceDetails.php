<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

class attendanceDetails
{
    /**
     * Saves attendance for a student in a specific class, session, and date.
     * If the record already exists, updates the status.
     *
     * @param object $dbo Database connection object
     * @param int $session Session ID
     * @param int $grade Grade ID
     * @param int $fac Faculty ID
     * @param int $student Student ID
     * @param string $ondate Date of attendance (YYYY-MM-DD)
     * @param string $status Attendance status ('YES' or 'NO')
     * @return array [1] on success, [-1] on failure
     */
    public function saveAttendance($dbo, $session, $grade, $fac, $student, $ondate, $status)
    {
        $rv = [-1];
        $c = "INSERT INTO attendance_details
              (session_id, grade_id, faculty_id, student_id, on_date, status)
              VALUES
              (:session_id, :grade_id, :faculty_id, :student_id, :on_date, :status)";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([
                ":session_id" => $session,
                ":grade_id" => $grade,
                ":faculty_id" => $fac,
                ":student_id" => $student,
                ":on_date" => $ondate,
                ":status" => $status
            ]);
            $rv = [1];
        } catch (Exception $e) {
            // If the entry already exists, update the status
            $c = "UPDATE attendance_details SET status = :status
                  WHERE session_id = :session_id AND grade_id = :grade_id
                  AND faculty_id = :faculty_id AND student_id = :student_id
                  AND on_date = :on_date";
            $s = $dbo->conn->prepare($c);
            try {
                $s->execute([
                    ":session_id" => $session,
                    ":grade_id" => $grade,
                    ":faculty_id" => $fac,
                    ":student_id" => $student,
                    ":on_date" => $ondate,
                    ":status" => $status
                ]);
                $rv = [1];
            } catch (Exception $ee) {
                error_log("Error updating attendance: " . $ee->getMessage());
                $rv = [-1, "Error updating attendance: " . $ee->getMessage()];
            }
        }
        return $rv;
    }

    /**
     * Fetches the list of students who were present in a class on a specific date.
     *
     * @param object $dbo Database connection object
     * @param int $session Session ID
     * @param int $grade Grade ID
     * @param int $fac Faculty ID
     * @param string $ondate Date of attendance (YYYY-MM-DD)
     * @return array List of student IDs who were present
     */
    public function getPresentListOfAClassByAFacOnADate($dbo, $session, $grade, $fac, $ondate)
    {
        $rv = [];
        $c = "SELECT student_id FROM attendance_details
              WHERE session_id = :session_id AND grade_id = :grade_id
              AND faculty_id = :faculty_id AND on_date = :on_date
              AND status = 'YES'";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([
                ":session_id" => $session,
                ":grade_id" => $grade,
                ":faculty_id" => $fac,
                ":on_date" => $ondate
            ]);
            $rv = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching present list: " . $e->getMessage());
        }
        return $rv;
    }

    /**
     * Generates an attendance report for a specific class, session, and faculty.
     *
     * @param object $dbo Database connection object
     * @param int $session Session ID
     * @param int $grade Grade ID
     * @param int $fac Faculty ID
     * @return array Attendance report
     */
    public function getAttenDanceReport($dbo, $session, $grade, $fac)
    {
        $report = [];
        $sessionName = '';
        $facname = '';
        $gradeName = '';

        // Fetch session, faculty, and grade details
        $c = "SELECT sd.year, sd.term, fd.name AS faculty_name, gd.code, gd.title
              FROM session_details sd
              JOIN faculty_details fd ON fd.id = :fac
              JOIN grade_details gd ON gd.id = :grade
              WHERE sd.id = :session";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([":session" => $session, ":grade" => $grade, ":fac" => $fac]);
            $details = $s->fetch(PDO::FETCH_ASSOC);
            if ($details) {
                $sessionName = $details['year'] . " " . $details['term'];
                $facname = $details['faculty_name'];
                $gradeName = $details['code'] . "-" . $details['title'];
            }
        } catch (Exception $e) {
            error_log("Error fetching details: " . $e->getMessage());
        }

        array_push($report, ["Session:", $sessionName]);
        array_push($report, ["Grade:", $gradeName]);
        array_push($report, ["Faculty:", $facname]);

        // Fetch total classes and date range
        $total = 0;
        $start = '';
        $end = '';
        $c = "SELECT DISTINCT on_date FROM attendance_details
              WHERE session_id = :session_id AND grade_id = :grade_id
              AND faculty_id = :faculty_id
              ORDER BY on_date";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([":session_id" => $session, ":grade_id" => $grade, ":faculty_id" => $fac]);
            $rv = $s->fetchAll(PDO::FETCH_ASSOC);
            $total = count($rv);
            if ($total > 0) {
                $start = $rv[0]['on_date'];
                $end = $rv[$total - 1]['on_date'];
            }
        } catch (Exception $ee) {
            error_log("Error fetching class dates: " . $ee->getMessage());
        }

        array_push($report, ["total", $total]);
        array_push($report, ["start", $start]);
        array_push($report, ["end", $end]);

        // Fetch attendance details for each student
        $rv = [];
        $c = "SELECT rsd.id, rsd.roll_no, rsd.name, COUNT(ad.on_date) AS attended
              FROM (
                  SELECT sd.id, sd.roll_no, sd.name, crd.session_id, crd.grade_id
                  FROM student_details sd
                  JOIN grade_registration crd ON sd.id = crd.student_id
                  WHERE crd.session_id = :session_id AND crd.grade_id = :grade_id
              ) AS rsd
              LEFT JOIN attendance_details ad
              ON rsd.id = ad.student_id
              AND rsd.session_id = ad.session_id
              AND rsd.grade_id = ad.grade_id
              AND ad.faculty_id = :faculty_id
              AND ad.status = 'YES'
              GROUP BY rsd.id";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([":session_id" => $session, ":grade_id" => $grade, ":faculty_id" => $fac]);
            $rv = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ee) {
            error_log("Error fetching attendance report: " . $ee->getMessage());
        }

        // Calculate attendance percentage
        for ($i = 0; $i < count($rv); $i++) {
            $rv[$i]['percent'] = 0.00;
            if ($total > 0) {
                $rv[$i]['percent'] = round($rv[$i]['attended'] / $total * 100.0, 2);
            }
        }

        array_push($report, ["slno", "rollno", "name", "attended", "percent"]);
        $report = array_merge($report, $rv);

        return $report;
    }
}
?>