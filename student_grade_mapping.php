<?php
// Include the database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

// Initialize the database object
$dbo = new Database();

// Fetch all students from student_details
$query = "SELECT id, class FROM student_details";
$stmt = $dbo->conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all grades from grade_details
$query = "SELECT id, grade FROM grade_details";
$stmt = $dbo->conn->prepare($query);
$stmt->execute();
$grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a mapping of grade names to grade IDs
$gradeMap = [];
foreach ($grades as $grade) {
    $gradeMap[$grade['grade']] = $grade['id'];
}

// Insert data into student_grade_mapping
foreach ($students as $student) {
    $studentId = $student['id'];
    $class = $student['class'];

    // Get the grade_id based on the class
    if (isset($gradeMap[$class])) {
        $gradeId = $gradeMap[$class];

        // Insert into student_grade_mapping
        $query = "INSERT INTO student_grade_mapping (student_id, grade_id) VALUES (:student_id, :grade_id)";
        $stmt = $dbo->conn->prepare($query);
        $stmt->execute([
            ':student_id' => $studentId,
            ':grade_id' => $gradeId
        ]);
    } else {
        echo "Grade not found for class: $class<br>";
    }
}

echo "Students have been assigned to their respective grades.";
?>