<?php
// Save this file as index.php
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Work Pause</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            color: white;
            text-align: center;
            background: url('zam.avif') no-repeat center center fixed;
            background-size: cover;
            overflow: hidden;
            position: relative;
        }

        .splash-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
            position: relative;
            z-index: 2;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5); /* Overlay for text readability */
            border-radius: 20px;
        }

        .splash-container h1 {
            font-size: 4em;
            margin-bottom: 20px;
            animation: fadeSlideIn 2s ease-out;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .splash-container p {
            font-size: 1.4em;
            margin-bottom: 35px;
            animation: fadeSlideUp 2.5s ease-out;
            max-width: 700px;
            line-height: 1.7;
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .cta-button {
            background: linear-gradient(to right, black,grey);
            color: #ffffff;
            padding: 15px 45px;
            font-size: 1.3em;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            animation: scaleIn 2.5s ease;
        }

        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
            z-index: 1;
        }

        .circle.one {
            width: 160px;
            height: 160px;
            top: 10%;
            left: 15%;
            animation-delay: 0s;
        }

        .circle.two {
            width: 220px;
            height: 220px;
            bottom: 10%;
            right: 20%;
            animation-delay: 2s;
        }

        .circle.three {
            width: 120px;
            height: 120px;
            bottom: 25%;
            left: 10%;
            animation-delay: 1s;
        }

        @keyframes fadeSlideIn {
            0% {
                opacity: 0;
                transform: translateY(-40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            0% {
                opacity: 0;
                transform: scale(0.85);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

    </style>
</head>
<body>
    <div class="splash-container">
        <h1><i>Welcome to Work Pause</i></h1>
        <p>
            <i>
            <?php
            echo "Your journey to better work-life balance starts here.";
            echo "<br>";
            echo "Today is " . date("l, F j, Y");
            ?>
            </i>
        </p>
        <a class="cta-button" href="login.php"><i>Enter</i></a>
        

    </div>
    <div class="circle one"></div>
    <div class="circle two"></div>
    <div class="circle three"></div>
</body>
</html>
