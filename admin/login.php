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
    html, body {
      height: 100%;
      width: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Roboto', sans-serif;
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size: cover;
      background-repeat: no-repeat;
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

    .navbar-nav .nav-link {
      color: #ffffff;
      font-weight: bold;
    }

    /* Login Form */
    #login {
      display: flex;
      height: 100%;
      align-items: center;
      justify-content: center;
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

    /* Media Queries for Mobile Devices */
    @media (max-width: 576px) {
      .navbar-nav {
        display: none; /* Hide the navbar items */
      }

      .navbar-toggler {
        display: block; /* Show the toggler button */
      }

      .navbar-collapse.show {
        display: block !important; /* Ensure the menu shows when expanded */
      }
    }
  </style>
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</head>
<body class="hold-transition">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
      <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" style="border-radius: 50%; height: 50px; width: 50px; object-fit: cover;">
      <h4 style="display: inline-block; vertical-align: middle; margin-left: 10px;">
        <?php echo $_settings->info('name') ?> Kodia Information System
      </h4>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="resident1.php"><i class="fas fa-users"></i> Residents</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="president.php"><i class="fas fa-user-tie"></i> President</a>
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
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" autofocus name="username" placeholder="Enter Username">
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
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
  
  <!-- Description Section -->
  <div id="about" style="display: none;">
    <div class="container">
      <h2>About Kodia NHA</h2>
      <p>The National Housing Authority (NHA) of Kodia is located in Barangay Kodia, Madridejos, Cebu...</p>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Toggle password visibility
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

      // Show/hide sections on nav link click
      $('a.nav-link[href="about.php"]').on('click', function(e) {
        e.preventDefault();
        $('#login').hide();
        $('#about').fadeIn();
      });

      $('a.nav-link[href="#"]').on('click', function(e) {
        e.preventDefault();
        $('#about').hide();
        $('#login').fadeIn();
      });
    });
  </script>
</body>
</html>
