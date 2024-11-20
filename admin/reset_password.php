<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="reset-password-container">
        <h2>Reset Your Password</h2>

        <?php
        if (isset($_SESSION["notify"])) {
            echo '<p>' . $_SESSION["notify"] . '</p>';
            unset($_SESSION["notify"]);
        }

        if (isset($_GET["error"])) {
            echo '<p style="color: red;">There was an error, please check your input and try again.</p>';
        }
        ?>

        <!-- Reset Password Form -->
        <form method="POST" action="reset_password.php">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <input type="password" name="password" placeholder="Enter new password" required>
            <button type="submit" name="btn-new-password">Reset Password</button>
        </form>
    </div>

</body>
</html>

<!-- CSS -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .reset-password-container {
        max-width: 400px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .reset-password-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .reset-password-container p {
        text-align: center;
        font-size: 16px;
        color: #444;
    }

    .reset-password-container form {
        display: flex;
        flex-direction: column;
    }

    .reset-password-container input {
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .reset-password-container button {
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .reset-password-container button:hover {
        background-color: #45a049;
    }

    .reset-password-container p {
        font-size: 14px;
        color: red;
    }
</style>

</body>
</html>