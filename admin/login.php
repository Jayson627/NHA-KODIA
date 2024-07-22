<!DOCTYPE html>
<html lang="en">
<?php require_once('../config.php'); ?>
<?php require_once('inc/header.php'); ?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    .login-title {
      text-shadow: 2px 2px black;
    }
    .navbar-brand img {
      border-radius: 50%;
      height: 50px;
      width: 50px;
      object-fit: cover;
    }
    .navbar-brand h4 {
      font-size: 1.5rem;
      margin-bottom: 0;
      font-weight: bold;
      color: #ffffff;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
    }
    .navbar-nav .nav-link {
      color: #ffffff;
      font-weight: bold;
      margin-right: 10px;
      position: relative;
      transition: all 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
      color: #f0f0f0;
      text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
      transform: translate3d(0, -2px, 0);
    }
    .navbar-nav .active .nav-link {
      color: #bbbbbb;
    }
    .navbar {
      background-color: #343a40;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 1;
    }
    .navbar::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
      z-index: -1;
    }
    #login {
      display: flex;
      height: 100%;
      align-items: center;
      justify-content: center;
    }
    .card {
      width: 100%;
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
      border-radius: 20px 0 0 20px;
    }
    .form-control {
      border-radius: 0 20px 20px 0;
      border: 1px solid #ced4da;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-primary {
      border-radius: 20px;
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0069d9;
      border-color: #0062cc;
    }
    .btn-primary:focus, .btn-primary.focus {
      box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }
    .carousel-caption {
      background: rgba(0, 0, 0, 0.5);
      color: white;
      padding: 10px;
      text-align: center;
    }
    .carousel-inner .item img {
      display: block;
      margin: auto;
      max-height: 400px;
    }
    /* Styles for About Us section */
    #about {
      display: none;
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
    }
    #about h2 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
    }
    #about p {
      text-align: center;
      font-size: 16px;
      line-height: 1.6;
    }
  </style>

  <!-- JavaScript for Login and About Toggle -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
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

      // Submit login form with SweetAlert message
      $('#login-frm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
          type: 'POST',
          url: _base_url_ + 'classes/Login.php?f=login',
          data: $(this).serialize(),
          success: function(response) {
            let resp = JSON.parse(response);
          if (resp.status == 'Incorrect username or password') {
            //   Swal.fire({
            //     title: 'Login Successful',
            //     text: 'Welcome back!',
            //     icon: 'success'
            //   }).then(() => {
            //     window.location.href = 'dashboard.php'; // Redirect to the dashboard or another page
            //   });
            // } else {
              Swal.fire({
                title: 'Login Failed',
                text: 'Invalid username or password. Please try again.',
                icon: 'error'
              });
            }
          }
        });
      });
    });
  </script>
</head>
<body class="hold-transition">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
      <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" style="border-radius: 50%; height: 50px; width: 50px; object-fit: cover; display: inline-block; vertical-align: middle;">
      <h4 style="display: inline-block; vertical-align: middle; margin-left: 10px;">
        <?php echo $_settings->info('name') ?> 
      </h4>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fas fa-home"></i> Home</a> <!-- Added home icon -->
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a> <!-- Added info icon and link to About page -->
        </li>
        
      </ul>
    </div>
  </nav>

  <!-- Login Form Section -->
  <div id="login">
    <div class="card">
      <div class="card-header">
        <h4><?php echo $_settings->info('name') ?> </h4>
      </div>
      <div class="card-body">
        <form id="login-frm" action="" method="post">
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" autofocus name="username" placeholder="Username">
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <div class="form-group">
            <a href="" data-toggle="modal" data-target="#modal-forgotpass">Forgot Password?</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Description Section -->
  <div id="about">
    <div class="container mt-5">
      <h2>About Us</h2>
      <p>Located in Barranggay Kodia,Madridejos,Cebu</p>
      <p>750 Units</p>
      <p>Total of Blocks</p>
      <p>Total of Lots</p>


    </div>
  </div>
</body>
</html>
