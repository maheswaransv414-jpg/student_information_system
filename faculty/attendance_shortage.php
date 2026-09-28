<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}

$students = $conn->query("SELECT id, reg_no, name, course FROM students ORDER BY name ASC");
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>⚠ Attendance Shortage List</h1>
    <p style="color:gray;">Students below 75% attendance</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Present</th>
                    <th>Total Classes</th>
                    <th>Attendance %</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $found = false;

                while ($student = $students->fetch_assoc()) {
                    $student_id = $student['id'];

                    $presentRow = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$student_id AND status='Present'")->fetch_assoc();
                    $totalRow = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$student_id")->fetch_assoc();

                    $present = $presentRow['c'] ?? 0;
                    $total = $totalRow['c'] ?? 0;
                    $percentage = ($total > 0) ? ($present / $total) * 100 : 0;

                    if ($percentage < 75) {
                        $found = true;
                ?>
                        <tr>
                            <td><?php echo $student['reg_no']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['course']; ?></td>
                            <td><?php echo $present; ?></td>
                            <td><?php echo $total; ?></td>
                            <td><?php echo round($percentage, 2); ?>%</td>
                            <td><span class="badge fail">Shortage</span></td>
                        </tr>
                <?php
                    }
                }

                if (!$found) {
                    echo "<tr><td colspan='7'>No students with attendance shortage.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>