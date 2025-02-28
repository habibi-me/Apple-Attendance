<?php
// Include the database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

// Initialize the database object
$dbo = new Database();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $roll_no = $_POST['roll_no'];
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $class = $_POST['class'];

    // Validate input
    if (empty($roll_no) || empty($name) || empty($contact_number) || empty($class)) {
        $message = "All fields are required!";
        $status = "error";
    } elseif (!preg_match('/^\d{10}$/', $contact_number)) {
        $message = "Contact number must be exactly 10 digits!";
        $status = "error";
    } else {
        // Sanitize input
        $roll_no = htmlspecialchars($roll_no);
        $name = htmlspecialchars($name);
        $contact_number = htmlspecialchars($contact_number);
        $class = htmlspecialchars($class);

        // Insert data into the database
        $query = "INSERT INTO student_details (roll_no, name, contact_number, class) VALUES (:roll_no, :name, :contact_number, :class)";
        $stmt = $dbo->conn->prepare($query);

        try {
            $stmt->execute([
                ':roll_no' => $roll_no,
                ':name' => $name,
                ':contact_number' => $contact_number,
                ':class' => $class
            ]);

            // Get the last inserted student ID
            $student_id = $dbo->conn->lastInsertId();

            // Map class to grade_id
            $class_to_grade_id = [
                'PG' => 1,
                'NY' => 2,
                'LKG' => 3,
                'UKG' => 4,
                'One' => 5,
                'Two' => 6,
                'Three' => 7,
                'Four' => 8,
                'Five' => 9,
                'Six' => 10,
                'Seven' => 11,
                'Eight' => 12,
                'Nine' => 13,
                'Ten' => 14
            ];

            $grade_id = $class_to_grade_id[$class]; // Get the grade_id based on the selected class
            $session_id = 1; // Assuming session_id = 1 for Apple Primary (adjust as needed)

            // Insert into grade_registration table
            $query = "INSERT INTO grade_registration (student_id, grade_id, session_id) VALUES (:student_id, :grade_id, :session_id)";
            $stmt = $dbo->conn->prepare($query);
            $stmt->execute([
                ':student_id' => $student_id,
                ':grade_id' => $grade_id,
                ':session_id' => $session_id
            ]);

            $message = "Student added successfully!";
            $status = "success";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
            $status = "error";
        }
    }

    // Redirect to the same page with a message
    header("Location: /attendanceapp/attendance.php?message=" . urlencode($message) . "&status=$status");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative; /* For positioning the back button */
        }
        .form-container h2 {
            text-align: center;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Back Button -->
        <a href="/attendanceapp/attendance.php" class="back-button" onclick="return checkSession()">Back</a>

        <h2>Add Student Details</h2>
        <form action="add_student.php" method="POST" onsubmit="return validateForm()">
            <label for="roll_no">Roll Number:</label>
            <input type="text" id="roll_no" name="roll_no" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" pattern="\d{10}" title="Please enter exactly 10 digits" required>

            <label for="class">Class:</label>
            <select id="class" name="class" required>
                <option value="">Select Class</option>
                <option value="PG">PlayGroup (PG)</option>
                <option value="NY">Nursery (NY)</option>
                <option value="LKG">L.K.G</option>
                <option value="UKG">U.K.G</option>
                <option value="One">Apple One</option>
                <option value="Two">Apple Two</option>
                <option value="Three">Apple Three</option>
                <option value="Four">Apple Four</option>
                <option value="Five">Apple Five</option>
                <option value="Six">Apple Six</option>
                <option value="Seven">Apple Seven</option>
                <option value="Eight">Apple Eight</option>
                <option value="Nine">Apple Nine</option>
                <option value="Ten">Apple Ten</option>
            </select>

            <input type="submit" value="Add Student">
        </form>

        <?php
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            $class = ($_GET['status'] === 'success') ? 'success' : 'error';
            echo "<div class='message $class'>$message</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript validation for contact number
        function validateForm() {
            const contactNumber = document.getElementById('contact_number').value;
            if (!/^\d{10}$/.test(contactNumber)) {
                alert('Contact number must be exactly 10 digits.');
                return false;
            }
            return true;
        }

        // Function to check session before redirecting
        // Function to check session before redirecting
function checkSession() {
    $.ajax({
        url: "/attendanceapp/ajaxhandler/checkSession.php",
        type: "GET", // Use GET instead of POST (if not required)
        dataType: "json",
        success: function(response) {
            if (response.valid) {
                window.location.href = "/attendanceapp/attendance.php";
            } else {
                window.location.href = "/attendanceapp/login.php";
            }
        },
        error: function() {
            alert("Error checking session. Redirecting to login.");
            window.location.href = "/attendanceapp/login.php"; // Fallback redirect
        }
    });
    return false; // Prevent default link behavior
}

    </script>
</body>
</html>