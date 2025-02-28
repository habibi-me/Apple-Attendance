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
$c = "CREATE TABLE grade_details (
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

// Step 1: Clear existing data (optional)
$c = "TRUNCATE TABLE grade_details";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo "<br>grade_details table cleared successfully.";
} catch (PDOException $o) {
    echo "<br>Error clearing grade_details table: " . $o->getMessage();
}

// Step 2: Insert data without explicit `id` values
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

$c = "create table faculty_details
(
    id int auto_increment primary key,
    user_name varchar(20) unique,
    name varchar(100),
    password varchar(50)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo ("<br>faculty_details created");
} catch (PDOException $o) {
    echo ("<br>faculty_details not created");
}


$c = "create table session_details
(
    id int auto_increment primary key,
    year int,
    term varchar(50),
    unique (year,term)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo ("<br>session_details created");
} catch (PDOException $o) {
    echo ("<br>session_details not created");
}



$c = "create table grade_registration
(
    student_id int,
    grade_id int,
    session_id int,
    primary key (student_id,grade_id,session_id)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo ("<br>grade_registration created");
} catch (PDOException $o) {
    echo ("<br>grade_registration not created");
}

$c = "create table grade_allotment
(
    faculty_id int,
    grade_id int,
    session_id int,
    primary key (faculty_id,grade_id,session_id)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo ("<br>grade_allotment created");
} catch (PDOException $o) {
    echo ("<br>grade_allotment not created");
}

$c = "create table attendance_details
(
    faculty_id int,
    grade_id int,
    session_id int,
    student_id int,
    on_date date,
    status varchar(10),
    primary key (faculty_id,grade_id,session_id,student_id,on_date)
)";
$s = $dbo->conn->prepare($c);
try {
    $s->execute();
    echo ("<br>attendance_details created");
} catch (PDOException $o) {
    echo ("<br>attendance_details not created");
}



$c = "insert into faculty_details
(id,user_name,password,name)
values
(1,'sachet','123','Sachet Chudal'),
(2,'aim','123','Aim Mainali'),
(3,'raaz','123','Raaz Khatiwada'),
(4,'pooja','123','Pooja Bhatta'),
(5,'bimala','123','Bimala Khadka'),
(6,'maya','123','Maya Basnet')";

$s = $dbo->conn->prepare($c);
try {
    $s->execute();
} catch (PDOException $o) {
    echo ("<br>duplicate entry");
}


$c = "insert into session_details
(id,year,term)
values
(1,2025,'Apple Primary'),
(2,2025,'Apple Secondary')";

$s = $dbo->conn->prepare($c);
try {
    $s->execute();
} catch (PDOException $o) {
    echo ("<br>duplicate entry");
}


//if any record already there in the table delete them
clearTable($dbo, "grade_registration");
$c = "insert into grade_registration
  (student_id, grade_id, session_id)
  values
  (:sid, :cid, :sessid)";
$s = $dbo->conn->prepare($c);

//iterate over all the 33 students
for ($i = 1; $i <= 33; $i++) {
    // Assign grades to Apple Primary (session_id = 1)
    $primaryGrades = [1, 2, 3, 4, 5]; // PG, NY, LKG, UKG, One
    foreach ($primaryGrades as $cid) {
        try {
            $s->execute([":sid" => $i, ":cid" => $cid, ":sessid" => 1]);
        } catch (PDOException $pe) {
            // Handle exception if needed
        }
    }

    // Assign grades to Apple Secondary (session_id = 2)
    $secondaryGrades = [6, 7, 8, 9, 10, 11, 12, 13, 14]; // Two, Three, ..., Ten
    foreach ($secondaryGrades as $cid) {
        try {
            $s->execute([":sid" => $i, ":cid" => $cid, ":sessid" => 2]);
        } catch (PDOException $pe) {
            // Handle exception if needed
        }
    }
}


//if any record already there in the table delete them
clearTable($dbo, "grade_allotment");
$c = "insert into grade_allotment
  (faculty_id, grade_id, session_id)
  values
  (:fid, :cid, :sessid)";
$s = $dbo->conn->prepare($c);

//iterate over all the 6 teachers
for ($i = 1; $i <= 6; $i++) {
    // Assign grades to Apple Primary (session_id = 1)
    $primaryGrades = [1, 2, 3, 4, 5]; // PG, NY, LKG, UKG, One
    foreach ($primaryGrades as $cid) {
        try {
            $s->execute([":fid" => $i, ":cid" => $cid, ":sessid" => 1]);
        } catch (PDOException $pe) {
            // Handle exception if needed
        }
    }

    // Assign grades to Apple Secondary (session_id = 2)
    $secondaryGrades = [6, 7, 8, 9, 10, 11, 12, 13, 14]; // Two, Three, ..., Ten
    foreach ($secondaryGrades as $cid) {
        try {
            $s->execute([":fid" => $i, ":cid" => $cid, ":sessid" => 2]);
        } catch (PDOException $pe) {
            // Handle exception if needed
        }
    }
}