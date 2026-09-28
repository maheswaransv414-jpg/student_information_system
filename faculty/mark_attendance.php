<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['save_attendance'])) {
    $date = $_POST['attendance_date'];

    if (!empty($_POST['status'])) {
        foreach ($_POST['status'] as $student_id => $status) {
            $student_id = (int)$student_id;
            $status = $conn->real_escape_string($status);
            $date = $conn->real_escape_string($date);

            // Check if attendance already exists for this student on this date
            $check = $conn->query("SELECT id FROM attendance WHERE student_id=$student_id AND date='$date'");

            if ($check && $check->num_rows > 0) {
                // Update existing record
                $conn->query("UPDATE attendance SET status='$status' WHERE student_id=$student_id AND date='$date'");
            } else {
                // Insert new record
                $conn->query("INSERT INTO attendance (student_id, date, status) VALUES ($student_id, '$date', '$status')");
            }
        }

        echo "<script>alert('Attendance saved successfully!');</script>";
    } else {
        echo "<script>alert('Please mark attendance for students.');</script>";
    }
}

$students = $conn->query("SELECT id, name, reg_no, course FROM students ORDER BY name ASC");
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1><i class="fa fa-calendar-check"></i> Bulk Attendance</h1>
    <p style="color:gray;">Select one date and mark attendance for all students at once.</p>

    <div class="card" style="width:95%; margin:auto;">
        <form method="POST">
            <label><b>Attendance Date</b></label><br>
            <input type="date" name="attendance_date" required value="<?php echo date('Y-m-d'); ?>"><br><br>

            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['reg_no']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td>
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Present" required>
                            </td>
                            <td>
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="Absent" required>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <br>
            <button type="submit" name="save_attendance">
                <i class="fa fa-save"></i> Save Attendance
            </button>
        </form>
    </div>
</div>
