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
      <form id="forgotPasswordForm" action="../admin/funtion" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Enter your email address:</label>
          <input type="email" class="form-control" name="email" placeholder="jayson5@gmail.com" required>
        </div>
        <button type="submit" name="btn-forgotpass" class="btn btn-primary w-100">Submit</button>
      </form>
    </div>
    <div class="footer-text">
     
    </div>
  </div>
  
  <script>
    document.addEventListener('contextmenu', function (e) {
      e.preventDefault();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'C' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
        e.preventDefault();
      }
    });

    // SweetAlert2 example
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
      e.preventDefault();
      // Assume AJAX call here, and on success:
      Swal.fire({
        icon: 'success',
        title: 'Email Sent',
        text: 'A password reset link has been sent to your email address.',
        confirmButtonColor: '#007bff'
      });
    });
  </script>

</body>
</html>
