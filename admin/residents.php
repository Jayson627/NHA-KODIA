<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Residents Login</title>
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
        .login-container input[type="text"],
        .login-container input[type="password"] {
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
    </style>
</head>
<body>

<div class="login-container">
<h2>Resident Login</h2>
    <form id="resident-form" action="process_resident.php" method="post">
      <div class="form-group">
        <label for="house-number">House Number</label>
        <input type="text" class="form-control" id="house-number" name="house_number" placeholder="Enter house number" required pattern="\d{1,3}" title="Please enter a valid house number (1 to 3 digits).">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Submit</button>
      <div id="form-message" class="alert alert-info mt-3" role="alert" style="display: none;">
        Your submission has been received!
      </div>
    </form>
  </div>
 

  <!-- Bootstrap JS and dependencies -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#resident-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var houseNumber = $('#house-number').val();

        // Check if house number exists
        $.ajax({
          url: 'check_house_number.php',
          method: 'POST',
          data: { house_number: houseNumber },
          dataType: 'json',
          success: function(response) {
            if (response.exists) {
              Swal.fire({
                icon: 'warning',
                title: 'House Number not in the list',
                text: 'The house number is not  in the records.',
                confirmButtonText: 'OK'
              });
            } else {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Your submission has been received!',
                confirmButtonText: 'OK'
              }).then((result) => {
                if (result.isConfirmed) {
                  // Proceed with the actual form submission
                  $('#resident-form')[0].submit();
                }
              });
            }
          },
          error: function(err) {
            console.log(err);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'An error occurred while checking the house number.',
              confirmButtonText: 'OK'
            });
          }
        });
      });

      // Restrict input to numbers only and limit to 3 digits
      $('#house-number').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, ''); // Remove non-numeric characters
        if (value.length > 3) {
          value = value.slice(0, 3); // Limit to 3 digits
        }
        $(this).val(value);
      });
    });
  </script>
</body>
</html>
