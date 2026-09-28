<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM timetable ORDER BY 
    FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
    time_slot ASC");
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>📅 View Timetable</h1>
    <p style="color:gray;">Manage all timetable entries</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course</th>
                    <th>Day</th>
                    <th>Subject</th>
                    <th>Faculty</th>
                    <th>Time Slot</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['day']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo $row['staff_name']; ?></td>
                            <td><?php echo $row['time_slot']; ?></td>
                            <td>
                                <a href="edit_timetable.php?id=<?php echo $row['id']; ?>" class="btn-view">Edit</a>
                                <a href="delete_timetable.php?id=<?php echo $row['id']; ?>" class="btn-view" style="background:red;" onclick="return confirm('Delete this timetable entry?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">No timetable entries found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>