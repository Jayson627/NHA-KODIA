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

    $(document).ready(function() {
      const maxAttempts = 3; // Maximum failed attempts
      const lockoutTime = 50000; // Lockout time in milliseconds (50 seconds)
      const lastAttemptTime = localStorage.getItem('lastAttemptTime');
      const attemptCount = parseInt(localStorage.getItem('attemptCount')) || 0;

      // Check if the user is locked out from previous failed attempts
      if (lastAttemptTime && (Date.now() - lastAttemptTime) < lockoutTime) {
        const remainingTime = lockoutTime - (Date.now() - lastAttemptTime);
        $('#login-frm').find('button').prop('disabled', true);
        $('#login-frm').prepend('<div class="alert alert-warning">You have been locked out. Please try again in ' + Math.ceil(remainingTime / 1000) + ' seconds.</div>');

        setTimeout(function() {
          $('#login-frm').find('button').prop('disabled', false);
          localStorage.removeItem('attemptCount');
          localStorage.removeItem('lastAttemptTime');
          $('#login-frm').find('.alert').remove();
        }, remainingTime);
        return;
      }

      $('#login').hide();
      $('#animated-text').show();

      $('#login-as-admin, #login-as-resident, #login-as-officer').on('click', function(e) {
        e.preventDefault();
        var role = $(this).data('role');
        $('#role').val(role);
        $('#login').show();
        $('#animated-text').hide();
      });

      $('#login-frm').on('submit', function(e) {
        const email = $('[name="email"]').val();
        const password = $('[name="password"]').val();

        const emailPattern = /.+@gmail\.com$/;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!emailPattern.test(email)) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid Gmail address.',
          });
          incrementFailedAttempts();
        } else if (!passwordPattern.test(password)) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Invalid Password',
            text: 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.',
          });
          incrementFailedAttempts();
        } else {
          localStorage.removeItem('attemptCount');
          localStorage.removeItem('lastAttemptTime');
        }
      });

      function incrementFailedAttempts() {
        let attempts = parseInt(localStorage.getItem('attemptCount')) || 0;
        attempts += 1;

        if (attempts >= maxAttempts) {
          localStorage.setItem('lastAttemptTime', Date.now());
          Swal.fire({
            icon: 'error',
            title: 'Too Many Attempts',
            text: 'You have reached the maximum number of login attempts. Please try again after 50 seconds.',
          });
        }

        localStorage.setItem('attemptCount', attempts);
      }

      type(); // Start the typing animation when page loads
    });
  </script>
</head>
<body>
  <!-- Push Menu -->
  <div id="push-menu">
    <a href="javascript:void(0)" class="close-btn">&times;</a>
    <a href="#" id="login-as-admin" data-role="admin">Login as Admin</a>
    <a href="#" id="login-as-resident" data-role="resident">Login as Resident</a>
    <a href="#" id="login-as-officer" data-role="officer">Login as Officer</a>
  </div>

  <!-- Animated Text -->
  <div class="animated-text" id="animated-text"></div>

  <!-- Login Form -->
  <div id="login">
    <div class="card">
      <div class="card-header">
        <h4>Login</h4>
      </div>
      <div class="card-body">
        <form id="login-frm">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
