<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
    $subject = trim($_POST['subject']);
    $internal1 = (int) $_POST['internal1'];
    $internal2 = (int) $_POST['internal2'];
    $assignment_mark = (int) $_POST['assignment_mark'];
    $model_exam = (int) $_POST['model_exam'];

    if ($student_id == 0) {
        $message = "Invalid student selected!";
    } else {
        $sql = "INSERT INTO internal_marks 
                (student_id, subject, internal1, internal2, assignment_mark, model_exam)
                VALUES 
                ('$student_id', '$subject', '$internal1', '$internal2', '$assignment_mark', '$model_exam')";

        if ($conn->query($sql)) {
            $message = "Internal marks added successfully!";

            // Notification for student
            $userData = $conn->query("SELECT id FROM users WHERE student_id='$student_id' AND role='student'");

            if ($userData && $userData->num_rows > 0) {
                $user = $userData->fetch_assoc();
                $user_id = $user['id'];

                $notifMsg = "Your internal marks for subject " . $subject . " have been updated.";

                $conn->query("INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$notifMsg')");
            }
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

$students = $conn->query("SELECT id, reg_no, name FROM students ORDER BY name ASC");
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📝 Add Internal Marks</h1>
    <p style="color:gray;">Enter internal assessment marks for students</p>

    <div class="card" style="max-width:800px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>Student</label><br>
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php while ($student = $students->fetch_assoc()) { ?>
                    <option value="<?php echo $student['id']; ?>">
                        <?php echo $student['reg_no'] . " - " . $student['name']; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <label>Subject</label><br>
            <input type="text" name="subject" required><br><br>

            <label>Internal 1</label><br>
            <input type="number" name="internal1" required><br><br>

            <label>Internal 2</label><br>
            <input type="number" name="internal2" required><br><br>

            <label>Assignment Mark</label><br>
            <input type="number" name="assignment_mark" required><br><br>

            <label>Model Exam</label><br>
            <input type="number" name="model_exam" required><br><br>

            <button type="submit">Save Internal Marks</button>
        </form>
    </div>
</div>