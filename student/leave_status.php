<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "student") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['student_id']) || empty($_SESSION['student_id'])) {
    echo "Student ID not found!";
    exit();
}

$student_id = (int) $_SESSION['student_id'];

$sql = "SELECT * FROM leave_requests WHERE student_id=$student_id ORDER BY applied_at DESC";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📄 My Leave Status</h1>
    <p style="color:gray;">Track your leave requests</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Applied At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['from_date']; ?></td>
                            <td><?php echo $row['to_date']; ?></td>
                            <td><?php echo $row['reason']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['applied_at']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">No leave requests found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>