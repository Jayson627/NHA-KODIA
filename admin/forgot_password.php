<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
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
    <div class="card-header">Forgot Password</div>
    <div class="card-body">
      <form id="forgotPasswordForm" action="../admin/function.php" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Enter your email address:</label>
          <input type="email" class="form-control" name="email" placeholder="jayson5@gmail.com" required>
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        </div>
        <button type="submit" name="btn-forgotpass" class="btn btn-primary w-100">Submit</button>
      </form>
    </div>
    <div class="footer-text">
     
    </div>
  </div>
  <?php
session_start();

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION["notify"])) {
    // Determine the type of notification
    if ($_SESSION["notify"] == "success") {
        $message = "Your OTP has been sent successfully!";
        $icon = "success";
    } elseif ($_SESSION["notify"] == "failed") {
        $message = "Something went wrong. Please try again.";
        $icon = "error";
    } elseif ($_SESSION["notify"] == "invalid") {
        $message = "Invalid OTP or email. Please check your details.";
        $icon = "warning";
    }

    // Show SweetAlert
    echo "<script>
        Swal.fire({
            title: 'Notification',
            text: '$message',
            icon: '$icon',
            confirmButtonText: 'OK'
        });
    </script>";

    // Clear session after alert is displayed
    unset($_SESSION["notify"]);
}
?>

<script>
document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'C' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
