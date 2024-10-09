<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Hacked</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-color: #111;
            color: red;
            font-family: 'Arial', sans-serif;
            font-size: 24px;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
        .glitch {
            color: red;
            font-weight: bold;
            position: relative;
        }
        .glitch::before, .glitch::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            color: lime;
            background: black;
        }
        .glitch::before {
            left: 2px;
            text-shadow: -2px 0 red;
            animation: glitch 1s infinite;
        }
        .glitch::after {
            left: -2px;
            text-shadow: 2px 0 blue;
            animation: glitch 1.2s infinite;
        }

        .gif-container {
            margin-top: 20px;
        }

        @keyframes glitch {
            0% {
                clip: rect(44px, 9999px, 56px, 0);
            }
            10% {
                clip: rect(75px, 9999px, 10px, 0);
            }
            20% {
                clip: rect(25px, 9999px, 90px, 0);
            }
            30% {
                clip: rect(50px, 9999px, 30px, 0);
            }
            40% {
                clip: rect(85px, 9999px, 15px, 0);
            }
            50% {
                clip: rect(30px, 9999px, 50px, 0);
            }
            60% {
                clip: rect(15px, 9999px, 80px, 0);
            }
            70% {
                clip: rect(70px, 9999px, 20px, 0);
            }
            80% {
                clip: rect(60px, 9999px, 40px, 0);
            }
            90% {
                clip: rect(20px, 9999px, 70px, 0);
            }
            100% {
                clip: rect(90px, 9999px, 5px, 0);
            }
        }
    </style>
</head>
<body>
    <div class="message">
        <div class="glitch"> </div>
    </div>

    <div class="gif-container">
        <!-- Replace this URL with the actual GIF URL of the dog -->
        <img src="sospechoso-mirada-sospechosa.gif" alt="No, don't hack this!" />
    </div>
</body>
</html>
