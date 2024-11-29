<?php
session_start(); 
require_once('../admin/connection.php');
require_once("../initialize.php");

if (isset($_GET["reset"])) {
    $email = $_GET["email"];
} else {
    // Redirect or show error if email is not found
    echo '<div class="alert alert-danger">Email parameter is missing.</div>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btn-new-password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $otp = $_POST["otp"];

    // Validate the OTP first
    $sql = "SELECT `code` FROM `users` WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $res = $result->fetch_assoc();
        $get_code = $res["code"];

        // Check if OTP is correct
        if ($otp === $get_code) {
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update the password and reset code
            $reset_code = random_int(100000, 999999);
            $sql = "UPDATE `users` SET `password`=?, `code`=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $hashed_password, $reset_code, $email);
            $stmt->execute();

            $_SESSION["notify"] = "Password has been reset successfully!";
            header("Location: ../admin/login.php");
        } else {
            $_SESSION["notify"] = "Invalid OTP. Please try again.";
            header("Location: ../admin/reset_password.php?reset&email=$email");
        }
    } else {
        $_SESSION["notify"] = "Email not found. Please try again.";
        header("Location: ../admin/forgot_password.php");
    }
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
        <?php
        if (isset($_SESSION["notify"])) {
            echo "<div class='alert alert-info'>".$_SESSION["notify"]."</div>";
            unset($_SESSION["notify"]);
        }
        ?>
        
        <form action="" method="post">
          <div class="form-group mb-3">
            <label for="otp" class="form-label">OTP Code:</label>
            <div id="otp-inputs" class="d-flex justify-content-between">
              <!-- OTP Inputs -->
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

          <button type="submit" class="btn btn-primary" name="btn-new-password">Reset Password</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // JavaScript for password visibility toggle and OTP functionality (same as before)
  </script>
</body>
</html>
