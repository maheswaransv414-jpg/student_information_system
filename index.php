<!DOCTYPE html>
<html>
<head>
    <title>Student Information System</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        height: 100vh;

        display: flex;
        justify-content: center;
        align-items: center;

        background: linear-gradient(to right, #007bff, #00c6ff);
    }

    .hero {
        background: white;
        color: black;
        padding: 40px;
        border-radius: 12px;
        width: 450px;
        text-align: center;
        box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
    }

    h1 {
        font-size: 28px;
        margin-bottom: 15px;
    }

    p {
        font-size: 15px;
        margin: 10px 0;
        color: #555;
    }

    .btn {
        background: #007bff;
        color: white;
        padding: 12px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        margin-top: 20px;
        transition: 0.3s;
    }

    .btn:hover {
        background: #0056b3;
    }
</style>
</head>

<body>

<div class="hero">
    <h1>🎓 Student Information System</h1>

    <p>
        A modern web-based system to manage student data, attendance, and academic performance efficiently.
    </p>

    <p>
        Built using PHP, MySQL, and modern UI design.
    </p>

    <a href="login.php" class="btn">Login to System</a>
</div>

</body>
</html>