<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No student selected";
    exit();
}

$student_id = (int) $_GET['id'];

$result = $conn->query("SELECT * FROM students WHERE id=$student_id");
if (!$result || $result->num_rows == 0) {
    echo "Student not found";
    exit();
}
$student = $result->fetch_assoc();

$marks = $conn->query("SELECT * FROM marks WHERE student_id=$student_id");
$attendance = $conn->query("SELECT * FROM attendance WHERE student_id=$student_id ORDER BY date DESC");

$totalMarksRow = $conn->query("SELECT SUM(marks) as total FROM marks WHERE student_id=$student_id")->fetch_assoc();
$avgMarksRow = $conn->query("SELECT AVG(marks) as avg FROM marks WHERE student_id=$student_id")->fetch_assoc();

$totalMarks = $totalMarksRow['total'] ?? 0;
$avgMarks = $avgMarksRow['avg'] ?? 0;

if ($avgMarks >= 75) $division = "Distinction";
elseif ($avgMarks >= 60) $division = "First Class";
elseif ($avgMarks >= 50) $division = "Second Class";
else $division = "Fail";

$presentRow = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$student_id AND status='Present'")->fetch_assoc();
$totalRow = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$student_id")->fetch_assoc();

$present = $presentRow['c'] ?? 0;
$total = $totalRow['c'] ?? 0;
$attendancePercent = ($total > 0) ? ($present / $total) * 100 : 0;
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>👤 Student Profile</h1>
    <p style="color:gray;">Detailed academic and personal information</p>

    <!-- Full-width personal details -->
    <div class="card full-card">
        <h3>Personal Details</h3>
        <table class="styled-table profile-table">
            <tbody>
                <tr>
                    <th>Register Number</th>
                    <td><?php echo $student['reg_no']; ?></td>
                    <th>Name</th>
                    <td><?php echo $student['name']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $student['email']; ?></td>
                    <th>Course</th>
                    <td><?php echo $student['course']; ?></td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td><?php echo $student['dob']; ?></td>
                    <th>Gender</th>
                    <td><?php echo $student['gender']; ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $student['phone']; ?></td>
                    <th>Address</th>
                    <td><?php echo $student['address']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 3 equal cards -->
    <div class="profile-grid">

        <div class="card mini-card">
            <h3>Marks Summary</h3>
            <table class="styled-table profile-table">
                <tbody>
                    <tr><th>Total Marks</th><td><?php echo $totalMarks; ?></td></tr>
                    <tr><th>Average Marks</th><td><?php echo round($avgMarks, 2); ?></td></tr>
                    <tr><th>Division</th><td><?php echo $division; ?></td></tr>
                </tbody>
            </table>
        </div>

        <div class="card mini-card">
            <h3>Subject Marks</h3>
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

        <div class="card mini-card">
            <h3>Attendance Summary</h3>
            <table class="styled-table profile-table">
                <tbody>
                    <tr><th>Present</th><td><?php echo $present; ?></td></tr>
                    <tr><th>Total Classes</th><td><?php echo $total; ?></td></tr>
                    <tr><th>Attendance %</th><td><?php echo round($attendancePercent, 2); ?>%</td></tr>
                </tbody>
            </table>
        </div>

    </div>
<a href="../student/download_report.php?student_id=<?php echo $student_id; ?>" class="btn">
    Download Report
</a>
    <a href="student_results.php" class="logout">⬅ Back</a>
</div>