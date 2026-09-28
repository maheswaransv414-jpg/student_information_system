<?php
session_start();
include("config/db.php");

// 🔒 Session check
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// ================== BASIC DATA ==================

// Total students
$count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc();
$totalStudents = $count['total'];

// Logged user
$username = $_SESSION['username'];
$userResult = $conn->query("SELECT id FROM users WHERE username='$username'");
$userData = $userResult->fetch_assoc();
$user_id = $userData['id'];

// Notifications
$notifCountRow = $conn->query("SELECT COUNT(*) AS total FROM notifications WHERE user_id=$user_id AND is_read=0")->fetch_assoc();
$unreadCount = $notifCountRow['total'];

// ================== ANALYTICS ==================

// 📊 Students per course
$courseLabels = [];
$courseData = [];

$res = $conn->query("SELECT course, COUNT(*) as total FROM students GROUP BY course");

while ($row = $res->fetch_assoc()) {
    $courseLabels[] = $row['course'];
    $courseData[] = $row['total'];
}

// 📈 Pass vs Fail
$pass = 0;
$fail = 0;

$res2 = $conn->query("
    SELECT s.id, AVG(m.marks) as avg_marks
    FROM students s
    LEFT JOIN marks m ON s.id = m.student_id
    GROUP BY s.id
");

while ($row = $res2->fetch_assoc()) {
    if ($row['avg_marks'] >= 50) {
        $pass++;
    } else {
        $fail++;
    }
}

// 📉 Overall average marks
$avgRes = $conn->query("SELECT AVG(marks) as avg FROM marks")->fetch_assoc();
$overallAvg = round($avgRes['avg'], 2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SIS</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="sidebar">
    <h2>SIS</h2>

    <?php if ($_SESSION['role'] == "admin") { ?>
        <a href="admin/add_student.php">➕ Add Student</a>
        <a href="admin/view_students.php">👥 View Students</a>
    <?php } ?>

    <?php if ($_SESSION['role'] == "faculty") { ?>
        <a href="faculty/add_marks.php">✏️ Add Marks</a>
        <a href="faculty/mark_attendance.php">📅 Attendance</a>
    <?php } ?>

    <?php if ($_SESSION['role'] == "student") { ?>
        <a href="student/view_details.php">👤 My Details</a>
    <?php } ?>

    <a class="logout" href="logout.php">🚪 Logout</a>
</div>

<div class="main">

    <h1>Welcome, <?php echo $_SESSION['role']; ?> 👋</h1>
    <p style="color:gray;">Student Information System Dashboard</p>

    <!-- CARDS -->
    <div class="card">
        <h3><?php echo $totalStudents; ?></h3>
        <p>Total Students</p>
    </div>

    <div class="card">
        <h3><?php echo $unreadCount; ?></h3>
        <p>Unread Notifications</p>
        <a href="notifications.php">View</a>
    </div>

    <div class="card">
        <h3><?php echo $overallAvg; ?></h3>
        <p>Overall Average Marks</p>
    </div>

    <br><br>

    <!-- 📊 Students per Course -->
    <div class="card" style="width:80%; margin:auto;">
        <h3>Students per Course</h3>
        <canvas id="courseChart"></canvas>
    </div>

    <br>

    <!-- 📈 Pass vs Fail -->
    <div class="card" style="width:80%; margin:auto;">
        <h3>Pass vs Fail</h3>
        <canvas id="resultChart"></canvas>
    </div>

</div>

<script>
// 📊 Students per Course
new Chart(document.getElementById('courseChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($courseLabels); ?>,
        datasets: [{
            label: 'Students',
            data: <?php echo json_encode($courseData); ?>,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// 📈 Pass vs Fail
new Chart(document.getElementById('resultChart'), {
    type: 'pie',
    data: {
        labels: ['Pass', 'Fail'],
        datasets: [{
            data: [<?php echo $pass; ?>, <?php echo $fail; ?>]
        }]
    }
});
</script>

</body>
</html>
