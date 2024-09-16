<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resident Login</title>
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Custom CSS -->
  <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('nha.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        header {
            background-color: #007bff;
            width: 100%;
            padding: 5px;
            text-align: left;
            color: #ffffff;
            font-size: 24px;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            display: flex;
            align-items: center;
        }
        header img {
            height: 50px;
            width: 50px;
            border-radius: 50%; /* Make the logo circular */
            margin-right: 10px;
        }
        header a {
            color: #ffffff;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
        }
        header a:hover {
            text-decoration: underline;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }
        .login-container label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }
        .login-container input[type="text"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 3px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 3px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            margin-top: 15px;
            background-color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
</head>
<body>

<header>
    <div class="logo">
        <img src="lo.png" alt="Logo"> <!-- Replace 'logo.png' with the path to your logo -->
        NHA Resident Login Portal
    </div>
    <a href="about.php">Home</a> <!-- Logout link aligned to the right -->
</header>
    </style>
</head>
<body>

<div class="login-container">
<h2>Resident Login</h2>
    <form id="president-form" action="process_resident.php" method="post">
      <div class="form-group">
        <label for="block-number">Block Number</label>
        <input type="text" class="form-control" id="block-number" name="block_number" placeholder="Enter block number" required>
      </div>
      <div class="form-group">
        <label for="lot-number">Lot Number</label>
        <input type="text" class="form-control" id="lot-number" name="lot_number" placeholder="Enter lot number" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Submit</button>
    </form>
  </div>
 

  <!-- Bootstrap JS and dependencies -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script>
    $(document).ready(function() {
  $('#president-form').on('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var blockNumber = $('#block-number').val();
    var lotNumber = $('#lot-number').val();

    // Check if the block number and lot number are valid
    $.ajax({
      url: 'check_block_lot.php',
      method: 'POST',
      data: { block_number: blockNumber, lot_number: lotNumber },
      dataType: 'json',
      success: function(response) {
        if (response.valid) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'You have successfully logged in!',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              // Proceed with the actual form submission
              $('#president-form')[0].submit();
            }
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Login',
            text: 'Incorrect block or lot number.',
            confirmButtonText: 'OK'
          });
        }
      },
      error: function(err) {
        console.log(err);
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'An error occurred during login.',
          confirmButtonText: 'OK'
        });
      }
    });
  });
});

  </script>
</body>
</html>