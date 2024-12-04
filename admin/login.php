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
  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
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

  <script>
    let typingInterval;
    const text = "Welcome to NHA Kodia Information System";
    const speed = 150; 
    let index = 0;
    let loginAttempts = 0;
    const maxAttempts = 3;
    const cooldownTime = 60; // seconds
    let cooldownTimer;

    function type() {
      if (index < text.length) {
        document.getElementById("animated-text").innerHTML += text.charAt(index);
        index++;
        typingInterval = setTimeout(type, speed); 
      } else {
        setTimeout(() => {
          document.getElementById("animated-text").innerHTML = "";
          index = 0; 
          type();
        }, 2000); 
      }
    }

    function startCooldown() {
      $('#login-btn').prop('disabled', true);
      let timeLeft = cooldownTime;
      cooldownTimer = setInterval(() => {
        if (timeLeft <= 0) {
          clearInterval(cooldownTimer);
          $('#login-btn').prop('disabled', false);
          loginAttempts = 0;
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Too Many Attempts',
            text: `Please wait ${timeLeft} seconds before trying again.`,
            timer: 1000,
            showConfirmButton: false,
            willClose: () => {
              timeLeft--;
            }
          });
        }
      }, 1000);
    }

    $(document).ready(function() {
      $('#login').hide();
      $('#animated-text').show();

      $('#login-as-admin, #login-as-resident, #login-as-officer').on('click', function(e) {
        e.preventDefault();
        $('#login').fadeIn();
        $('#animated-text').hide();

        const role = $(this).attr('id').replace('login-as-', '');
        $('#role').val(role);
        $('#title').text(role.charAt(0).toUpperCase() + role.slice(1));
      });

      $('#login-frm').submit(function(e) {
        e.preventDefault();
        if (grecaptcha.getResponse() === '') {
          Swal.fire({
            icon: 'error',
            title: 'Captcha Error',
            text: 'Please complete the captcha.'
          });
        } else {
          $.ajax({
            url: 'Classes/Users.php?f=login',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
              if (resp == 1) {
                location.href = 'index.php';
              } else {
                loginAttempts++;
                if (loginAttempts >= maxAttempts) {
                  startCooldown();
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Login Error',
                    text: 'Invalid username or password.'
                  });
                }
              }
            },
            error: function(err) {
              console.log(err);
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.'
              });
            }
          });
        }
      });

      type();
    });

    function openPushMenu() {
      document.getElementById("push-menu").style.width = "250px";
    }

    function closePushMenu() {
      document.getElementById("push-menu").style.width = "0";
    }
  </script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="images/nha.png" alt="NHA Logo">
      <h4 class="ml-2">NHA Kodia Information System</h4>
    </a>
    <div class="navbar-nav ml-auto">
      <a class="nav-link" href="#" id="login-as-admin">Admin</a>
      <a class="nav-link" href="#" id="login-as-resident">Resident</a>
      <a class="nav-link" href="#" id="login-as-officer">Officer</a>
    </div>
    <span class="open-menu-btn" onclick="openPushMenu()">&#9776;</span>
  </nav>

  <div id="push-menu">
    <a href="javascript:void(0)" class="close-btn" onclick="closePushMenu()">&times;</a>
    <a href="#">Admin</a>
    <a href="#">Resident</a>
    <a href="#">Officer</a>
  </div>

  <div class="container" id="login">
    <div class="card">
      <div class="card-header bg-primary text-white text-center">
        <h4 id="title">Login</h4>
      </div>
      <div class="card-body">
        <form id="login-frm">
          <input type="hidden" id="role" name="role">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
              </div>
              <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-primary text-white"><i class="fas fa-lock"></i></span>
              </div>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group">
            <div class="h-captcha" data-sitekey="f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88"></div>
          </div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary" id="login-btn">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="animated-text" class="animated-text"></div>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<?php require_once('inc/footer.php'); ?>
</html>
