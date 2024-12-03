<?php
session_start();
include 'includes/conn.php';

if (isset($_GET["reset"])) {
    $email = $_GET["email"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   

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
</head>
<body>
    <div class="reset-password-box">
        <h2 class="reset-password-title">Reset Password</h2>
        <form action="../admin/funtion" method="post">
            <div class="form-group has-feedback">
                <input type="hidden" name="email" class="form-control" value="<?php echo $email ?>" required readonly>
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
    <script>
    <?php
    // Check if there's a session message to display
    if (isset($_SESSION['notify'])) {
        $message = addslashes($_SESSION['notify']);
        
        // Check if the message contains "Your password has been reset successfully" for success
        if (strpos($message, 'Your password has been reset successfully') !== true) {
            echo "Swal.fire({
                title: 'Success',
                text: '$message',
                icon: 'success',
                confirmButtonText: 'OK'
            });";
        } else {
            // If the message doesn't indicate success, show as error
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
<?php
} else {
    // Handle case when reset is not set
}
?>
