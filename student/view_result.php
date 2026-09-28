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

$result = $conn->query("SELECT * FROM internal_marks WHERE student_id=$student_id");

$totalOverall = 0;
$countSubjects = 0;
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📘 Internal Assessment Result</h1>
    <p style="color:gray;">View your internal assessment performance</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
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

                        if ($average >= 50) {
                            $resultStatus = "Pass";
                        } else {
                            $resultStatus = "Fail";
                        }

                        if ($average >= 75) {
                            $division = "Distinction";
                        } elseif ($average >= 60) {
                            $division = "First Class";
                        } elseif ($average >= 50) {
                            $division = "Second Class";
                        } else {
                            $division = "Fail";
                        }

                        $totalOverall += $total;
                        $countSubjects++;
                    ?>
                        <tr>
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
                        <td colspan="9">No internal marks found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php
    $overallAverage = ($countSubjects > 0) ? ($totalOverall / ($countSubjects * 4)) : 0;

    if ($overallAverage >= 75) $overallDivision = "Distinction";
    elseif ($overallAverage >= 60) $overallDivision = "First Class";
    elseif ($overallAverage >= 50) $overallDivision = "Second Class";
    else $overallDivision = "Fail";
    ?>

    <div class="card" style="max-width:700px;">
        <h3>Overall Result Summary</h3>
        <table class="styled-table">
            <tbody>
                <tr>
                    <th>Total Overall Marks</th>
                    <td><?php echo $totalOverall; ?></td>
                </tr>
                <tr>
                    <th>Overall Average</th>
                    <td><?php echo round($overallAverage, 2); ?></td>
                </tr>
                <tr>
                    <th>Overall Division</th>
                    <td><?php echo $overallDivision; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>