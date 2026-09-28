<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$sql = "
    SELECT 
        u.id,
        u.username,
        u.role,
        u.student_id,
        u.faculty_id,
        s.name AS student_name,
        f.name AS faculty_name
    FROM users u
    LEFT JOIN students s ON u.student_id = s.id
    LEFT JOIN faculty f ON u.faculty_id = f.id
    ORDER BY u.id DESC
";

$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>👥 Manage Users</h1>
    <p style="color:gray;">Admin can manage all login accounts</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Linked Name</th>
                    <th>Student ID</th>
                    <th>Faculty ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo ucfirst($row['role']); ?></td>
                            <td>
                                <?php
                                if ($row['role'] == 'student') {
                                    echo $row['student_name'] ?? '-';
                                } elseif ($row['role'] == 'faculty') {
                                    echo $row['faculty_name'] ?? '-';
                                } else {
                                    echo 'Admin';
                                }
                                ?>
                            </td>
                            <td><?php echo $row['student_id'] ?? '-'; ?></td>
                            <td><?php echo $row['faculty_id'] ?? '-'; ?></td>
                            <td>
                                <a href="reset_password.php?id=<?php echo $row['id']; ?>" class="btn-view">Reset Password</a>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn-view" style="background:red;" onclick="return confirm('Delete this user account?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">No users found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>