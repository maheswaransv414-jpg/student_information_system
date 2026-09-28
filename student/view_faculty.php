<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != "student") {
    header("Location: ../login.php");
}

$faculty = $conn->query("SELECT * FROM faculty");
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>👨‍🏫 Teaching Staff</h1>

    <div class="card" style="width:90%; margin:auto;">
        <table class="styled-table">
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Email</th>
            </tr>

            <?php while ($row = $faculty->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['subject']; ?></td>
                <td><?php echo $row['email']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>