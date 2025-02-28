<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . "/attendanceapp/database/database.php";

function clearTable($dbo, $tabName)
{
    $c = "DELETE FROM " . $tabName; // Table name cannot be passed as a bound parameter
    $s = $dbo->conn->prepare($c);
    try {
        $s->execute();
    } catch (PDOException $oo) {
        // Handle exception if needed
    }
}

$dbo = new Database();

// Create grade_details table
$c = "CREATE TABLE grade_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE,  -- Ensure `code` is unique
    title VARCHAR(50),
    credit INT
)";

$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>grade_details table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating grade_details table: " . $o->getMessage();
}

// Clear existing data (optional)
clearTable($dbo, 'grade_details');

// Insert data into grade_details
$c = "INSERT INTO grade_details (code, title, credit) VALUES
('PG', 'PlayGroup', 1),
('NY', 'Nursery', 1),
('LKG', 'L.K.G', 1),
('UKG', 'U.K.G', 1),
('One', 'Apple One', 1),
('Two', 'Apple Two', 1),
('Three', 'Apple Three', 1),
('Four', 'Apple Four', 1),
('Five', 'Apple Five', 1),
('Six', 'Apple Six', 1),
('Seven', 'Apple Seven', 1),
('Eight', 'Apple Eight', 1),
('Nine', 'Apple Nine', 1),
('Ten', 'Apple Ten', 1)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>Data inserted into grade_details successfully.";
} catch (PDOException $o) {
    echo "<br>Error inserting data into grade_details: " . $o->getMessage();
}

// Create student_details table
$c = "CREATE TABLE student_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) UNIQUE,
    name VARCHAR(50),
    class VARCHAR(20),
    contact_number VARCHAR(10)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>student_details table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating student_details table: " . $o->getMessage();
}

// Insert data into student_details
$c = "INSERT INTO student_details (id, roll_no, name, class, contact_number) VALUES
(1, 'APP78001', 'Aakriti Shrestha', 'PG', '9801234567'),
(2, 'APP78002', 'Aashish Chaudhary', 'NY', '9801234568'),
(3, 'APP78003', 'Aastha Sharma', 'LKG', '9865375818'),
(4, 'APP78004', 'Adyatan Guragain', 'UKG', '9801234570'),
(5, 'APP78005', 'Ajaharul Ansari', 'One', '9801234571'),
(6, 'APP78006', 'Alish Paudel', 'Two', '9801234572'),
(7, 'APP78007', 'Arun Prakash Bhatta', 'Three', '9801234573'),
(8, 'APP78008', 'Bikesh Thakur', 'Four', '9801234574'),
(9, 'APP78009', 'Bipin Rai', 'Five', '9801234575'),
(10, 'APP78010', 'Deependra Bhul', 'Six', '9801234576'),
(11, 'APP78011', 'Ganesh Kafle', 'Seven', '9801234577'),
(12, 'APP78012', 'Hemanta Bahadur Chand', 'Eight', '9801234578'),
(13, 'APP78013', 'Kritan Nepal', 'Nine', '9801234579'),
(14, 'APP78014', 'Mahendra Singh Mahara', 'Ten', '9801234580'),
(15, 'APP78015', 'Manish Kandel', 'PG', '9801234581'),
(16, 'APP78016', 'Methal Kumar Yadav', 'NY', '9801234582'),
(17, 'APP78017', 'Nabin Shrestha', 'LKG', '9801234583'),
(18, 'APP78018', 'Nabin Acharya', 'UKG', '9801234584'),
(19, 'APP78019', 'Nabin Oli', 'One', '9801234585'),
(20, 'APP78020', 'Nischal Magar', 'Two', '9801234586'),
(21, 'APP78021', 'Pawan Koirala', 'Three', '9801234587'),
(22, 'APP78022', 'Prasanna Roka', 'Four', '9801234588'),
(23, 'APP78023', 'Pyalace Rai', 'Five', '9801234589'),
(24, 'APP78024', 'Riwaj Dev Shrestha', 'Six', '9801234590'),
(25, 'APP78025', 'Sagar Thakur', 'Seven', '9801234591'),
(26, 'APP78026', 'Sairaj Timilsina', 'Eight', '9801234592'),
(27, 'APP78027', 'Saloni Kumari Mandal', 'Nine', '9801234593'),
(28, 'APP78028', 'Sharwan Mahato', 'Ten', '9801234594'),
(29, 'APP78029', 'Ankit Baniya', 'PG', '9801234595'),
(30, 'APP78030', 'Sachet Chudal', 'NY', '9840306269'),
(31, 'APP78031', 'Sudeep Kandel', 'LKG', '9801234597'),
(32, 'APP78032', 'Suraj Tiwari', 'UKG', '9801234598'),
(33, 'APP78033', 'Tara Bahadur Paudel', 'One', '9801234599')";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>Data inserted into student_details successfully.";
} catch (PDOException $o) {
    echo "<br>Error inserting data into student_details: " . $o->getMessage();
}

