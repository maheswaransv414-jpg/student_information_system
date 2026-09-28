<div class="sidebar">
    <h2>SIS</h2>

    <?php if ($_SESSION['role'] == "admin") { ?>
        <a href="../admin/add_student.php"><i class="fa fa-user-plus"></i> Add Student</a>
        <a href="../admin/view_students.php"><i class="fa fa-users"></i> View Students</a>
        <a href="../admin/add_faculty.php">Add Faculty</a>
        <a href="../admin/view_faculty.php">View Faculty</a>
        <a href="../admin/add_timetable.php">Add Timetable</a>
        <a href="../admin/view_timetable.php">View Timetable</a>
        <a href="../admin/manage_users.php">Manage Users</a>
    <?php } ?>

    <?php if ($_SESSION['role'] == "faculty") { ?>
        <a href="../faculty/add_marks.php"><i class="fa fa-pen"></i> Add Marks</a>
        <a href="../faculty/mark_attendance.php"><i class="fa fa-calendar"></i> Attendance</a>
        <a href="../faculty/attendance_shortage.php">Attendance Shortage</a>
        <a href="../faculty/view_students_progress.php">Student Progress</a>
        <a href="../faculty/add_internal_marks.php">Add Internal Marks</a>
        <a href="../faculty/student_results.php">Results & Division</a>
        <a href="../faculty/view_internal_results.php">Internal Results</a>
        <a href="../faculty/manage_leave.php">Manage Leave</a>

    <?php } ?>

    <?php if ($_SESSION['role'] == "student") { ?>
        <a href="../student/view_details.php"><i class="fa fa-user"></i> My Details</a>
        <a href="../student/view_faculty.php">Teaching Staff</a>
        <a href="../student/view_timetable.php">Timetable</a>
        <a href="../student/view_result.php">View Result</a>
        <a href="../student/apply_leave.php">Apply Leave</a>
        <a href="../student/leave_status.php">Leave Status</a>

    <?php } ?>

    <a class="logout" href="../logout.php">Logout</a>
</div>
