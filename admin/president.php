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
            padding: 5px;
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
            border-radius: 50%; /* Make the logo circular */
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
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 70px; /* Added margin to avoid overlap with header */
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }
        .login-container label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 3px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 3px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            margin-top: 15px;
            background-color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
</head>
<body>

<header>
    <div class="logo">
        <img src="lo.png" alt="Logo"> <!-- Replace 'logo.png' with the path to your logo -->
        NHA President Login Portal
    </div>
    <a href="about.php">Home</a> <!-- Logout link aligned to the right -->
</header>

<div class="login-container">
    <h2>President Login</h2>
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
    <form action="process_president.php" method="post" autocomplete="off">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" autocomplete="off" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" autocomplete="new-password" required>

        <button type="submit">Login</button>
    </form>

</div>

</body>
</html>
