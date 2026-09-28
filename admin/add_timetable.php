<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

// Load faculty list
$facultyList = $conn->query("SELECT name, subject FROM faculty ORDER BY name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = trim($_POST['course']);
    $day = trim($_POST['day']);
    $subject = trim($_POST['subject']);
    $staff_name = trim($_POST['staff_name']);
    $time_slot = trim($_POST['time_slot']);

    $sql = "INSERT INTO timetable (course, day, subject, staff_name, time_slot)
            VALUES ('$course', '$day', '$subject', '$staff_name', '$time_slot')";

    if ($conn->query($sql)) {
        $message = "Timetable entry added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>🗓 Add Timetable</h1>
    <p style="color:gray;">Create timetable entries for students</p>

    <div class="card" style="max-width:800px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>Course</label><br>
            <input type="text" name="course" placeholder="Example: IT" required><br><br>

            <label>Day</label><br>
            <select name="day" required>
                <option value="">Select Day</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
            </select><br><br>

            <label>Subject</label><br>
            <input type="text" name="subject" placeholder="Example: DBMS" required><br><br>

            <label>Staff Name</label><br>
            <input type="text" name="staff_name" placeholder="Example: Ravi Kumar" required><br><br>

            <label>Time Slot</label><br>
            <input type="text" name="time_slot" placeholder="Example: 9:00 AM - 10:00 AM" required><br><br>

            <button type="submit">Add Timetable</button>
        </form>
    </div>
</div>