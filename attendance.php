<?php
session_start();
if (!isset($_SESSION["current_user"]) || empty($_SESSION["current_user"])) {
    header("location:" . "/attendanceapp/login.php");
    die();
}
$facid = $_SESSION["current_user"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Apple Attendance Management System">
    <title>Apple Attendance</title>
    <link rel="stylesheet" href="css/attendance.css">
</head>

<body>
    <div class="page">
        <!-- Header Area -->
        <div class="header-area">
            <div class="logo-area">
                <h2 class="logo"><span class="apple">APPLE </span>&nbsp;ATTENDANCE</h2>
            </div>
            <div class="logout-area">
                <button class="btnlogout" id="btnAddStudent" aria-label="Add Student">ADD</button>
                <button class="btnlogout" id="btnLogout" aria-label="Logout">LOGOUT</button>
            </div>
        </div>

        <!-- Session Area -->
        <div class="session-area">
            <div class="label-area"><label>SESSION</label></div>
            <div class="dropdown-area">
                <select class="ddlclass" id="ddlclass">
                    <!-- Sessions will be loaded dynamically -->
                </select>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" style="display: none;">Loading...</div>

        <!-- Class List Area -->
        <div class="classlist-area" id="classlistarea">
            <!-- Class cards will be loaded dynamically -->
        </div>

        <!-- Class Details Area -->
        <div class="classdetails-area" id="classdetailsarea">
            <!-- Class details will be loaded dynamically -->
        </div>

        <!-- Student List Area -->
        <div class="studentlist-area" id="studentlistarea">
            <!-- Student list will be loaded dynamically -->
        </div>

        <!-- Footer Area -->
        <div class="footer-area">
            <p>&copy; 2023 Apple Attendance. All rights reserved.</p>
        </div>
    </div>

    <!-- Hidden Inputs -->
    <input type="hidden" id="hiddenFacId" value="<?php echo $facid; ?>">
    <input type="hidden" id="hiddenSelectedGradeID" value="-1">

    <!-- JavaScript Files -->
    <script src="js/jquery.js"></script>
    <script src="js/attendance.js"></script>
</body>

</html>