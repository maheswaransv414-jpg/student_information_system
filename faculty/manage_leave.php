<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    // Find student_id for this leave request
    $leaveData = $conn->query("SELECT student_id FROM leave_requests WHERE id=$id")->fetch_assoc();
    $student_id = $leaveData['student_id'];

    // Find corresponding user_id for student
    $userData = $conn->query("SELECT id FROM users WHERE student_id=$student_id AND role='student'")->fetch_assoc();
    $user_id = $userData['id'];

    if ($action == "approve") {
        $conn->query("UPDATE leave_requests SET status='Approved' WHERE id=$id");
        $notifMsg = "Your leave request has been Approved.";
        $conn->query("INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$notifMsg')");
    } elseif ($action == "reject") {
        $conn->query("UPDATE leave_requests SET status='Rejected' WHERE id=$id");
        $notifMsg = "Your leave request has been Rejected.";
        $conn->query("INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$notifMsg')");
    }

    header("Location: manage_leave.php");
    exit();
}


$sql = "
    SELECT lr.*, s.name, s.reg_no, s.course
    FROM leave_requests lr
    JOIN students s ON lr.student_id = s.id
    ORDER BY lr.applied_at DESC
";

$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📋 Manage Leave Requests</h1>
    <p style="color:gray;">Approve or reject student leave applications</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['reg_no']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['from_date']; ?></td>
                            <td><?php echo $row['to_date']; ?></td>
                            <td><?php echo $row['reason']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['applied_at']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'Pending') { ?>
                                    <a href="manage_leave.php?action=approve&id=<?php echo $row['id']; ?>" class="btn-view" style="background:green;">Approve</a>
                                    <a href="manage_leave.php?action=reject&id=<?php echo $row['id']; ?>" class="btn-view" style="background:red;">Reject</a>
                                <?php } else { ?>
                                    <span>-</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="9">No leave requests found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>