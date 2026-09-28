<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != "student") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['student_id'])) {
    echo "Error: Student ID not set!";
    exit();
}

$student_id = $_SESSION['student_id'];

$result = $conn->query("SELECT * FROM students WHERE id=$student_id");

if ($result && $result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Student not found!";
    exit();
}

$course = $student['course'];

$timetable = $conn->query("SELECT * FROM timetable WHERE course='$course' ORDER BY 
    FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
    time_slot ASC");
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1><i class="fa fa-calendar-days"></i> My Timetable</h1>
    <p style="color:gray;">Course: <?php echo $course; ?></p>

    <div class="card" style="width:90%; margin:auto;">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Subject</th>
                    <th>Staff</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $timetable->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['day']; ?></td>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['staff_name']; ?></td>
                        <td><?php echo $row['time_slot']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>