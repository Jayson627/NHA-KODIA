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
        flex-direction: column;
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
        border-radius: 50%;
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
        background-color: white;
        padding: 0;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 400px;
        margin: 120px auto;
        text-align: center;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 15px;
        font-size: 24px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .login-container label {
        display: block;
        margin-bottom: 10px;
        color: black; /* Changed to dark for contrast */
        font-weight: bold;
    }

    .login-container input[type="text"] {
        width: 90%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid lightblue;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .login-container button {
        width: 50%;
        padding: 12px;
        background-color: blue;
        border: none;
        border-radius: 20px;
        color: white;
        font-size: 15px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-container button:hover {
        background-color: lightblue;
    }

    /* Responsive Styles */
    @media (max-width: 600px) {
        header {
            font-size: 20px;
        }

        .login-container {
            margin: 80px auto;
            width: 95%;
        }

        .login-container button {
            width: 80%;
        }

        .card-header {
            font-size: 20px;
        }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <div class="logo">
        <img src="lo.png" alt="Logo">
        NHA Resident Login Portal
    </div>
    <a href="about.php">Home</a>
</header>

<div class="login-container">
    <div class="card-header">
        Resident Login
    </div>
    <form id="resident-form" action="process_resident.php" method="post" style="padding: 20px;">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="block-number">Block Number</label>
        <input type="text" id="block-number" name="block_number" placeholder="Enter block number" required>
        
        <label for="lot-number">Lot Number</label>
        <input type="text" id="lot-number" name="lot_number" placeholder="Enter lot number" required>
        
        <button type="submit">Submit</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $('#resident-form').on('submit', function(event) {
            event.preventDefault(); 

            var blockNumber = $('#block-number').val();
            var lotNumber = $('#lot-number').val();

            // Validate input
            function validateInput(value) {
                const regex = /^[0-9]+$/; 
                return regex.test(value);
            }

            if (!validateInput(blockNumber) || !validateInput(lotNumber)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Input',
                    text: 'Block and lot numbers must be numeric.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $.ajax({
                url: 'check_block_lot.php',
                method: 'POST',
                data: { 
                    block_number: blockNumber, 
                    lot_number: lotNumber,
                    csrf_token: $('input[name="csrf_token"]').val() // Include CSRF token
                },
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
                                $('#resident-form')[0].submit();
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
