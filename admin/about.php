<!DOCTYPE html>
<html lang="en">
<?php require_once('../config.php'); ?>
<?php require_once('inc/header.php'); ?>
<body class="hold-transition">
  <script>
    start_loader();
  </script>
  <style>
    html, body {
      height: 100%;
      width: 100%;
      margin: 0;
      padding: 0;
    }
    body {
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
    .navbar-nav .nav-link {
      color: white;
      font-weight: bold;
      margin-right: 10px;
    }
    .navbar-nav .nav-link:hover {
      color: #ddd;
    }
    .navbar-nav .active .nav-link {
      color: #bbb;
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
      border-radius: 10px; /* Rounded corners */
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      overflow: hidden; /* Ensures shadow and border radius work correctly */
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
    .form-control {
      border-radius: 20px; 
      border: 1px solid #ced4da; /* Light gray border */
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

    /* Carousel styles */
    .carousel-caption {
      background: rgba(0, 0, 0, 0.5); /* Semi-transparent background for captions */
      color: white;
      padding: 10px;
      text-align: center;
    }

    .carousel-inner .item img {
      display: block;
      margin: auto;
      max-height: 400px; /* Limit maximum height of images */
    }
  </style>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
      <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="About.php">About</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#">Login <span class="sr-only">(current)</span></a>
        </li>
      </ul>
    </div>
  </nav>

 

 

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  

  <script>
    $(document).ready(function() {
      end_loader();
    });
  </script>
</body>
</html>
