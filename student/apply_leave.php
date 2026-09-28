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
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $reason = trim($_POST['reason']);

    if ($from_date > $to_date) {
        $message = "From date cannot be greater than To date.";
    } else {
        $sql = "INSERT INTO leave_requests (student_id, from_date, to_date, reason, status)
                VALUES ('$student_id', '$from_date', '$to_date', '$reason', 'Pending')";

        if ($conn->query($sql)) {
            $message = "Leave request submitted successfully!";

            // Notify faculty users
            $facultyUsers = $conn->query("SELECT id FROM users WHERE role='faculty'");
            while ($faculty = $facultyUsers->fetch_assoc()) {
                $faculty_id = $faculty['id'];
                $notifMsg = "New leave request submitted by student ID: " . $student_id;
                $conn->query("INSERT INTO notifications (user_id, message) VALUES ('$faculty_id', '$notifMsg')");
            }
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📝 Apply Leave</h1>
    <p style="color:gray;">Submit your leave request</p>

    <div class="card" style="max-width:700px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>From Date</label><br>
            <input type="date" name="from_date" required><br><br>

            <label>To Date</label><br>
            <input type="date" name="to_date" required><br><br>

            <label>Reason</label><br>
            <textarea name="reason" rows="5" required></textarea><br><br>

            <button type="submit">Submit Leave Request</button>
        </form>
    </div>
</div>