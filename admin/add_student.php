<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if ($_POST) {
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $username = $_POST['username'];
    $password = $_POST['password'];

    // 🔐 Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into students table
    $sql = "INSERT INTO students (reg_no, name, email, course, dob, gender, phone, address)
            VALUES ('$reg_no', '$name', '$email', '$course', '$dob', '$gender', '$phone', '$address')";

    if ($conn->query($sql)) {

        // Get last inserted student ID
        $student_id = $conn->insert_id;

        // Insert into users table
        $userSql = "INSERT INTO users (username, password, role, student_id)
                    VALUES ('$username', '$hashedPassword', 'student', '$student_id')";

        if ($conn->query($userSql)) {
            echo "<script>alert('Student and login created successfully!');</script>";
        } else {
            echo "<script>alert('Login creation failed: " . $conn->error . "');</script>";
        }

    } else {
        echo "<script>alert('Error adding student: " . $conn->error . "');</script>";
    }
}
?>

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php include("../includes/sidebar.php"); ?>

<div class="main">

    <h1><i class="fa fa-user-plus"></i> Add Student</h1>

    <div class="card" style="width:500px; margin:auto;">

        <form method="POST">

            <label>Register Number</label><br>
            <input type="text" name="reg_no" required><br><br>

            <label>Name</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email</label><br>
            <input type="email" name="email" required><br><br>

            <label>Course</label><br>
            <input type="text" name="course" required><br><br>

            <label>Date of Birth</label><br>
            <input type="date" name="dob" required><br><br>

            <label>Gender</label><br>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select><br><br>

            <label>Phone</label><br>
            <input type="text" name="phone" required><br><br>

            <label>Address</label><br>
            <textarea name="address" rows="4" style="width:95%; padding:10px;" required></textarea><br><br>

            <label>Username</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">
                <i class="fa fa-plus"></i> Add Student
            </button>

        </form>

    </div>

</div>