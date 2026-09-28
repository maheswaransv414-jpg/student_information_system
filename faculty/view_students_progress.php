<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}

$sql = "
    SELECT 
        s.id,
        s.name,
        s.course,
        COALESCE(SUM(m.marks), 0) AS total_marks,
        COALESCE(SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END), 0) AS present_count,
        COALESCE(SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END), 0) AS absent_count
    FROM students s
    LEFT JOIN marks m ON s.id = m.student_id
    LEFT JOIN attendance a ON s.id = a.student_id
    GROUP BY s.id, s.name, s.course
    ORDER BY s.id ASC
";

$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1><i class="fa fa-chart-line"></i> Student Progress</h1>
    <p style="color:gray;">Faculty can monitor student marks and attendance</p>

    <div class="card" style="width:95%; margin:auto;">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Total Marks</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                        <td><?php echo $row['total_marks']; ?></td>
                        <td><?php echo $row['present_count']; ?></td>
                        <td><?php echo $row['absent_count']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>