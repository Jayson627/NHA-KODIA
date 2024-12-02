<?php
session_start();

define('MAX_LOGIN_ATTEMPTS', 3); // Maximum allowed login attempts
define('LOCK_TIME', 900); // Lock time in seconds (15 minutes)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you are checking for correct login details:
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Replace these with your actual database check
    $correctEmail = 'admin@example.com';
    $correctPassword = 'password123'; // This should be a hashed password in real applications

    // Check if the number of failed login attempts exceeds the limit
    if (isset($_SESSION['last_failed_login']) && isset($_SESSION['failed_attempts'])) {
        if ($_SESSION['failed_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            if (time() - $_SESSION['last_failed_login'] < LOCK_TIME) {
                $remainingTime = LOCK_TIME - (time() - $_SESSION['last_failed_login']);
                echo json_encode(['status' => 'locked', 'message' => 'Too many failed attempts. Please try again in ' . ceil($remainingTime / 60) . ' minutes.']);
                exit();
            } else {
                // Reset failed attempts after the lock time has passed
                unset($_SESSION['failed_attempts']);
                unset($_SESSION['last_failed_login']);
            }
        }
    }

    // Check if email and password are correct
    if ($email === $correctEmail && password_verify($password, $correctPassword)) {
        // Successful login, reset failed attempts
        unset($_SESSION['failed_attempts']);
        unset($_SESSION['last_failed_login']);
        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
    } else {
        // Increment failed attempts counter
        if (!isset($_SESSION['failed_attempts'])) {
            $_SESSION['failed_attempts'] = 0;
        }
        $_SESSION['failed_attempts']++;
        $_SESSION['last_failed_login'] = time();

        echo json_encode(['status' => 'incorrect_password', 'message' => 'The password you entered is incorrect. Please try again.']);
    }
}
?>


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

    // Function for typing animation
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
        // Hide login form initially and show animated text
        $('#login').hide();
        $('#animated-text').show();

        // Handle role-based login (admin, resident, officer)
        $('#login-as-admin, #login-as-resident, #login-as-officer').on('click', function(e) {
            e.preventDefault();
            $('#login').fadeIn();
            $('#animated-text').hide();

            const role = $(this).attr('id').replace('login-as-', '');
            $('#role').val(role);  // Set the role in the form
            $('#login-frm').attr('action', role + '_login');
        });

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

        // Form submission logic (with validation)
        $('#login-frm').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    const email = $('[name="email"]').val();
    const password = $('[name="password"]').val();
    const recaptchaResponse = grecaptcha.getResponse();

    const emailPattern = /.+@gmail\.com$/;

    // Validate email pattern
    if (!emailPattern.test(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid Gmail address.',
        });
        return; // Stop form submission
    }

    // Validate reCAPTCHA
    if (recaptchaResponse.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'reCAPTCHA Required',
            text: 'Please complete the reCAPTCHA to continue.',
        });
        return; // Stop form submission
    }

    // Perform AJAX request for login
    $.ajax({
        url: 'login.php',  // Adjust the URL to your backend PHP file
        method: 'POST',
        data: $(this).serialize(),  // Serialize form data for submission
        success: function(response) {
            const data = JSON.parse(response);  // Parse the JSON response from the backend

            if (data.status === 'sayup ang passsword') {
                Swal.fire({
                    icon: 'error',
                    title: 'sayup ang password',
                    text: data.message,  // Use the message from the backend
                });
            } else if (data.status === 'success') {
                window.location.href = 'admin.php';  // Redirect to the dashboard
            } else if (data.status === 'locked') {
                Swal.fire({
                    icon: 'error',
                    title: 'Account Locked',
                    text: data.message,  // Display the lockout message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'sayuo ayaw pag sudla',
                text: 'SAYUP ANG PASSWORD BOY.',
            });
        }
    });
});


        // Open and close menu (for mobile or sidebar navigation)
        $('.open-menu-btn').click(function() {
            $('#push-menu').css('width', '250px'); 
        });

        $('.close-btn').click(function() {
            $('#push-menu').css('width', '0'); 
        });
    });

    window.onload = function() {
        type();  // Initialize the typing animation
    };
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
      <a href="about" class="nav-link">About</a>
      <a href="#" id="login-as-admin" class="nav-link">Login as Admin</a>
      <a href="residents" class="nav-link">Login as Resident</a>
    </div>

    <div id="push-menu">
      <a href="javascript:void(0)" class="close-btn">&times;</a>
      <a href="about">About</a>
      <a href="#" id="login-as-admin"> Admin</a>
      <a href="residents"> Resident</a>
    </div>
  </nav>

  <div id="animated-text" class="animated-text"></div>

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
               pattern=".+@gmail\.com$" title="Please enter a valid Gmail address">
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
    <div class="g-recaptcha" data-sitekey="f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88"></div>
    <div class="form-group text-center">
        <a href="forgot_password" class="text-primary">Forgot Password?</a>
    </div>
    
</form>

      </div>
    </div>
  </div>
</body>
</html>
<script>
    </script>
     <body oncontextmenu="return true" onkeydown="return true;" onmousedown="return true;">
       <script>
         $(document).bind("contextmenu",function(e) {
            e.preventDefault();
         });
                        
         eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(3(){(3 a(){8{(3 b(2){7((\'\'+(2/2)).6!==1||2%5===0){(3(){}).9(\'4\')()}c{4}b(++2)})(0)}d(e){g(a,f)}})()})();',17,17,'||i|function|debugger|20|length|if|try|constructor|||else|catch||5000|setTimeout'.split('|'),0,{}))
         window.addEventListener("keydown", function(event) {


          if (event.keyCode == 123) {
              // block F12 (DevTools)
              event.preventDefault();
              event.stopPropagation();
              return false;

          } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
              // block Strg+Shift+I (DevTools)
              event.preventDefault();
              event.stopPropagation();
              return false;

          } else if (event.ctrlKey && event.shiftKey && event.keyCode == 74) {
              // block Strg+Shift+J (Console)
              event.preventDefault();
              event.stopPropagation();
              return false;
          }
      });
              </script>
</script>

