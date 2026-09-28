<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_timetable.php");
    exit();
}

$id = (int) $_GET['id'];

$conn->query("DELETE FROM timetable WHERE id=$id");

header("Location: view_timetable.php");
exit();
?>