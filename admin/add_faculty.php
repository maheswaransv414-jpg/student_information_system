<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $facultySql = "INSERT INTO faculty (name, email, subject, phone)
                   VALUES ('$name', '$email', '$subject', '$phone')";

    if ($conn->query($facultySql)) {
        $faculty_id = $conn->insert_id;

        // If you are using hashed passwords
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userSql = "INSERT INTO users (username, password, role, faculty_id)
                    VALUES ('$username', '$hashedPassword', 'faculty', '$faculty_id')";

        if ($conn->query($userSql)) {
            $message = "Faculty added and login created successfully!";
        } else {
            $message = "Faculty added, but login creation failed: " . $conn->error;
        }
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>👩‍🏫 Add Faculty</h1>
    <p style="color:gray;">Create faculty profile and login account</p>

    <div class="card" style="max-width:800px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label>Name</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email</label><br>
            <input type="email" name="email" required><br><br>

            <label>Subject</label><br>
            <input type="text" name="subject" required><br><br>

            <label>Phone</label><br>
            <input type="text" name="phone" required><br><br>

            <label>Username</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password</label><br>
            <input type="text" name="password" required><br><br>

            <button type="submit">Add Faculty</button>
        </form>
    </div>
</div>
