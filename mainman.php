<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php");
    exit();
}
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 15px;
            font-size: 26px;
        }

        p {
            margin-bottom: 30px;
            font-size: 16px;
            color: #4b5563;
        }

        a.button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        a.button:hover {
            background: #1e40af;
        }

        .logout {
            margin-top: 20px;
            display: inline-block;
            font-size: 14px;
            color: #dc2626;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1>Welcome, <?= $username ?>!</h1>
        <p>Select where you'd like to go:</p>
        <a href="employee.php" class="button"><i class="fas fa-chart-line"></i> Go to Dashboard</a>
        <br>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</body>
</html>
