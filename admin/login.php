<!DOCTYPE html>
<html lang="en">
<?php require_once('../config.php'); ?>
<?php require_once('inc/header.php'); ?>
<?php
session_start();

// Initialize session variables for tracking login attempts and cooldown time
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (!isset($_SESSION['last_failed_login_time'])) {
    $_SESSION['last_failed_login_time'] = 0;
}

// Function to handle login logic
function login($email, $password) {
    // Replace with actual authentication logic (e.g., check against a database)
    $correct_email = "admin@example.com"; // Example email for admin
    $correct_password = "adminPassword"; // Example password for admin (ensure hashing for real-world use)

    if ($email !== $correct_email || $password !== $correct_password) {
        return false; // Incorrect credentials
    }
    return true; // Correct credentials
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user is within the login attempt limit and cooldown
    if ($_SESSION['login_attempts'] >= 3 && (time() - $_SESSION['last_failed_login_time']) < 30) {
        // If too many failed attempts, inform user of the wait time
        $wait_time = 30 - (time() - $_SESSION['last_failed_login_time']);
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Too many login attempts',
                    text: 'Please wait for $wait_time seconds before trying again.'
                });
              </script>";
        exit;
    }

    // Attempt login
    if (login($email, $password)) {
        $_SESSION['login_attempts'] = 0; // Reset login attempts after successful login
        echo "<script>Swal.fire({icon: 'success', title: 'Login successful'});</script>";
        // Redirect to the dashboard or admin page here
    } else {
        // Increment failed attempts and set the last failed login time
        $_SESSION['login_attempts'] += 1;
        $_SESSION['last_failed_login_time'] = time();
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Incorrect Credentials',
                    text: 'You have made {$_SESSION['login_attempts']} of 3 attempts.'
                });
              </script>";
    }
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Google Fonts - Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <!-- Custom CSS -->
  <style>
    /* General Styles */
    body {
      height: 100vh;
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: url('houses.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    /* Navbar */
    .navbar {
      background-color: #343a40;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap; /* Allow items to wrap on smaller screens */
    }

    .navbar-brand img {
      border-radius: 50%;
      height: 80px;
      width: 80px;
    }

    .navbar-brand h4 {
      font-size: 1.5rem;
      margin-left: 10px;
      color: #ffffff;
      font-weight: bold;
    }

    .navbar-nav {
      display: flex;
      justify-content: flex-end;
      margin-left: auto;
      flex-wrap: wrap;
    }

    .navbar-nav .nav-link {
      color: #ffffff;
      font-weight: bold;
      font-size: 1.2rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-left: 20px;
      padding: 8px 15px;
      border-radius: 30px;
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: #f0f0f0;
      background-color: #007bff;
      padding: 8px 20px;
    }

    body {
      font-family: 'Poppins', sans-serif;
    }

    /* Push Menu Styles */
    #push-menu {
      height: 100%;
      width: 0;
      position: fixed;
      top: 0;
      left: 0;
      background-color: white;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
      z-index: 1000;
    }

    #push-menu a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: black;
      display: block;
      transition: 0.3s;
    }

    #push-menu a:hover {
      background-color: blue;
    }

    #push-menu .close-btn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
      color: red;
    }

    .open-menu-btn {
      font-size: 30px;
      color: white;
      cursor: pointer;
      display: none;
      z-index: 1050;
    }

    .open-menu-btn:hover {
      color: #007bff;
    }

    /* Login Form */
    #login {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 1;
    }

    .card {
      width: 90%;
      max-width: 400px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      overflow: hidden;
      background-color: #ffffff;
    }

    .card-header {
      background-color: #007bff;
      color: white;
      text-align: center;
      border-bottom: none;
      padding: 15px 0;
    }

    .card-body {
      padding: 20px;
    }

    .input-group-text {
      background-color: #007bff;
      color: white;
      border: none;
    }

    .form-control {
      border-radius: 0 20px 20px 0;
    }

    .btn-primary {
      border-radius: 20px;
      background-color: #007bff;
      border-color: #007bff;
    }

    /* Animated Text */
    .animated-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 3rem;
      color: #ffffff;
      white-space: nowrap;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    /* Media Queries */
    @media (max-width: 768px) {
      .navbar-brand h4 {
        font-size: 1rem;
      }

      .navbar-brand img {
        height: 35px;
        width: 35px;
      }

      .card {
        width: 95%;
        max-width: none;
      }

      .card-header h4 {
        font-size: 1.2rem;
      }

      .navbar-nav .nav-link {
        display: none !important;
      }

      /* Show Push Menu only on small screens */
      #push-menu {
        width: 0;
      }

      /* Display the hamburger menu button in mobile */
      .open-menu-btn {
        display: block;
      }

      .navbar-nav {
        display: none;
      }

      .open-menu-btn {
        margin-left: auto;
      }
    }

    @media (max-width: 480px) {
      .animated-text {
        font-size: 2rem; /* Adjust animated text size for very small screens */
      }

      .card-header h4 {
        font-size: 1.1rem;
      }
    }
  </style>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
    $(document).ready(function() {
      // Handle login form submission and display alerts based on the session variables
      const loginAttempts = <?php echo $_SESSION['login_attempts']; ?>;
      const lastFailedLoginTime = <?php echo $_SESSION['last_failed_login_time']; ?>;
      const currentTime = Math.floor(Date.now() / 1000); // Get current timestamp
      const cooldownTime = 30; // Cooldown time in seconds (30 seconds)
      const maxAttempts = 3;

      if (loginAttempts >= maxAttempts && (currentTime - lastFailedLoginTime) < cooldownTime) {
        const waitTime = cooldownTime - (currentTime - lastFailedLoginTime);
        Swal.fire({
          icon: 'error',
          title: 'Too many login attempts',
          text: `Please wait for ${waitTime} seconds before trying again.`
        });
      }
    });
  </script>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-blue bg-blue">
    <a class="navbar-brand" href="#">
      <img src="lo.png" alt="Logo">
    </a>

    <span class="open-menu-btn">&#9776;</span>

    <div class="navbar-nav">
      <a href="about.php" class="nav-link">About</a>
      <a href="#" id="login-as-admin" class="nav-link">Login as Admin</a>
      <a href="residents.php" class="nav-link">Login as Resident</a>
    </div>

    <div id="push-menu">
      <a href="javascript:void(0)" class="close-btn">&times;</a>
      <a href="about.php">About</a>
      <a href="#" id="login-as-admin">Admin</a>
      <a href="residents.php">Resident</a>
    </div>
  </nav>

  <div id="animated-text" class="animated-text"></div>

  <div id="login">
    <div class="card">
      <div class="card-header">
        <h4>Login to Dashboard</h4>
      </div>
      <div class="card-body">
        <form method="POST" id="login-frm">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
