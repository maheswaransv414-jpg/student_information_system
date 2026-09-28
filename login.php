<?php
session_start();
include("config/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Run query ONLY after POST
    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // Plain password check
        if ($pass === $row['password']) {

            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['student_id'] = $row['student_id'];

            // 🔥 Redirect based on role (IMPORTANT)
           header("Location: /sis_project/dashboard.php");
exit();
           
            exit();

        } else {
            $error = "Invalid username or password";
        }

    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SIS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #007bff, #00c6ff);
            overflow: hidden;
        }

        .login-box {
            background: white;
            padding: 35px;
            border-radius: 15px;
            width: 380px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 42px;
            color: #007bff;
            margin-bottom: 10px;
            animation: bounce 1.5s infinite alternate;
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-5px);
            }
        }

        h2 {
            margin-bottom: 20px;
            color: #222;
        }

        .input-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 6px rgba(0,123,255,0.3);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 42px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
            transform: scale(1.02);
        }

        .error-box {
            background: #ffe5e5;
            color: #d8000c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            border-left: 5px solid #d8000c;
            text-align: left;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            50% { transform: translateX(4px); }
            75% { transform: translateX(-4px); }
            100% { transform: translateX(0); }
        }

        .footer {
            margin-top: 15px;
            font-size: 12px;
            color: gray;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="logo">
        <i class="fa fa-graduation-cap"></i>
    </div>

    <h2>Student Information System</h2>

    <?php if (!empty($error)) { ?>
        <div class="error-box">
            <i class="fa fa-circle-exclamation"></i> <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required>
                <i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
            </div>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="footer">
        © 2026 SIS Project
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>