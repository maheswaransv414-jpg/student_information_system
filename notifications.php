<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get logged in user id
$username = $_SESSION['username'];
$userResult = $conn->query("SELECT id FROM users WHERE username='$username'");
$userData = $userResult->fetch_assoc();
$user_id = $userData['id'];

// Mark all as read
$conn->query("UPDATE notifications SET is_read=1 WHERE user_id=$user_id");

// Fetch notifications
$result = $conn->query("SELECT * FROM notifications WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="assets/style.css">

<div class="main" style="margin-left:0; max-width:1000px; margin:auto; padding:30px;">
    <h1>🔔 Notifications</h1>
    <p style="color:gray;">Your latest updates</p>

    <div class="card">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['message']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <?php if ($row['is_read']) { ?>
                                    <span class="badge pass">Read</span>
                                <?php } else { ?>
                                    <span class="badge fail">Unread</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="3">No notifications found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php" class="logout">⬅ Back</a>
</div>