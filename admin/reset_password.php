<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <!-- Include Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Include SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      border-radius: 20px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      background-color: white;
    }
    .card-header {
      background: linear-gradient(135deg, #007bff, #0056b3);
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
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #0056b3, #004085);
    }
    .footer-text {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="card-header">Reset Password</div>
    <div class="card-body">
      <form id="resetPasswordForm" action="../admin/funtion.php" method="post">
        <div class="mb-3">
          <label for="newPassword" class="form-label">Enter your new password:</label>
          <input type="password" class="form-control" name="newPassword" placeholder="********" required>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Confirm your new password:</label>
          <input type="password" class="form-control" name="confirmPassword" placeholder="********" required>
        </div>
        <button type="submit" name="btn-resetpass" class="btn btn-primary w-100">Reset Password</button>
      </form>
    </div>
    <div class="footer-text">
      <!-- Optional Footer Text -->
    </div>
  </div>

  <!-- Include Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert Script for Reset Password Success -->
  <!-- <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent form submission
      
      Swal.fire({
        icon: 'success',
        title: 'Password Reset Successfully',
        text: 'Your password has been reset successfully!',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect to login page after user clicks "OK"
          window.location.href = '../admin/login.php'; // Modify to the appropriate login page
        }
      });
    });
  </script> -->

</body>
</html>
