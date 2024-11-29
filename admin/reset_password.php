<?php
session_start();
require_once('../admin/connection.php');

if (isset($_POST["btn-new-password"])) {
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $otp = filter_var($_POST["otp"], FILTER_SANITIZE_NUMBER_INT);
    $new_password = $_POST["password"];

    if (!$email || !$otp || !$new_password) {
        $_SESSION["notify"] = "invalid";
        header("location: forgot_password");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND code=?");
    $stmt->bind_param('si', $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hashed_password = password_hash($new_password, PASSWORD_ARGON2I);
        $stmt = $conn->prepare("UPDATE users SET password=?, code=NULL WHERE email=?");
        $stmt->bind_param('ss', $hashed_password, $email);
        $update = $stmt->execute();

        if ($update) {
            $_SESSION["notify"] = "password_updated";
        } else {
            $_SESSION["notify"] = "failed";
        }
    } else {
        $_SESSION["notify"] = "invalid";
    }

    header("location: forgot_password");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
          <div class="form-group mb-3">
            <label for="otp" class="form-label">OTP Code:</label>
            <div id="otp-inputs" class="d-flex justify-content-between">
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
              <input type="text" class="form-control otp-box" maxlength="1" required>
            </div>
            <input type="hidden" name="otp" id="otp" value="">
          </div>
          <div class="form-group mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" autocomplete="new-password" required>
              <span class="input-group-text" id="togglePassword">
                <i class="bi bi-eye-fill" id="eyeIcon"></i>
              </span>
            </div>
          </div>
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
          <button type="submit" class="btn btn-primary w-100" name="btn-new-password">Reset Password</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      eyeIcon.classList.toggle('bi-eye-fill');
      eyeIcon.classList.toggle('bi-eye-slash-fill');
    });

    const otpBoxes = document.querySelectorAll('.otp-box');
    const otpHiddenField = document.getElementById('otp');

    otpBoxes.forEach((box, index) => {
      box.addEventListener('input', (e) => {
        const value = e.target.value;
        if (!/^\d$/.test(value)) {
          box.value = '';
          return;
        }
        if (value.length === 1 && index < otpBoxes.length - 1) {
          otpBoxes[index + 1].focus();
        }
        otpHiddenField.value = Array.from(otpBoxes).map(box => box.value).join('');
      });
    });
  </script>
</body>
</html>
