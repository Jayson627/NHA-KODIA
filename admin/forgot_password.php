<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/color4.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 700px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .container .left-section {
            background-color: #d32f2f;
            padding: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 35%;
        }

        .container .left-section img {
            max-width: 80%;
            height: auto;
        }

        .container .right-section {
            padding: 60px 40px;
            width: 65%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            width: 100%;
            color: #333;
            text-align: left;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #d32f2f;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #b71c1c;
        }

        .container p {
            margin-top: 15px;
            color: #666;
            text-align: center;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .container {
                width: 700px; /* Fixed width for tablets */
            }

            .container .left-section {
                padding: 20px;
            }

            .container .right-section {
                padding: 40px 20px;
            }

            h2 {
                font-size: 24px;
            }

            input[type="email"], button {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 500px; /* Fixed width for mobile */
            }

            .container .right-section {
                padding: 30px 15px;
            }

            h2 {
                font-size: 22px;
            }

            input[type="email"], button {
                font-size: 14px;
                padding: 10px;
            }

            button {
                padding: 10px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="images/logo-170x172.png" alt="Logo"> <!-- Ensure to use your logo here -->
        </div>
        <div class="right-section">
            <h2>Forgot Password</h2>
            <form action="admin/funtion.php" method="post">
                <label for="email">Enter your email:</label>
                <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
                <button type="submit" name="btn-forgotpass">Send Reset Link</button>
            </form>
            <p>We'll send a link to reset your password.</p>
        </div>
    </div>
    <script>
    <?php
    // Check if there's a session message to display
    if (isset($_SESSION['notify'])) {
        $message = addslashes($_SESSION['notify']);
        if (strpos($message, 'A reset link has been sent to your email') !== false) {
            echo "Swal.fire({
                title: 'Success',
                text: '$message',
                icon: 'success',
                confirmButtonText: 'OK'
            });";
        } else {
            echo "Swal.fire({
                title: 'Error',
                text: '$message',
                icon: 'error',
                confirmButtonText: 'OK'
            });";
        }
        unset($_SESSION['notify']);
    }
    ?>
</script>
</body>
</html>
