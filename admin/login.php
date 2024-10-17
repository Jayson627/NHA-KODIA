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
  height: 100%;
  width: 100%;
  color: #333;
  background-color: lightblue;
  padding: 40px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}
#about h2 {
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 20px;
  text-align: center;
  color: #007bff;
}
#about p {
  text-align: center;
  font-size: 16px;
  line-height: 2.2;
  color: #555;
  margin-bottom: 20px;
}
#about .container {
  max-width: 800px;
  margin: auto;
  background: whitesmoke;
  padding: 20px;
  border-radius: 10px;
}

  </style>

  <!-- JavaScript for Login and About Toggle -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
    });
  </script>
</head>
<body class="hold-transition">
  <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-blue bg-blue">
  <a class="navbar-brand" href="#">
    <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" style="border-radius: 50%; height: 50px; width: 50px; object-fit: cover; display: inline-block; vertical-align: middle;">
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
      <a class="nav-link" href="#"><i class="fas fa-user-cog"></i> Admin</a> <!-- Changed to admin icon -->

      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a> <!-- Added info icon and link to About page -->
      </li>
      <li class="nav-item">
        <a class="nav-link" href="residents.php"><i class="fas fa-users"></i> Residents</a> <!-- Added Residents link -->
      </li>
      <li class="nav-item">
        <a class="nav-link" href="president.php"><i class="fas fa-user-tie"></i> President</a> <!-- Added President link -->
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
            <input type="text" class="form-control" autofocus name="username" placeholder="Username">
          </div>
          <div class="form-group input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
      </div>
      <input type="email" class="form-control" name="email" placeholder="Email">
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
<div class="form-group text-center">
  <a href="forgot_password.php" class="text-primary">Forgot Password?</a>
</div>

           
          </div>
        </form>
      </div>
    </div>
  </div>
<!-- Description Section -->
<div id="about">
  <div class="container">
    <h2>About Kodia NHA</h2>
    <p>The National Housing Authority (NHA) of Kodia is located in Barangay Kodia, Madridejos, Cebu. The housing consists of 750 units, 27 blocks, and 58 lots. Based on history, the Kodia NHA was constructed the year after Typhoon Yolanda and was turned over to the barangay in May 2021. All barangays in Madridejos, except for Barangay Tugas and Kangwayan, were provided with housing units. Each barangay was allocated 50 units for those residents who needed to evacuate during typhoons. The process of allocation involved barangay officials distributing forms to the recipients to fill out the necessary information.</p>
    <p>Every barangay received 50 units, while Barangay Kodia received 100 units because the housing was built in their areas. Based on our survey, the barangays with the most residents living in the housing are Barangay Mancilang and Barangay Poblacion, as they are closest to the sea and most prone to typhoons. According to our survey, there are over 80 units that are not occupied but have owners. There is a possibility that the housing units may be reclaimed if they are not occupied for over a year.</p>
  </div>
</div>
  </body>
  </html> 