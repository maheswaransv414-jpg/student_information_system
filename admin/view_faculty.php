<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM faculty ORDER BY id DESC");
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>👨‍🏫 View Faculty</h1>
    <p style="color:gray;">Manage faculty information</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
                            <td><?php echo isset($row['phone']) ? $row['phone'] : '-'; ?></td>
                            <td>
                                <a href="edit_faculty.php?id=<?php echo $row['id']; ?>" class="btn-view">Edit</a>
                                <a href="delete_faculty.php?id=<?php echo $row['id']; ?>" class="btn-view" style="background:red;" onclick="return confirm('Delete this faculty?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6">No faculty found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>