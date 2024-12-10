<?php
session_start();
include_once('connection.php');

// Define max login attempts and lockout time
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_TIME', 60); // 60 seconds

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle form submission for account creation and login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    if (isset($_POST['create_account'])) {
        // Sanitize and validate input
        $fullname = sanitize_input($_POST['fullname']);
        $dob = sanitize_input($_POST['dob']);
        $lot_no = sanitize_input($_POST['lot_no']);
        $house_no = sanitize_input($_POST['house_no']);
        $email = filter_var(sanitize_input($_POST['email']), FILTER_VALIDATE_EMAIL);
        $username = sanitize_input($_POST['username']);
        $password = sanitize_input($_POST['password']);
        $role = sanitize_input($_POST['role']);
        $id = uniqid();

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_ARGON2I);

        // Insert new user with default 'pending' status and random ID
        $stmt = $conn->prepare("INSERT INTO residents (id, fullname, dob, lot_no, house_no, email, username, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssssssss", $id, $fullname, $dob, $lot_no, $house_no, $email, $username, $hashed_password, $role);

        // Execute and check for success
        if ($stmt->execute()) {
            $_SESSION['message'] = "Account created successfully! Wait for the approval and check your email.";
        } else {
            $_SESSION['message'] = "Error creating account. Please try again.";
        }
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
        exit();
    }

    if (isset($_POST['login'])) {
        // Validate hCaptcha response
        $captcha_response = $_POST['g-recaptcha-response'];
        $secret_key = 'f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88'; // Replace with your secret key
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
        $response_keys = json_decode($response, true);
    
        // If the reCAPTCHA response is invalid, show an error
        if(intval($response_keys["success"]) !== 1) {
            $_SESSION['message'] = "Please complete the CAPTCHA to proceed.";
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect back to the login page
            exit();
        }
    
        // If CAPTCHA is successful, proceed with login validation
        // Collect and sanitize input
        $email = filter_var(sanitize_input($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = sanitize_input($_POST['password']);
    
        // Prepare and execute the statement to get the user, role, and status by email
        $stmt = $conn->prepare("SELECT password, role, status FROM residents WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
    
        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword, $role, $status);
            $stmt->fetch();
    
            // Check if the account status is 'approved'
            if ($status !== 'approved') {
                $_SESSION['message'] = "Your account is not approved yet. Please wait for approval.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
    
            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Reset login attempts on successful login
                $_SESSION['login_attempts'] = 0;
    
                // Convert role to lowercase to avoid case sensitivity issues
                $role = strtolower($role);
    
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
    
                // Check role and redirect accordingly
                if ($role === 'president') {  
                    header("Location: president"); 
                    exit();
                } else if ($role === 'residents') { 
                    header("Location: people_dashboard");
                    exit();
                }
            } else {
                // Increment the login attempts
                if (!isset($_SESSION['login_attempts'])) {
                    $_SESSION['login_attempts'] = 0;
                }
                $_SESSION['login_attempts']++;
    
                // Store the last attempt time
                $_SESSION['last_attempt_time'] = time();
    
                $_SESSION['message'] = "Invalid email or password!";
            }
        } else {
            // Increment the login attempts
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 0;
            }
            $_SESSION['login_attempts']++;
    
            // Store the last attempt time
            $_SESSION['last_attempt_time'] = time();
    
            $_SESSION['message'] = "Invalid email or password!";
        }
    
        $stmt->close();
    }
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account / Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('houses.jpg'); /* Update the path as necessary */
            background-size: cover; /* Ensure the image covers the entire area */
            background-position: center; /* Center the image */
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; /* Stack elements vertically */
            align-items: center;
            height: 100vh;
        }
        header {
            width: 100%;
            display: flex;
            align-items: center; /* Align items vertically center */
            padding: 10px 20px; /* Add some padding */
            background-color: #007BFF; /* Blue background */
            color: white; /* Text color for better contrast */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logo {
            width: 50px; /* Adjust the size as necessary */
            height: 50px; /* Ensure height matches width for a perfect circle */
            border-radius: 50%; /* Make the logo circular */
            margin-right: 15px; /* Space between the logo and any following content */
        }
        
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 35px;
            width: 350px;
            transition: transform 0.3s ease;
            margin-top: 100px; /* Add margin to push it down */
        }
        .container:hover {
            transform: translateY(-5px);
        }
        h2 {
            text-align: center;
            color: #5a67d8;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="password"]:focus {
            border-color: #5a67d8;
            outline: none;
        }
        button {
            background-color: #5a67d8;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #4c51bf;
        }
        .toggle-button {
            text-align: center;
            color: #5a67d8;
            text-decoration: underline;
            cursor: pointer;
            margin-top: 15px;
        }
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
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
<?php if (isset($_SESSION['message'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?php echo $_SESSION['message']; ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php unset($_SESSION['message']); ?>
<?php endif; ?>
<header>
    <img src="lo.png" alt="Logo" class="logo">
    <h1 style="margin: 0;">NHA Kodia-IS</h1>
    <a href="login" style="margin-left: auto; color: white; text-decoration: none; padding: 10px 15px; background-color: transparent; border-radius: 4px;">Home</a>
</header>

<div class="container">
    <h2 id="form-title">Login Portal</h2>
    <div class="form-container" id="create-account">
    <form method="POST" onsubmit="return validateForm()">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="text" name="fullname" placeholder="Full Name" required pattern="^[A-Za-z\s]{3,50}$" title="Full name should only contain letters and be 3-50 characters long">
        <input type="date" name="dob" placeholder="Date of Birth" required max="<?= date('Y-m-d', strtotime('-18 years')) ?>" title="You must be at least 18 years old">
        <input type="text" name="lot_no" placeholder="Lot No" required pattern="^\d{1,10}$" title="Lot number should be numeric and up to 10 digits">
        <input type="text" name="house_no" placeholder="House No" required pattern="^\d{1,4}$" title="House number should contain 1-4 digits">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required pattern="^[A-Za-z0-9_]{5,20}$" title="Username should be alphanumeric, 5-20 characters, and may include underscores">
        
        <!-- Password input with show/hide toggle -->
        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Password" required minlength="8" title="Password must be at least 8 characters">
            <span id="togglePassword" class="eye-icon">&#128065;</span>
        </div>
        
        <select name="role" required style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
            <option value="residents">Residents</option>
            <option value="president">President</option>
        </select>
 <!-- Terms and Conditions Checkbox -->
 <div style="margin: 10px 0;">
    <input type="checkbox" id="terms" name="terms" required>
    <label for="terms">I agree to the <a href="javascript:void(0);" onclick="document.getElementById('termsModal').style.display='block';">Terms and Conditions</a></label>
</div>
        <button type="submit" name="create_account">Create Account</button>
    </form>
    <p class="toggle-button" onclick="toggleForm()">Already have an account? Login here.</p>
</div>
    <div class="form-container active" id="login">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-wrapper">
                <input type="password" id="login_password" name="password" placeholder="Password" required>
                <span id="toggleLoginPassword" class="eye-icon">&#128065;</span>
            </div>
            <button type="submit" name="login">Login</button>
            <div class="g-recaptcha" data-sitekey="f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88"></div>
        </form>
        <p class="toggle-button" onclick="toggleForm()">Don't have an account? Create one here.</p>
        <p class="forgot-password" style="text-align: center; margin-top: 10px;">
            <a href="forgot_password.php" style="color: #5a67d8; text-decoration: underline;">Forgot Password?</a>
        </p>
    </div>
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
<script>

    function toggleForm() {
        const createAccountForm = document.getElementById('create-account');
        const loginForm = document.getElementById('login');
        const formTitle = document.getElementById('form-title');

        if (createAccountForm.classList.contains('active')) {
            createAccountForm.classList.remove('active');
            loginForm.classList.add('active');
            formTitle.textContent = 'Login';
        } else {
            loginForm.classList.remove('active');
            createAccountForm.classList.add('active');
            formTitle.textContent = 'Create Account';
        }
    }

    // Toggle password visibility for account creation
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    togglePassword.addEventListener('click', function (e) {
        // Toggle the password type between text and password
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        // Change the eye icon
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });

    // Toggle password visibility for login
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginPasswordField = document.getElementById('login-password');
    toggleLoginPassword.addEventListener('click', function (e) {
        // Toggle the password type between text and password
        const type = loginPasswordField.type === 'password' ? 'text' : 'password';
        loginPasswordField.type = type;
        // Change the eye icon
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });

    function validateForm() {
        const dob = document.querySelector('input[name="dob"]').value;
        const dobDate = new Date(dob);
        const today = new Date();
        const age = today.getFullYear() - dobDate.getFullYear();
        
        // Check if user is at least 18 years old
        if (age < 18) {
            alert("You must be at least 18 years old to register.");
            return false;
        }
    }

        // Retrieve data from localStorage when loading the page
        window.onload = function() {
            const storedData = JSON.parse(localStorage.getItem('formData'));
            if (storedData) {
                document.getElementById('username').value = storedData.username || '';
                document.getElementById('email').value = storedData.email || '';
            }
        };
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
        

    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['message'])): ?>
            const message = '<?php echo $_SESSION['message']; ?>';
            const isError = message.includes("Invalid") || message.includes("Error") || message.includes("not approved");

            // If the user is locked out
            if (message.includes('Too many login attempts')) {
                const remainingTime = <?php echo isset($_SESSION['last_attempt_time']) ? LOCKOUT_TIME - (time() - $_SESSION['last_attempt_time']) : 0; ?>;

                // Display SweetAlert with a countdown
                Swal.fire({
                    icon: 'error',
                    title: 'Too many login attempts!',
                    html: `Please try again in <b id="countdown">${remainingTime}</b> seconds.`,
                    showConfirmButton: false,
                    timer: remainingTime * 1000, // Set the timer duration in milliseconds
                    willOpen: () => {
                        const countdownElement = document.getElementById('countdown');
                        let countdown = remainingTime;
                        const countdownInterval = setInterval(() => {
                            countdown--;
                            countdownElement.innerText = countdown;
                            if (countdown <= 0) {
                                clearInterval(countdownInterval);
                            }
                        }, 1000);
                    }
                });
            } else {
                Swal.fire({
                    icon: isError ? 'error' : 'success',
                    title: isError ? 'Error' : 'Success',
                    text: message,
                    confirmButtonText: 'OK'
                });
            }
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    });

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

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>


</body>
</html>

