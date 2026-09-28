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

// Optional: prevent deleting own admin account
if (isset($_SESSION['username'])) {
    $currentUser = $_SESSION['username'];
    $currentData = $conn->query("SELECT id FROM users WHERE username='$currentUser'")->fetch_assoc();
    if ($currentData && $currentData['id'] == $id) {
        echo "You cannot delete your own logged-in account.";
        exit();
    }
}

$conn->query("DELETE FROM users WHERE id=$id");

header("Location: manage_users.php");
exit();
?>