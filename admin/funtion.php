<?php
session_start();
require_once("mailer.php");
require_once('../admin/connection.php');
require_once("../initialize.php");

// Helper function to send reset email
function sendResetEmail($email, $reset_code) {
    global $mail;
    $mail->SetFrom("alcantarajayson118@gmail.com");
    $mail->AddAddress($email);
    $mail->Subject = "Reset Password OTP";
    $mail->Body = "Use this OTP Code to reset your password: " . $reset_code . "<br/>" . 
                  "Click the link to reset password: http://nha-kodia.com/admin/reset_password?reset&email=$email";

    return $mail->send();
}

// Handle forgotten password (generate OTP)
if (isset($_POST["btn-forgotpass"])) {
    $email = $_POST["email"];
    
    // Query the database to check if the email exists
    $sql = "SELECT * FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Email exists, generate OTP and send the reset email
        $reset_code = random_int(100000, 999999);
        
        // Direct SQL query to update the reset code
        $update_sql = "UPDATE `users` SET `code` = '$reset_code' WHERE email = '$email'";
        
        if ($conn->query($update_sql) === TRUE) {
            if (sendResetEmail($email, $reset_code)) {
                $_SESSION["notify"] = "A reset link has been sent to your email.";
            } else {
                $_SESSION["notify"] = "Mailer Error: " . $mail->ErrorInfo;
            }
            header("location: ../admin/forgot_password");
            exit();
        } else {
            $_SESSION["notify"] = "Failed to update the reset code. Please try again.";
            header("location: ../admin/forgot_password");
            exit();
        }
    } else {
        // If the email does not exist in the database
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("location: ../admin/forgot_password");
        exit();
    }
}

// Handle new password submission (validate OTP and reset password)
if (isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Direct SQL query to get the code from the database
    $sql = "SELECT `code` FROM `users` WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $get_code = $row['code'];

        // Validate OTP
        if ($get_code && $otp === $get_code) {
            $reset = random_int(100000, 999999);
            $hashed_password = password_hash($password,  PASSWORD_ARGON2I);

            // Direct SQL query to update the password and reset code
            $update_sql = "UPDATE `users` SET `password` = '$hashed_password', `code` = '$reset' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                $_SESSION["notify"] = "Your password has been reset successfully.";
                header("location: ../admin/forgot_password");
                exit();
            }
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("location: ../admin/reset_password");
            exit();
        }
    } else {
        $_SESSION["notify"] = "No user found with this email. Please try again.";
        header("location: ../admin/reset_password");
        exit();
    }
}
?>
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
        <form action="../admin/function" method="post">
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
            if (strpos($message, 'Your password has been reset successfully') !== false) {
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
<?php
} else {
    // Handle case when reset is not set
}
?>
