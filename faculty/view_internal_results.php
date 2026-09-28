<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "faculty") {
    header("Location: ../login.php");
    exit();
}

$sql = "
    SELECT 
        s.id,
        s.reg_no,
        s.name,
        s.course,
        im.subject,
        im.internal1,
        im.internal2,
        im.assignment_mark,
        im.model_exam
    FROM internal_marks im
    JOIN students s ON im.student_id = s.id
    ORDER BY s.name ASC
";

$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📊 Internal Assessment Results</h1>
    <p style="color:gray;">Faculty can view internal assessment performance</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Subject</th>
                    <th>Internal 1</th>
                    <th>Internal 2</th>
                    <th>Assignment</th>
                    <th>Model Exam</th>
                    <th>Total</th>
                    <th>Average</th>
                    <th>Result</th>
                    <th>Division</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { 
                        $total = $row['internal1'] + $row['internal2'] + $row['assignment_mark'] + $row['model_exam'];
                        $average = $total / 4;

                        if ($average >= 50) $resultStatus = "Pass";
                        else $resultStatus = "Fail";

                        if ($average >= 75) $division = "Distinction";
                        elseif ($average >= 60) $division = "First Class";
                        elseif ($average >= 50) $division = "Second Class";
                        else $division = "Fail";
                    ?>
                        <tr>
                            <td><?php echo $row['reg_no']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo $row['internal1']; ?></td>
                            <td><?php echo $row['internal2']; ?></td>
                            <td><?php echo $row['assignment_mark']; ?></td>
                            <td><?php echo $row['model_exam']; ?></td>
                            <td><?php echo $total; ?></td>
                            <td><?php echo round($average, 2); ?></td>
                            <td><?php echo $resultStatus; ?></td>
                            <td><?php echo $division; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="12">No internal assessment records found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>