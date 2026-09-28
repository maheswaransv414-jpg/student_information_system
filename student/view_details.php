<?php
session_start();
include("../config/db.php");

// Only student access
if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['student_id']) || empty($_SESSION['student_id'])) {
    echo "Error: Student ID not set!";
    exit();
}

$student_id = (int) $_SESSION['student_id'];

// Get student details safely
$result = $conn->query("SELECT * FROM students WHERE id=$student_id");

if ($result && $result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Student not found!";
    exit();
}

// Marks
$marks = $conn->query("SELECT * FROM marks WHERE student_id=$student_id");
$totalMarksRow = $conn->query("SELECT SUM(marks) AS total FROM marks WHERE student_id=$student_id")->fetch_assoc();
$avgMarksRow = $conn->query("SELECT AVG(marks) AS average_marks FROM marks WHERE student_id=$student_id")->fetch_assoc();
$maxMarksRow = $conn->query("SELECT MAX(marks) AS highest FROM marks WHERE student_id=$student_id")->fetch_assoc();
$minMarksRow = $conn->query("SELECT MIN(marks) AS lowest FROM marks WHERE student_id=$student_id")->fetch_assoc();

$totalMarks = $totalMarksRow['total'] ?? 0;
$averageMarks = $avgMarksRow['average_marks'] ?? 0;
$highestMarks = $maxMarksRow['highest'] ?? 0;
$lowestMarks = $minMarksRow['lowest'] ?? 0;

// Attendance
$attendance = $conn->query("SELECT * FROM attendance WHERE student_id=$student_id ORDER BY date DESC");
$presentCount = $conn->query("SELECT COUNT(*) AS total FROM attendance WHERE student_id=$student_id AND status='Present'")->fetch_assoc();
$absentCount = $conn->query("SELECT COUNT(*) AS total FROM attendance WHERE student_id=$student_id AND status='Absent'")->fetch_assoc();

$present = $presentCount['total'] ?? 0;
$absent = $absentCount['total'] ?? 0;
$totalClasses = $present + $absent;

$attendancePercentage = 0;
if ($totalClasses > 0) {
    $attendancePercentage = ($present / $totalClasses) * 100;
}
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">

    <h1><i class="fa fa-user"></i> My Details</h1>
    <p style="color:gray;">View your personal, academic, and attendance information</p>

    <!-- Attendance Shortage Alert -->
    <?php if ($attendancePercentage < 75) { ?>
        <div class="card" style="width:90%; margin:20px auto; border-left:6px solid red; background:#fff5f5;">
            <h3 style="color:red;">⚠ Attendance Shortage Alert</h3>
            <p>Your attendance is below the required 75%.</p>
            <p><b>Current Attendance:</b> <?php echo round($attendancePercentage, 2); ?>%</p>
        </div>
    <?php } else { ?>
        <div class="card" style="width:90%; margin:20px auto; border-left:6px solid green; background:#f5fff7;">
            <h3 style="color:green;">✅ Attendance Status Good</h3>
            <p>Your attendance is above the required 75%.</p>
            <p><b>Current Attendance:</b> <?php echo round($attendancePercentage, 2); ?>%</p>
        </div>
    <?php } ?>

    <!-- Personal Details -->
    <div class="card" style="width:90%; margin:20px auto;">
        <h3><i class="fa fa-id-card"></i> Personal Details</h3>

        <table class="styled-table">
            <tbody>
                <tr>
                    <th>Register Number</th>
                    <td><?php echo $student['reg_no']; ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo $student['name']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $student['email']; ?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?php echo $student['course']; ?></td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td><?php echo $student['dob']; ?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?php echo $student['gender']; ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $student['phone']; ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo $student['address']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Marks Summary -->
    <div class="card" style="width:90%; margin:20px auto;">
        <h3><i class="fa fa-square-poll-vertical"></i> Marks Summary</h3>

        <table class="styled-table">
            <tbody>
                <tr>
                    <th>Total Marks</th>
                    <td><?php echo $totalMarks; ?></td>
                </tr>
                <tr>
                    <th>Average Marks</th>
                    <td><?php echo round($averageMarks, 2); ?></td>
                </tr>
                <tr>
                    <th>Highest Mark</th>
                    <td><?php echo $highestMarks; ?></td>
                </tr>
                <tr>
                    <th>Lowest Mark</th>
                    <td><?php echo $lowestMarks; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Subject Marks -->
    <div class="card" style="width:90%; margin:20px auto;">
        <h3><i class="fa fa-book"></i> Subject Marks</h3>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $marks->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['marks']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Attendance Summary -->
    <div class="card" style="width:90%; margin:20px auto;">
        <h3><i class="fa fa-chart-pie"></i> Attendance Summary</h3>

        <table class="styled-table">
            <tbody>
                <tr>
                    <th>Total Present</th>
                    <td><?php echo $present; ?></td>
                </tr>
                <tr>
                    <th>Total Absent</th>
                    <td><?php echo $absent; ?></td>
                </tr>
                <tr>
                    <th>Total Classes</th>
                    <td><?php echo $totalClasses; ?></td>
                </tr>
                <tr>
                    <th>Attendance Percentage</th>
                    <td>
                        <?php
                        if ($attendancePercentage >= 75) {
                            echo "<span style='color:green; font-weight:bold;'>" . round($attendancePercentage, 2) . "%</span>";
                        } else {
                            echo "<span style='color:red; font-weight:bold;'>" . round($attendancePercentage, 2) . "%</span>";
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Attendance History -->
    <div class="card" style="width:90%; margin:20px auto;">
        <h3><i class="fa fa-calendar"></i> Attendance History</h3>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $attendance->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="download_report.php" class="logout" style="background:#28a745;">⬇ Download PDF Report</a>
    <a href="../dashboard.php" class="logout">⬅ Back</a>

</div>