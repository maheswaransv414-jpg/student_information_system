<?php
session_start();
include("../config/db.php");
$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM students 
        WHERE name LIKE '%$search%' OR email LIKE '%$search%'");
} else {
    $result = $conn->query("SELECT * FROM students");
}

// Only admin access
if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
}

$result = $conn->query("SELECT * FROM students");
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php include("../includes/sidebar.php"); ?>
<div class="main">

    <h1><i class="fa fa-users"></i> Student List</h1>

    <div class="card" style="width:90%; margin:auto;">
<form method="GET" style="text-align:center; margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search by name or email" 
           value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                    </tr>
                    <td>
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure?')">
                           🗑 Delete
                        </a>
                    </td>
                <?php } ?>
            </tbody>
        </table>

    </div>

    <br>
    <a href="../dashboard.php" class="logout">⬅ Back</a>

</div>