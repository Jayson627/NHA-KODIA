<?php
session_start();
include 'includes/conn.php';

if (isset($_GET["reset"]) && isset($_GET["email"])) {
    $email = $_GET["email"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- Styling for the page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-password-box {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            box-sizing: border-box;
        }

        .reset-password-title {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #5cb85c;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #4cae4c;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .reset-password-box {
                padding: 20px;
            }

            .reset-password-title {
                font-size: 20px;
            }
        }
    </style>

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">

</head>
<body>
    <div class="reset-password-box">
        <h2 class="reset-password-title">Reset Password</h2>
        <form action="../admin/funtion" method="post">
            <div class="form-group has-feedback">
                <input type="hidden" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required readonly>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Set new password" name="password" required>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="OTP Code" name="otp" required>
            </div>
            <button type="submit" name="btn-new-password">Set Password</button>
        </form>
    </div>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <script>
        // Check for success parameter in the URL and show the sweet alert
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            Swal.fire({
                title: 'Success!',
                text: 'Your password has been reset successfully! Have a wonderful day!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        // Display session notifications using sweet alert
        <?php if (isset($_SESSION["notify"])): ?>
            Swal.fire({
                title: 'Notification',
                text: "<?php echo $_SESSION["notify"]; unset($_SESSION["notify"]); ?>",
                icon: 'info',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>
<?php
} else {
    // Handle case when reset is not set
    echo "Invalid reset request.";
}
?>
