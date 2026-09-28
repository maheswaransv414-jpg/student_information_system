<?php
session_start();
include("../config/db.php");

// Only faculty access
if ($_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
}

if ($_POST) {
    $id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    $sql = "INSERT INTO marks (student_id, subject, marks)
            VALUES ('$id', '$subject', '$marks')";

    if ($conn->query($sql)) {
        echo "<script>alert('Marks Added Successfully!');</script>";
    }
    $userData = $conn->query("SELECT id FROM users WHERE student_id=$id AND role='student'")->fetch_assoc();
if ($userData) {
    $user_id = $userData['id'];
    $notifMsg = "Your marks for subject " . $subject . " have been updated.";
    $conn->query("INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$notifMsg')");
}
}
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include("../includes/sidebar.php"); ?>
<div class="main">

    <h1><i class="fa fa-pen"></i> Add Marks</h1>

    <div class="card" style="width:400px; margin:auto;">
        <form method="POST">

            <label>Student ID</label><br>
            <input type="number" name="student_id" placeholder="Enter student ID" required><br><br>

            <label>Subject</label><br>
            <input type="text" name="subject" placeholder="Enter subject" required><br><br>

            <label>Marks</label><br>
            <input type="number" name="marks" placeholder="Enter marks" required><br><br>

            <button type="submit">
                <i class="fa fa-plus"></i> Add Marks
            </button>

        </form>
    </div>

    <br>
    <a href="../dashboard.php" class="logout">⬅ Back</a>

</div>