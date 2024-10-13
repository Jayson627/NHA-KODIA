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

    /* About Section */
    #about {
      display: none;
      height: auto;
      width: 100%;
      color: #333;
      background-color: lightblue;
      padding: 20px;
    }

    #about h2 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 15px;
      text-align: center;
      color: #007bff;
    }

    #about p {
      text-align: justify;
      font-size: 16px;
      line-height: 1.5;
      color: #555;
      margin-bottom: 15px;
    }

    #about .container {
      max-width: 100%;
      background: whitesmoke;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Media Queries for Mobile Devices */
    @media (max-width: 576px) {
      #about h2 {
        font-size: 20px;
      }

      #about p {
        font-size: 14px;
        margin: 10px 0;
      }

      #about .container {
        padding: 15px;
      }
    }

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

    @media (max-width: 576px) {
      .navbar-toggler {
        background-color: #007bff;
        border: none;
        border-radius: 25px;
        padding: 10px 15px;
        transition: background-color 0.3s, transform 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      }

      .navbar-toggler:hover {
        background-color: #0056b3;
        transform: scale(1.05);
      }

      .navbar-toggler .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3E%3Cpath stroke='white' stroke-width='3' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
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
    $(document).ready(function() {
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

      // Show description when "About" link is clicked
      $('a.nav-link[href="about.php"]').on('click', function(e) {
        e.preventDefault();
        $('#login').hide();
        $('#about').fadeIn();
      });

      // Show login form when "Login" link is clicked
      $('a.nav-link[href="#"]').on('click', function(e) {
        e.preventDefault();
        $('#about').hide();
        $('#login').fadeIn();
      });
    });
  </script>
</head>
<body class="hold-transition">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-blue bg-blue">
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

  <!-- Login Form -->
  <div id="login">
    <div class="card">
      <div class="card-header">
        <h4>Login</h4>
      </div>
      <div class="card-body">
        <form action="" method="POST">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <span class="input-group-text" id="togglePassword"><i class="fas fa-eye"></i></span>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- About Section -->
  <div id="about">
    <div class="container">
      <h2>About the System</h2>
      <p>This is the description of the system. Here you can explain its features, purpose, and how it helps residents and administrators manage information efficiently.</p>
    </div>
  </div>
</body>
</html>