// Create faculty_details table
$c = "CREATE TABLE faculty_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(20) UNIQUE,
    name VARCHAR(100),
    password VARCHAR(50)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>faculty_details table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating faculty_details table: " . $o->getMessage();
}

// Insert data into faculty_details
$c = "INSERT INTO faculty_details (id, user_name, password, name) VALUES
(1, 'sachet', '123', 'Sachet Chudal'),
(2, 'aim', '123', 'Aim Mainali'),
(3, 'raaz', '123', 'Raaz Khatiwada'),
(4, 'pooja', '123', 'Pooja Bhatta'),
(5, 'bimala', '123', 'Bimala Khadka'),
(6, 'maya', '123', 'Maya Basnet'),
(7, 'john', '123', 'John Doe'),
(8, 'jane', '123', 'Jane Doe'),
(9, 'alice', '123', 'Alice Smith'),
(10, 'bob', '123', 'Bob Johnson')";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>Data inserted into faculty_details successfully.";
} catch (PDOException $o) {
    echo "<br>Error inserting data into faculty_details: " . $o->getMessage();
}

// Create session_details table
$c = "CREATE TABLE session_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year INT,
    term VARCHAR(50),
    UNIQUE (year, term)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>session_details table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating session_details table: " . $o->getMessage();
}

// Insert data into session_details
$c = "INSERT INTO session_details (id, year, term) VALUES
(1, 2025, 'Apple Primary'),
(2, 2025, 'Apple Secondary')";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>Data inserted into session_details successfully.";
} catch (PDOException $o) {
    echo "<br>Error inserting data into session_details: " . $o->getMessage();
}

// Create grade_registration table
$c = "CREATE TABLE grade_registration (
    student_id INT,
    grade_id INT,
    session_id INT,
    PRIMARY KEY (student_id, grade_id, session_id)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>grade_registration table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating grade_registration table: " . $o->getMessage();
}

// Assign students to their respective classes and sessions
$c = "INSERT INTO grade_registration (student_id, grade_id, session_id)
SELECT sd.id, gd.id, 
    CASE 
        WHEN gd.code IN ('PG', 'NY', 'LKG', 'UKG', 'One') THEN 1 
        ELSE 2 
    END AS session_id
FROM student_details sd
JOIN grade_details gd ON sd.class = gd.code";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>Students assigned to their respective classes and sessions successfully.";
} catch (PDOException $o) {
    echo "<br>Error assigning students to classes: " . $o->getMessage();
}

// Create grade_allotment table
$c = "CREATE TABLE grade_allotment (
    faculty_id INT,
    grade_id INT,
    session_id INT,
    PRIMARY KEY (faculty_id, grade_id, session_id)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>grade_allotment table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating grade_allotment table: " . $o->getMessage();
}

// Assign classes to sessions and teachers
$c = "INSERT INTO grade_allotment (faculty_id, grade_id, session_id) VALUES
(1, (SELECT id FROM grade_details WHERE code = 'PG'), 1),
(2, (SELECT id FROM grade_details WHERE code = 'NY'), 1),
(3, (SELECT id FROM grade_details WHERE code = 'LKG'), 1),
(4, (SELECT id FROM grade_details WHERE code = 'UKG'), 1),
(5, (SELECT id FROM grade_details WHERE code = 'One'), 1),
(6, (SELECT id FROM grade_details WHERE code = 'Two'), 2),
(7, (SELECT id FROM grade_details WHERE code = 'Three'), 2),
(8, (SELECT id FROM grade_details WHERE code = 'Four'), 2),
(9, (SELECT id FROM grade_details WHERE code = 'Five'), 2),
(10, (SELECT id FROM grade_details WHERE code = 'Six'), 2)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>Data inserted into grade_allotment successfully.";
} catch (PDOException $o) {
    echo "<br>Error inserting data into grade_allotment: " . $o->getMessage();
}

// Create attendance_details table
$c = "CREATE TABLE attendance_details (
    faculty_id INT,
    grade_id INT,
    session_id INT,
    student_id INT,
    on_date DATE,
    status VARCHAR(10),
    PRIMARY KEY (faculty_id, grade_id, session_id, student_id, on_date)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>attendance_details table created successfully.";
} catch (PDOException $o) {
    echo "<br>Error creating attendance_details table: " . $o->getMessage();
}

echo "<br>All operations completed successfully.";
?>