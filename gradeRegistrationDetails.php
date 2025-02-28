<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

$dbo = new Database();

// Step 1: Fetch all students along with their class
$query = "SELECT id, class FROM student_details";
$stmt = $dbo->conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Step 2: Fetch all class-to-grade mappings
$query = "SELECT id, code FROM grade_details"; 
$stmt = $dbo->conn->prepare($query);
$stmt->execute();
$grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert grades array into an associative array for easier lookup
$classToGrade = [];
foreach ($grades as $grade) {
    $classToGrade[$grade['code']] = $grade['id']; 
}

// Step 3: Assign students to their respective grades
$query = "INSERT INTO grade_registration (student_id, grade_id, session_id) 
          VALUES (:student_id, :grade_id, :session_id)";

$stmt = $dbo->conn->prepare($query);

$session_id = 1; // Change this to the active session ID

foreach ($students as $student) {
    $student_id = $student['id'];
    $class = $student['class'];

    // Check if class exists in the grade mapping
    if (isset($classToGrade[$class])) {
        $grade_id = $classToGrade[$class];

        try {
            $stmt->execute([
                ':student_id' => $student_id,
                ':grade_id' => $grade_id,
                ':session_id' => $session_id
            ]);
            echo "Student ID $student_id registered to grade ID $grade_id successfully.<br>";
        } catch (PDOException $e) {
            echo "Error registering student ID $student_id: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Class {$class} not found in grade details. Skipping student ID {$student_id}.<br>";
    }
}
?>
