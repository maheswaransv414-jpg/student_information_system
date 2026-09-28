<?php
session_start();
include("../config/db.php");
require_once(__DIR__ . "/../fpdf/fpdf.php");

// Allow student, faculty, and admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ["student", "faculty", "admin"])) {
    header("Location: ../login.php");
    exit();
}

// If faculty/admin passes student_id in URL, use it
if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
    $student_id = (int) $_GET['student_id'];
} else {
    // Otherwise student downloads own report
    if (!isset($_SESSION['student_id']) || empty($_SESSION['student_id'])) {
        die("Student ID not found!");
    }
    $student_id = (int) $_SESSION['student_id'];
}

// Student details
$result = $conn->query("SELECT * FROM students WHERE id=$student_id");
if (!$result || $result->num_rows == 0) {
    die("Student not found!");
}
$student = $result->fetch_assoc();

// Marks
$marks = $conn->query("SELECT * FROM marks WHERE student_id=$student_id");

// Attendance summary
$presentCount = $conn->query("SELECT COUNT(*) AS total FROM attendance WHERE student_id=$student_id AND status='Present'")->fetch_assoc();
$absentCount = $conn->query("SELECT COUNT(*) AS total FROM attendance WHERE student_id=$student_id AND status='Absent'")->fetch_assoc();

$present = $presentCount['total'] ?? 0;
$absent = $absentCount['total'] ?? 0;
$totalClasses = $present + $absent;
$attendancePercentage = ($totalClasses > 0) ? ($present / $totalClasses) * 100 : 0;

// Marks summary
$totalMarksRow = $conn->query("SELECT SUM(marks) AS total FROM marks WHERE student_id=$student_id")->fetch_assoc();
$avgMarksRow   = $conn->query("SELECT AVG(marks) AS avg_marks FROM marks WHERE student_id=$student_id")->fetch_assoc();
$maxMarksRow   = $conn->query("SELECT MAX(marks) AS highest FROM marks WHERE student_id=$student_id")->fetch_assoc();
$minMarksRow   = $conn->query("SELECT MIN(marks) AS lowest FROM marks WHERE student_id=$student_id")->fetch_assoc();

$totalMarks   = $totalMarksRow['total'] ?? 0;
$averageMarks = $avgMarksRow['avg_marks'] ?? 0;
$highestMarks = $maxMarksRow['highest'] ?? 0;
$lowestMarks  = $minMarksRow['lowest'] ?? 0;

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Student Report', 0, 1, 'C');
$pdf->Ln(5);

// Personal details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Personal Details', 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(95, 8, 'Register Number: ' . ($student['reg_no'] ?? ''), 0, 0);
$pdf->Cell(95, 8, 'Name: ' . ($student['name'] ?? ''), 0, 1);

$pdf->Cell(95, 8, 'Email: ' . ($student['email'] ?? ''), 0, 0);
$pdf->Cell(95, 8, 'Course: ' . ($student['course'] ?? ''), 0, 1);

$pdf->Cell(95, 8, 'Date of Birth: ' . ($student['dob'] ?? ''), 0, 0);
$pdf->Cell(95, 8, 'Gender: ' . ($student['gender'] ?? ''), 0, 1);

$pdf->Cell(95, 8, 'Phone: ' . ($student['phone'] ?? ''), 0, 1);
$pdf->MultiCell(190, 8, 'Address: ' . ($student['address'] ?? ''));
$pdf->Ln(5);

// Marks summary
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Marks Summary', 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(95, 8, 'Total Marks: ' . $totalMarks, 0, 0);
$pdf->Cell(95, 8, 'Average Marks: ' . round((float)$averageMarks, 2), 0, 1);

$pdf->Cell(95, 8, 'Highest Mark: ' . $highestMarks, 0, 0);
$pdf->Cell(95, 8, 'Lowest Mark: ' . $lowestMarks, 0, 1);

$pdf->Ln(5);

// Subject marks table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Subject Marks', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(95, 8, 'Subject', 1, 0, 'C');
$pdf->Cell(95, 8, 'Marks', 1, 1, 'C');

$pdf->SetFont('Arial', '', 11);
if ($marks && $marks->num_rows > 0) {
    while ($row = $marks->fetch_assoc()) {
        $pdf->Cell(95, 8, $row['subject'], 1, 0, 'C');
        $pdf->Cell(95, 8, $row['marks'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 8, 'No marks available', 1, 1, 'C');
}

$pdf->Ln(5);

// Attendance summary
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'Attendance Summary', 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(95, 8, 'Present: ' . $present, 0, 0);
$pdf->Cell(95, 8, 'Absent: ' . $absent, 0, 1);

$pdf->Cell(95, 8, 'Total Classes: ' . $totalClasses, 0, 0);
$pdf->Cell(95, 8, 'Attendance %: ' . round($attendancePercentage, 2) . '%', 0, 1);

// Output
$pdf->Output('D', 'Student_Report_' . $student_id . '.pdf');
exit();
?>