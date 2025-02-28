<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

class faculty_details
{
    /**
     * Verifies faculty credentials (username and password).
     *
     * @param object $dbo Database connection object
     * @param string $un Username
     * @param string $pw Password
     * @return array Contains faculty ID and status message
     */
    public function verifyUser($dbo, $un, $pw)
    {
        $rv = ["id" => -1, "status" => "ERROR"];
        $c = "SELECT id, password FROM faculty_details WHERE user_name = :un";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([":un" => $un]);
            if ($s->rowCount() > 0) {
                $result = $s->fetchAll(PDO::FETCH_ASSOC)[0];
                if ($result['password'] == $pw) {
                    // All OK
                    $rv = ["id" => $result['id'], "status" => "ALL OK"];
                } else {
                    // Password does not match
                    $rv = ["id" => $result['id'], "status" => "Wrong password"];
                }
            } else {
                // Username does not exist
                $rv = ["id" => -1, "status" => "USER NAME DOES NOT EXIST"];
            }
        } catch (PDOException $e) {
            error_log("Error verifying user: " . $e->getMessage());
            $rv = ["id" => -1, "status" => "Database error"];
        }
        return $rv;
    }

    /**
     * Fetches the list of grades (classes) assigned to a faculty member in a specific session.
     *
     * @param object $dbo Database connection object
     * @param int $sessionid Session ID
     * @param int $facid Faculty ID
     * @return array List of grades with their details
     */
    public function getGradesInASession($dbo, $sessionid, $facid)
    {
        $rv = [];
        $c = "SELECT cd.id, cd.code, cd.title
              FROM grade_allotment AS ca
              JOIN grade_details AS cd ON ca.grade_id = cd.id
              WHERE ca.faculty_id = :facid AND ca.session_id = :sessionid";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute([":facid" => $facid, ":sessionid" => $sessionid]);
            $rv = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching grades: " . $e->getMessage());
        }
        return $rv;
    }
}
?>