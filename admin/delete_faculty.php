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

// Delete faculty login first
$conn->query("DELETE FROM users WHERE faculty_id=$id AND role='faculty'");

// Delete faculty record
$conn->query("DELETE FROM faculty WHERE id=$id");

header("Location: view_faculty.php");
exit();
?>