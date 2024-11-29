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
    /* Your CSS styling here (same as before) */
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
