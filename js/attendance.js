/*
I will need this template many times ahead
$.ajax({
    url: "ajaxhandler/attendanceAJAX.php",
    type: "POST",
    dataType: "json",
    data: {},
    beforeSend: function(e) {},
    success: function(rv) {},
    error: function(e) {}
});
*/

/**
 * Generates HTML for session dropdown options.
 *
 * @param {Array} rv List of sessions
 * @returns {string} HTML for session dropdown
 */
function getSessionHTML(rv) {
    let x = `<option value=-1>SELECT ONE</option>`;
    for (let i = 0; i < rv.length; i++) {
        let cs = rv[i];
        x += `<option value=${cs['id']}>${cs['year'] + " " + cs['term']}</option>`;
    }
    return x;
}

/**
 * Loads sessions from the server and populates the dropdown.
 */
function loadSessions() {
    $.ajax({
        url: "ajaxhandler/attendanceAJAX.php",
        type: "POST",
        dataType: "json",
        data: { action: "getSession" },
        beforeSend: function(e) {},
        success: function(rv) {
            console.log("Sessions:", rv); // Log the sessions data
            let x = getSessionHTML(rv);
            $("#ddlclass").html(x);
        },
        error: function(e) {
            alert("Failed to load sessions. Please try again.");
        }
    });
}

/**
 * Generates HTML for grade cards.
 *
 * @param {Array} classlist List of grades
 * @returns {string} HTML for grade cards
 */
function getGradeCardHTML(classlist) {
    let x = ``;
    for (let i = 0; i < classlist.length; i++) {
        let cc = classlist[i];
        x += `<div class="classcard" data-classobject='${JSON.stringify(cc)}'>${cc['code']}</div>`;
    }
    return x;
}

/**
 * Fetches the list of grades assigned to the logged-in faculty for the selected session.
 *
 * @param {number} facid Faculty ID
 * @param {number} sessionid Session ID
 */
function fetchFacultyGrades(facid, sessionid) {
    $.ajax({
        url: "ajaxhandler/attendanceAJAX.php",
        type: "POST",
        dataType: "json",
        data: { facid: facid, sessionid: sessionid, action: "getFacultyGrades" },
        beforeSend: function(e) {},
        success: function(rv) {
            console.log("Grades:", rv); // Log the grades data
            let x = getGradeCardHTML(rv);
            $("#classlistarea").html(x);
        },
        error: function(e) {
            alert("Failed to fetch grades. Please try again.");
        }
    });
}

/**
 * Generates HTML for class details area.
 *
 * @param {Object} classobject Grade details
 * @returns {string} HTML for class details area
 */
function getClassdetailsAreaHTML(classobject) {
    let dobj = new Date();
    let year = dobj.getFullYear();
    let month = dobj.getMonth() + 1; // Months are 0-based
    let day = dobj.getDate();

    // Ensure two-digit month and day
    month = month < 10 ? `0${month}` : month;
    day = day < 10 ? `0${day}` : day;

    let ondate = `${year}-${month}-${day}`;
    let x = `<div class="classdetails">
                <div class="code-area">${classobject['code']}</div>
                <div class="title-area">${classobject['title']}</div>
                <div class="ondate-area">
                    <input type="date" value='${ondate}' id='dtpondate'>
                </div>
             </div>`;
    return x;
}

/**
 * Generates HTML for student list.
 *
 * @param {Array} studentList List of students
 * @returns {string} HTML for student list
 */
function getStudentListHTML(studentList) {
    let x = `<div class="studenttlist"><label>STUDENT LIST</label></div>`;
    for (let i = 0; i < studentList.length; i++) {
        let cs = studentList[i];
        let checkedState = cs['isPresent'] == 'YES' ? `checked` : ``;
        let rowcolor = cs['isPresent'] == 'YES' ? 'presentcolor' : 'absentcolor';
        x += `<div class="studentdetails ${rowcolor}" id="student${cs['id']}">
                  <div class="slno-area">${(i + 1)}</div>
                  <div class="rollno-area">${cs['roll_no']}</div>
                  <div class="name-area">${cs['name']}</div>
                  <div class="checkbox-area" data-studentid='${cs['id']}'>
                      <input type="checkbox" class="cbpresent" data-studentid='${cs['id']}' ${checkedState}>
                  </div>
              </div>`;
    }
    x += `<div class="reportsection">
              <button id="btnReport">REPORT</button>
          </div>
          <div id="divReport"></div>`;
    return x;
}

/**
 * Fetches the list of students for the selected class and date.
 *
 * @param {number} sessionid Session ID
 * @param {number} classid Grade ID
 * @param {number} facid Faculty ID
 * @param {string} ondate Date in YYYY-MM-DD format
 */
function fetchStudentList(sessionid, classid, facid, ondate) {
    if (sessionid == -1 || classid == -1 || facid == -1 || !ondate) {
        alert("Please select a valid session, class, and date.");
        return;
    }
    $.ajax({
        url: "ajaxhandler/attendanceAJAX.php",
        type: "POST",
        dataType: "json",
        data: { facid: facid, ondate: ondate, sessionid: sessionid, classid: classid, action: "getStudentList" },
        beforeSend: function(e) {},
        success: function(rv) {
            console.log("Students:", rv); // Log the students data
            let x = getStudentListHTML(rv);
            $("#studentlistarea").html(x);
        },
        error: function(e) {
            alert("Failed to fetch student list. Please try again.");
        }
    });
}

