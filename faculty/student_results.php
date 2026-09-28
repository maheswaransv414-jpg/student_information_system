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
        s.reg_no,
        s.email,
        COALESCE(AVG(m.marks), 0) AS avg_marks,
        COALESCE(SUM(m.marks), 0) AS total_marks
    FROM students s
    LEFT JOIN marks m ON s.id = m.student_id
    GROUP BY s.id
";

$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">

    <h1>📊 Student Academic Report</h1>
    <p style="color:gray;">Student performance overview</p>

    <div class="card">

        <table class="styled-table">

            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Email</th>
                    <th>Total Marks</th>
                    <th>Average</th>
                    <th>Division</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($row = $result->fetch_assoc()) { 

                $avg = $row['avg_marks'];

                if ($avg >= 75) $division = "Distinction";
                elseif ($avg >= 60) $division = "First Class";
                elseif ($avg >= 50) $division = "Second Class";
                else $division = "Fail";
            ?>

                <tr>
                    <td><?php echo $row['reg_no']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['course']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['total_marks']; ?></td>
                    <td><?php echo round($avg, 2); ?></td>
                    <td><?php echo $division; ?></td>
                    <td>
                        <a href="student_profile.php?id=<?php echo $row['id']; ?>">View</a>
                    </td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>