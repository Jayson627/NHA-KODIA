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

        .otp-box {
            display: flex;
            justify-content: space-between;
        }

        .otp-input {
            width: 40px;
            height: 40px;
            font-size: 24px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .otp-input:last-child {
            margin-right: 0;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .reset-password-box {
                padding: 20px;
            }

            .reset-password-title {
                font-size: 20px;
            }

            .otp-input {
                width: 30px;
                height: 30px;
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
        <form action="../admin/process_forgot_password" method="post" onsubmit="return validatePassword();">
            <div class="form-group has-feedback">
                <input type="hidden" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required readonly>
            </div>
            <div class="form-group has-feedback">
                <input type="password" id="new-password" class="form-control" placeholder="Set new password" name="password" required>
                <small id="password-help" style="color: #888; font-size: 12px;">Password must be at least 8 characters long and contain at least one uppercase letter.</small>
            </div>
            <div class="form-group has-feedback otp-box">
                <input type="text" class="otp-input" maxlength="1" pattern="\d*" required>
                <input type="text" class="otp-input" maxlength="1" pattern="\d*" required>
                <input type="text" class="otp-input" maxlength="1" pattern="\d*" required>
                <input type="text" class="otp-input" maxlength="1" pattern="\d*" required>
                <input type="text" class="otp-input" maxlength="1" pattern="\d*" required>
                <input type="text" class="otp-input" maxlength="1" pattern="\d*" required>
                <input type="hidden" name="otp" id="otp" required>
            </div>
            <button type="submit" name="btn-new-password">Set Password</button>
        </form>
    </div>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        // Handle OTP input focus and combine values
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHiddenInput = document.getElementById('otp');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (!/^\d$/.test(input.value)) {
                    input.value = '';
                } else if (input.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                combineOtp();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        function combineOtp() {
            let otpValue = '';
            otpInputs.forEach(input => {
                otpValue += input.value;
            });
            otpHiddenInput.value = otpValue;
        }

        // Password validation function
        function validatePassword() {
            const password = document.getElementById('new-password').value;
            const passwordPattern = /^(?=.*[A-Z]).{8,}$/; // At least 8 characters with at least one uppercase letter
            if (!passwordPattern.test(password)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Password must be at least 8 characters long and contain at least one uppercase letter.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false; // Prevent form submission if the validation fails
            }
            return true; // Allow form submission if validation passes
        }
    </script>
</body>
</html>
<?php
} else {
    // Handle case when reset is not set
    echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'Invalid reset request.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
}
?>