/**
 * Saves attendance for a student.
 *
 * @param {number} studentid Student ID
 * @param {number} gradeid Grade ID
 * @param {number} facultyid Faculty ID
 * @param {number} sessionid Session ID
 * @param {string} ondate Date in YYYY-MM-DD format
 * @param {string} ispresent Attendance status ('YES' or 'NO')
 */
function saveAttendance(studentid, gradeid, facultyid, sessionid, ondate, ispresent) {
    $.ajax({
        url: "ajaxhandler/attendanceAJAX.php",
        type: "POST",
        dataType: "json",
        data: { studentid: studentid, gradeid: gradeid, facultyid: facultyid, sessionid: sessionid, ondate: ondate, ispresent: ispresent, action: "saveattendance" },
        beforeSend: function(e) {},
        success: function(rv) {
            if (ispresent == "YES") {
                $("#student" + studentid).removeClass('absentcolor').addClass('presentcolor');
            } else {
                $("#student" + studentid).removeClass('presentcolor').addClass('absentcolor');
            }
        },
        error: function(e) {
            alert("Failed to save attendance. Please try again.");
        }
    });
}

/**
 * Downloads the attendance report in CSV format.
 *
 * @param {number} sessionid Session ID
 * @param {number} classid Grade ID
 * @param {number} facid Faculty ID
 */
function downloadCSV(sessionid, classid, facid) {
    $.ajax({
        url: "ajaxhandler/attendanceAJAX.php",
        type: "POST",
        dataType: "json",
        data: { sessionid: sessionid, classid: classid, facid: facid, action: "downloadReport" },
        beforeSend: function(e) {},
        success: function(rv) {
            let link = document.createElement('a');
            link.href = rv['filename'];
            link.download = `attendance_report_${sessionid}_${classid}_${facid}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        error: function(e) {
            alert("Failed to download the report. Please try again.");
        }
    });
}

// Event handlers
$(function() {
    $(document).on("click", "#btnLogout", function() {
        $.ajax({
            url: "ajaxhandler/logoutAjax.php",
            type: "POST",
            dataType: "json",
            data: { id: 1 },
            beforeSend: function(e) {},
            success: function(e) {
                document.location.replace("login.php");
            },
            error: function(e) {
                alert("Something went wrong!");
            }
        });
    });

    document.getElementById("btnAddStudent").addEventListener("click", function() {
        window.location.href = "database/add_student.php";
    });

    loadSessions();

    $(document).on("change", "#ddlclass", function() {
        $("#classlistarea").html(``);
        $("#classdetailsarea").html(``);
        $("#studentlistarea").html(``);
        let si = $("#ddlclass").val();
        if (si != -1) {
            let sessionid = si;
            let facid = $("#hiddenFacId").val();
            fetchFacultyGrades(facid, sessionid);
        }
    });

    $(document).on("click", ".classcard", function() {
        let classobject = $(this).data('classobject');
        $("#hiddenSelectedGradeID").val(classobject['id']);
        let x = getClassdetailsAreaHTML(classobject);
        $("#classdetailsarea").html(x);
        let sessionid = $("#ddlclass").val();
        let classid = classobject['id'];
        let facid = $("#hiddenFacId").val();
        let ondate = $("#dtpondate").val();
        if (sessionid != -1) {
            fetchStudentList(sessionid, classid, facid, ondate);
        }
    });

    $(document).on("click", ".cbpresent", function() {
        let ispresent = this.checked ? "YES" : "NO";
        let studentid = $(this).data('studentid');
        let gradeid = $("#hiddenSelectedGradeID").val();
        let facultyid = $("#hiddenFacId").val();
        let sessionid = $("#ddlclass").val();
        let ondate = $("#dtpondate").val();
        saveAttendance(studentid, gradeid, facultyid, sessionid, ondate, ispresent);
    });

    $(document).on("change", "#dtpondate", function() {
        let sessionid = $("#ddlclass").val();
        let classid = $("#hiddenSelectedGradeID").val();
        let facid = $("#hiddenFacId").val();
        let ondate = $("#dtpondate").val();
        if (sessionid != -1) {
            fetchStudentList(sessionid, classid, facid, ondate);
        }
    });

    $(document).on("click", "#btnReport", function() {
        let sessionid = $("#ddlclass").val();
        let classid = $("#hiddenSelectedGradeID").val();
        let facid = $("#hiddenFacId").val();
        downloadCSV(sessionid, classid, facid);
    });
});
$(document).on("change", "#ddlclass", function() {
    let si = $("#ddlclass").val();
    console.log("Selected Session ID:", si); // Log the selected session ID
    if (si != -1) {
        let sessionid = si;
        let facid = $("#hiddenFacId").val();
        fetchFacultyGrades(facid, sessionid);
    }
});