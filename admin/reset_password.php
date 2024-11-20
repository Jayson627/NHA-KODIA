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
    /* Add your existing styling here */
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center h-100">
    <div class="card">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <form action="" method="post">
          <!-- Hidden Email Field -->
          <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">

          <!-- OTP Code Input -->
          <div class="form-group mb-3">
            <label for="otp" class="form-label">OTP Code:</label>
            <input type="text" class="form-control" name="otp" placeholder="Enter your OTP code" autocomplete="one-time-code" required>
          </div>

          <!-- New Password Input with Show/Hide Eye and Autofill -->
          <div class="form-group mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <div class="input-group">
              <input type="password" class="form-control" id="new_password" name="password" placeholder="Enter new password" autocomplete="new-password" required>
              <span class="input-group-text" id="togglePassword">
                <i class="bi bi-eye-fill" id="eyeIcon"></i>
              </span>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" name="btn-new-password" class="btn btn-primary">Reset Password</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Include Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JavaScript for Toggling Password Visibility -->
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#new_password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
      // Toggle the type attribute between password and text
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      // Toggle the icon between eye and eye-slash
      eyeIcon.classList.toggle('bi-eye-fill');
      eyeIcon.classList.toggle('bi-eye-slash-fill');
    });
  </script>
</body>
</html>
