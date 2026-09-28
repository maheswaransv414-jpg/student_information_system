<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_faculty.php");
    exit();
}

$id = (int) $_GET['id'];
$message = "";

$result = $conn->query("SELECT * FROM faculty WHERE id=$id");
if (!$result || $result->num_rows == 0) {
    echo "Faculty not found";
    exit();
}
$faculty = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $phone = trim($_POST['phone']);

    $sql = "UPDATE faculty 
            SET name='$name', email='$email', subject='$subject', phone='$phone'
            WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "Faculty updated successfully!";
        $result = $conn->query("SELECT * FROM faculty WHERE id=$id");
        $faculty = $result->fetch_assoc();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>✏ Edit Faculty</h1>

    <div class="card" style="max-width:800px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>Name</label><br>
            <input type="text" name="name" value="<?php echo $faculty['name']; ?>" required><br><br>

            <label>Email</label><br>
            <input type="email" name="email" value="<?php echo $faculty['email']; ?>" required><br><br>

            <label>Subject</label><br>
            <input type="text" name="subject" value="<?php echo $faculty['subject']; ?>" required><br><br>

            <label>Phone</label><br>
            <input type="text" name="phone" value="<?php echo $faculty['phone']; ?>" required><br><br>

            <button type="submit">Update Faculty</button>
        </form>
    </div>
</div>