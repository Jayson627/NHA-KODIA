<!DOCTYPE html>
<html lang="en">
<?php require_once('../config.php'); ?>
<?php require_once('inc/header.php'); ?>
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
    }
    .navbar-brand img {
      border-radius: 50%;
      height: 40px;
      width: 40px;
    }
    .navbar-brand h4 {
      font-size: 1.2rem;
      margin-left: 10px;
    }
    .navbar-nav .nav-link {
      color: #ffffff;
      font-weight: bold;
    }
    .navbar-nav .nav-link:hover {
      color: #f0f0f0;
    }
    /* Dropdown */
    .dropdown-menu {
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 10px 0;
    }
    .dropdown-item {
      padding: 10px 20px;
      transition: background-color 0.3s;
    }
    .dropdown-item:hover {
      background-color: #007bff;
      color: white;
    }
    /* Make Navbar always visible */
    .navbar-collapse {
      display: block !important; /* Always visible on all screen sizes */
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
    }
  </style>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <script>
    let typingInterval; // Variable to hold the typing interval
    const text = "Welcome to NHA Kodia Information System";
    const speed = 150; // Typing speed in milliseconds
    let index = 0;

    function type() {
      if (index < text.length) {
        document.getElementById("animated-text").innerHTML += text.charAt(index);
        index++;
        typingInterval = setTimeout(type, speed); // Store the timeout in typingInterval
      } else {
        setTimeout(() => {
          document.getElementById("animated-text").innerHTML = ""; // Clear the text
          index = 0; // Reset index
          type(); // Restart typing
        }, 2000); // Pause before restarting
      }
    }

    $(document).ready(function() {
      $('#login').hide(); // Ensure login form is hidden initially
      $('#animated-text').show(); // Show animated text initially

      // Show login form only when "Admin", "Resident", or "Officer" link is clicked
      $('#login-as-admin, #login-as-resident, #login-as-officer').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
        $('#login').fadeIn(); // Show login form
        $('#animated-text').hide(); // Hide animated text

        // Set role and form action based on which link was clicked
        const role = $(this).attr('id').replace('login-as-', ''); // Get role from the ID
        $('#role').val(role);
        $('#login-frm').attr('action', role + '_login.php'); // Set form action
      });

      // Show/hide password functionality
      $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const passwordFieldType = passwordField.attr('type');
        const icon = $(this);

        if (passwordFieldType === 'password') {
          passwordField.attr('type', 'text');
          icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          passwordField.attr('type', 'password');
          icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // Validate email and password before form submission
      $('#login-frm').on('submit', function(e) {
        const email = $('[name="email"]').val();
        const emailPattern = /.+@gmail\.com$/;
        const password = $('[name="password"]').val();
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!emailPattern.test(email)) {
          e.preventDefault(); // Prevent form submission
          Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid Gmail address.',
          });
        } else if (!passwordPattern.test(password)) {
          e.preventDefault(); // Prevent form submission
          Swal.fire({
            icon: 'error',
            title: 'Invalid Password',
            text: 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.',
          });
        }
      });
    });

    window.onload = function() {
      type(); // Start typing when the page loads
    };
  </script>
</head>
<body class="hold-transition">
  <!-- Animated Text -->
  <div id="animated-text" class="animated-text"></div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-blue bg-blue">
    <a class="navbar-brand" href="#">
      <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" style="border-radius: 50%; height: 50px; width: 50px; object-fit: cover;">
      <h4 style="display: inline-block; vertical-align: middle; margin-left: 10px;">
        <?php echo $_settings->info('name') ?> Kodia Information System
      </h4>
    </a>
    <div class="navbar-collapse"> <!-- Removed the collapsible behavior -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-users"></i> Login As
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#" id="login-as-admin">Admin</a>
            <a class="dropdown-item" href="residents.php">Users</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Login Form Section -->
  <div id="login">
    <div class="card">
      <div class="card-header">
        <h4><?php echo $_settings->info('name') ?> Kodia Information System</h4>
      </div>
      <div class="card-body">
        <form id="login-frm" action="" method="post">
          <input type="hidden" name="role" id="role" value="">
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" autofocus name="email" placeholder="Enter email" required 
                   pattern=".+@gmail\.com$" title="Please enter a valid Gmail address (e.g., example@gmail.com)">
          </div>

          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required
                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
                   title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
              </span>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <div class="form-group text-center">
            <a href="forgot_password.php" class="text-primary">Forgot Password?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
y