<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_timetable.php");
    exit();
}

$id = (int) $_GET['id'];
$message = "";

$result = $conn->query("SELECT * FROM timetable WHERE id=$id");
if (!$result || $result->num_rows == 0) {
    echo "Timetable entry not found";
    exit();
}
$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = trim($_POST['course']);
    $day = trim($_POST['day']);
    $subject = trim($_POST['subject']);
    $staff_name = trim($_POST['staff_name']);
    $time_slot = trim($_POST['time_slot']);

    $sql = "UPDATE timetable 
            SET course='$course', day='$day', subject='$subject', staff_name='$staff_name', time_slot='$time_slot'
            WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "Timetable updated successfully!";
        $result = $conn->query("SELECT * FROM timetable WHERE id=$id");
        $data = $result->fetch_assoc();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>✏ Edit Timetable</h1>

    <div class="card" style="max-width:800px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>Course</label><br>
            <input type="text" name="course" value="<?php echo $data['course']; ?>" required><br><br>

            <label>Day</label><br>
            <select name="day" required>
                <option <?php if($data['day']=="Monday") echo "selected"; ?>>Monday</option>
                <option <?php if($data['day']=="Tuesday") echo "selected"; ?>>Tuesday</option>
                <option <?php if($data['day']=="Wednesday") echo "selected"; ?>>Wednesday</option>
                <option <?php if($data['day']=="Thursday") echo "selected"; ?>>Thursday</option>
                <option <?php if($data['day']=="Friday") echo "selected"; ?>>Friday</option>
                <option <?php if($data['day']=="Saturday") echo "selected"; ?>>Saturday</option>
            </select><br><br>

            <label>Subject</label><br>
            <input type="text" name="subject" value="<?php echo $data['subject']; ?>" required><br><br>

            <label>Faculty</label><br>
            <input type="text" name="staff_name" value="<?php echo $data['staff_name']; ?>" required><br><br>

            <label>Time Slot</label><br>
            <input type="text" name="time_slot" value="<?php echo $data['time_slot']; ?>" required><br><br>

            <button type="submit">Update Timetable</button>
        </form>
    </div>
</div>