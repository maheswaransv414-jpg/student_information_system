<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = (int) $_GET['id'];
$message = "";

// Load user
$result = $conn->query("SELECT * FROM users WHERE id=$id");
if (!$result || $result->num_rows == 0) {
    echo "User not found";
    exit();
}
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);

    // Hashed password
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password='$hashedPassword' WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "Password reset successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<?php include("../includes/sidebar.php"); ?>

<div class="main">
    <h1>🔑 Reset Password</h1>

    <div class="card" style="max-width:700px;">
        <?php if (!empty($message)) { ?>
            <div style="margin-bottom:15px; padding:10px; background:#eef; border-radius:8px;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <p><b>Username:</b> <?php echo $user['username']; ?></p>
        <p><b>Role:</b> <?php echo ucfirst($user['role']); ?></p>

        <form method="POST">
            <label>New Password</label><br>
            <input type="text" name="new_password" required><br><br>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</div>