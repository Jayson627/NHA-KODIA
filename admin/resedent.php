<!DOCTYPE html>
<html>
<head>
    <title>Registration and Login Form</title>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show SweetAlert function
        function showMessage(message, type) {
            Swal.fire({
                icon: type,
                title: message,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('houses.jpg'); /* Update the path as necessary */
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        header {
            width: 100%;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
       
        .container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 90%; /* Use percentage for better responsiveness */
        max-width: 400px; /* Set a maximum width */
        transition: transform 0.3s ease;
        margin-top: 20px;
    }

        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            position: relative;
        }
        .password-container {
            position: relative;
            width: 100%;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
        }
        
   
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .toggle-link {
            text-align: center;
            color: #007bff;
            cursor: pointer;
        }
        .toggle-link:hover {
            text-decoration: underline;
        }
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scrolling if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .accept-button {
            background-color: #5a67d8;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .accept-button:hover {
            background-color: #4c51bf;
        }
    </style>
</head>
<body>
<header>
    <img src="lo.png" alt="Logo" class="logo">
    <h1 style="margin: 0;">NHA Kodia-IS</h1>
    <a href="login" style="margin-left: auto; color: white; text-decoration: none; padding: 10px 15px; background-color: transparent; border-radius: 4px;">Home</a>
</header>

    <div class="container">
        <div class="form-container active" id="registration-form">
            <h2>Registration Form</h2>
            <form action="../admin/process_form" method="post" onsubmit="return validateRegistrationForm()">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF Token -->
    <input type="hidden" name="action" value="register">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" pattern="[A-Za-z\s]+" title="Full name should only contain alphabets and spaces" required>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" max="<?= date('Y-m-d', strtotime('-18 years')); ?>" required>

    <label for="lot_no">Lot No:</label>
<input type="text" id="lot_no" name="lot_no" pattern="^\d+$" title="Lot number should only contain numbers." required>

<label for="house_no">House No:</label>
<input type="text" id="house_no" name="house_no" pattern="^\d+$" title="House number should only contain numbers." required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <div class="password-container">
        <input type="password" id="password" name="password" minlength="8" pattern=".*[A-Z].*" title="Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character." required>
        <span class="toggle-password" id="togglePassword1" onclick="togglePasswordVisibility('password', 'togglePassword1')">👁️</span>
    </div>

    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="resident">Resident</option>
        <option value="president">President</option>
    </select>

    <!-- Terms and Conditions Checkbox -->
    <div style="margin: 10px 0;">
        <input type="checkbox" id="terms" name="terms" required>
        <label for="terms">I agree to the <a href="javascript:void(0);" onclick="document.getElementById('termsModal').style.display='block';">Terms and Conditions</a></label>
    </div>

    <input type="submit" value="Register">
</form>

            <div class="toggle-link" onclick="toggleForms()">Already have an account? Login here</div>
        </div>

        <div class="form-container" id="login-form">
            <h2>Login</h2>
            <form action="../admin/process_form" method="post">
                <input type="hidden" name="action" value="login">
                <label for="login_email">Email:</label>
                <input type="email" id="login_email" name="login_email" required>
                <label for="login_password">Password:</label>
            <div class="password-container">
                <input type="password" id="login_password" name="login_password" required>
                <span class="toggle-password" id="togglePassword2" onclick="togglePasswordVisibility('login_password', 'togglePassword2')">👁️</span>
            </div>
                <div class="g-recaptcha" data-sitekey="f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88"></div>
                <input type="submit" value="Login">
            </form>
            <div class="toggle-link" onclick="toggleForms()">Don't have an account? Register here</div>
            <p class="forgot-password" style="text-align: center; margin-top: 10px;">
            <a href="forget_password" style="color: #5a67d8; text-decoration: underline;">Forgot Password?</a>
        </p>
        </div>
   <!-- Modal for Terms and Conditions -->
   <div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Terms and Conditions</h2>
        <p>Welcome to the Kodi NHA Information System. This system is intended for administrators managing the Kodi NHA community information, including household details, block, and lot data.</p>
    <p>By accessing this system, you agree to handle all data with confidentiality and to use the information solely for administrative purposes. Unauthorized access, data sharing, or modification without permission may result in disciplinary action.</p>
    <p>Ensure that all actions taken comply with the privacy policies and data protection regulations governing community information management. The system logs all activities for security and auditing purposes.</p>
        <button id="acceptTerms" class="accept-button">I Agree</button>
    </div>
</div>

    <?php if (isset($_GET['message'])): ?>
        <script>
            showMessage("<?php echo htmlspecialchars($_GET['message']); ?>", "<?php echo htmlspecialchars($_GET['type']); ?>");
        </script>
    <?php endif; ?>

    <script>
 // Get the modal
 var modal = document.getElementById("termsModal");

// Get the checkbox
var termsCheckbox = document.getElementById("terms");

// Get the <span> element that closes the modal
var closeBtn = document.getElementsByClassName("close")[0];

// Get the "I Agree" button
var acceptBtn = document.getElementById("acceptTerms");

// When the user clicks the checkbox, open the modal
termsCheckbox.addEventListener('change', function() {
    if (termsCheckbox.checked) {
        modal.style.display = "block";
    }
});

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
    termsCheckbox.checked = false; // Uncheck the checkbox when modal is closed
}

// When the user clicks the "I Agree" button, close the modal
acceptBtn.onclick = function() {
    modal.style.display = "none";
    termsCheckbox.checked = true; // Ensure the checkbox is checked
}

// Close the modal if the user clicks anywhere outside the modal content
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        termsCheckbox.checked = false;
    }
}

        // Toggle password visibility
        function togglePasswordVisibility(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                icon.textContent = '👁️';
            }
        }
         // Disable right-click context menu
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });

    // Disable certain keyboard shortcuts for inspecting the page
    document.addEventListener('keydown', function (e) {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'C' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
        }
    });
        </script>
        <script>
        function toggleForms() {
            const registrationForm = document.getElementById('registration-form');
            const loginForm = document.getElementById('login-form');
            registrationForm.classList.toggle('active');
            loginForm.classList.toggle('active');
        }

        
        function validateRegistrationForm() {
    var fullName = document.getElementById('fullname').value;
    var dob = new Date(document.getElementById('dob').value);
    var password = document.getElementById('password').value;
    var terms = document.getElementById('terms').checked;

    // Validate full name
    if (!/^[A-Za-z\s]+$/.test(fullName)) {
        showMessage("Full name should only contain alphabets and spaces.", "error");
        return false;
    }

    // Validate Date of Birth (ensure age >= 18)
    var age = new Date().getFullYear() - dob.getFullYear();
    if (age < 18) {
        showMessage("You must be at least 18 years old to register.", "error");
        return false;
    }

    // Validate password
    if (!/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])/.test(password)) {
        showMessage("Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.", "error");
        return false;
    }

    // Validate terms checkbox
    if (!terms) {
        showMessage("You must agree to the terms and conditions.", "error");
        return false;
    }

    return true; // All validations passed
}

function showMessage(message, type) {
    Swal.fire({
        icon: type,
        title: message,
        showConfirmButton: true,
        confirmButtonText: 'OK'
    });
}

    </script>
</body>
</html>
