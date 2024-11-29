<?php
session_start();
require_once('../admin/connection.php');
require_once("../initialize.php");

// Generate CSRF token if not already generated
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch the email if it's passed in the URL
if (isset($_GET["reset"]) && isset($_GET["email"])) {
    $email = $_GET["email"];
} else {
    // If email is missing, handle it
    echo '<div class="alert alert-danger">Email is missing. Please try again.</div>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <!-- Include Bootstrap 5 CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Include Bootstrap Icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      width: 100%;
      max-width: 400px;
      border-radius: 15px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
      overflow: hidden;
    }
    .card-header {
      background-color: #007bff;
      color: white;
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
      padding: 1rem;
    }
    .card-body {
      padding: 2rem;
    }
    .form-label {
      font-weight: 500;
      color: #555;
    }
    .form-control {
      border-radius: 30px;
      padding: 0.75rem;
      border: 1px solid #ced4da;
    }
    .form-control:focus {
      box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
      border-color: #007bff;
    }
    .btn-primary {
      background: linear-gradient(135deg, #007bff, #0056b3);
      border: none;
      border-radius: 30px;
      padding: 0.75rem;
      font-size: 1.1rem;
      width: 100%;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #0056b3, #004085);
    }
    .input-group-text {
      border-radius: 0 30px 30px 0;
      cursor: pointer;
    }
    .otp-box {
      width: 50px;
      text-align: center;
      margin-right: 5px;
      font-size: 1.2rem;
      border-radius: 8px;
      border: 1px solid #ced4da;
      outline: none;
    }
    .otp-box:focus {
      border-color: #007bff;
      box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
    }
    @media (max-width: 576px) {
      .card {
        margin: 20px;
      }
      .otp-box {
        width: 40px;
        margin-right: 3px;
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center h-100">
    <div class="card">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <form action="../admin/funtion" method="post">
          <!-- CSRF Token -->
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

          <!-- OTP Field -->
          <div class="form-group mb-3">
            <label for="otp" class="form-label">OTP Code:</label>
            <div id="otp-inputs" class="d-flex justify-content-between">
              <!-- 6 Input Boxes for OTP -->
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
            </div>
            <!-- Hidden field to collect the OTP -->
            <input type="hidden" name="otp" id="otp" value="">
          </div>

          <!-- New Password Input -->
          <div class="form-group mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" autocomplete="new-password" required>
              <span class="input-group-text" id="togglePassword">
                <i class="bi bi-eye-fill" id="eyeIcon"></i>
              </span>
            </div>
          </div>

          <!-- Hidden Email Field -->
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary" name="btn-new-password">Reset Password</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Include Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JavaScript for Toggling Password Visibility -->
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password'); // Fixed selector
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
      // Toggle the type attribute between password and text
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      // Toggle the icon between eye and eye-slash
      eyeIcon.classList.toggle('bi-eye-fill');
      eyeIcon.classList.toggle('bi-eye-slash-fill');
    });

    const otpBoxes = document.querySelectorAll('.otp-box');
    const otpHiddenField = document.getElementById('otp');

    otpBoxes.forEach((box, index) => {
      box.addEventListener('input', (e) => {
        const value = e.target.value;
        if (!/^\d$/.test(value)) {
          // Clear the input if it's not a valid number
          box.value = '';
          return;
        }

        // Move to the next box when a valid number is entered
        if (value.length === 1 && index < otpBoxes.length - 1) {
          otpBoxes[index + 1].focus();
        }

        // Update the hidden input field with the OTP
        let otpValue = '';
        otpBoxes.forEach((input) => {
          otpValue += input.value;
        });
        otpHiddenField.value = otpValue;
      });

      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && box.value === '' && index > 0) {
          otpBoxes[index - 1].focus();
        }
      });

      
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_ARGON2I);
    
    // Update the password in the database
    $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param('ss', $hashed_password, $email);
    $stmt->execute();
}


      // Prevent non-numeric input during keydown
      box.addEventListener('keypress', (e) => {
        if (!/^\d$/.test(e.key)) {
          e.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
