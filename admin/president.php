<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>President Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('nha.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        header {
            background-color: #007bff;
            width: 100%;
            padding: 10px 20px;
            text-align: left;
            color: #ffffff;
            font-size: 24px;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        header a {
            color: #ffffff;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }

        header a:hover {
            text-decoration: underline;
        }

        .login-container {
            background-color: white;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            margin: 120px auto;
            text-align: center;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 24px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-size: 26px;
            color: blue;
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
            color: white;
            font-weight: bold;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid lightblue;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container button {
            width: 50%;
            padding: 12px;
            background-color: blue;
            border: none;
            border-radius: 20px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: lightblue;
        }

        .login-container p {
            margin-top: 20px;
            font-size: 14px;
        }

        .login-container p a {
            color: blue;
            text-decoration: none;
        }

        .login-container p a:hover {
            text-decoration: underline;
        }

        .logout-btn {
            margin-top: 15px;
            background-color: blue;
        }

        .logout-btn:hover {
            background-color: blue;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            header {
                font-size: 20px; /* Slightly smaller text */
            }

            .login-container {
                margin: 80px auto; /* Reduce margin for mobile */
                width: 95%; /* Take full width on small screens */
            }

            .login-container button {
                width: 80%; /* Button width adjustment */
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <div class="logo">
        <img src="lo.png" alt="Logo">
        NHA President Login Portal
    </div>
    <a href="about.php">Home</a>
</header>

<div class="login-container">
    <div class="card-header">
        President Login
    </div>
    <?php
    if (isset($_GET['status'])) {
        $status = htmlspecialchars($_GET['status']);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() { 
                Swal.fire({
                    icon: '$status',
                    title: '$status',
                    text: '" . htmlspecialchars($_GET['message']) . "',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
    ?>
    <form action="process_president.php" method="post" autocomplete="off" style="padding: 20px;">
        <label for="name">Name:</label>
        <input type="text" id="name" placeholder="Enter Username" name="name" autocomplete="off" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" placeholder="Enter Password" name="password" autocomplete="new-password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
