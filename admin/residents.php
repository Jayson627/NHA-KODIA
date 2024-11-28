<?php
session_start();

include_once('connection.php');

// Define max login attempts and lockout time
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_TIME', 60); // 60 seconds

// Handle form submission for account creation and login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_account'])) {
        // Account creation code (no changes here)
        $fullname = $_POST['fullname'];
        $dob = $_POST['dob'];
        $lot_no = $_POST['lot_no'];
        $house_no = $_POST['house_no'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
        $role = $_POST['role']; // Use the selected role from form
    
       // Insert new user with default 'pending' status
$stmt = $conn->prepare("INSERT INTO residents (fullname, dob, lot_no, house_no, email, username, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param("ssssssss", $fullname, $dob, $lot_no, $house_no, $email, $username, $password, $role);

    
        // Execute and check for success
        if ($stmt->execute()) {
            $_SESSION['message'] = "Account created successfully! Wait for the approval check your email";
        } else {
            $_SESSION['message'] = "Error creating account. Please try again.";
        }
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
        exit();
    } // Handle login request
    if (isset($_POST['login'])) {
        // Check if the user is locked out
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            // Check if lockout time has passed
            if (isset($_SESSION['last_attempt_time']) && (time() - $_SESSION['last_attempt_time']) < LOCKOUT_TIME) {
                $remaining_time = LOCKOUT_TIME - (time() - $_SESSION['last_attempt_time']);
                $_SESSION['message'] = "Too many login attempts. Please try again in " . $remaining_time . " seconds.";
                header("Location: " . $_SERVER['PHP_SELF']); 
                exit();
            } else {
                // Reset the login attempts after lockout time has passed
                $_SESSION['login_attempts'] = 0;
            }
        }
    
        // Collect the email and password
        $email = $_POST['email'];
        $password = $_POST['password'];
        
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
        color: #5a67d8;
        margin-bottom: 20px;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="password"] {
        width: 93%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 2px;
        font-size: 14px;
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
 /* Modal styling */
 .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    .text-button {
        background: none;
        border: none;
        color: #007BFF;
        text-decoration: underline;
        cursor: pointer;
        font-size: 14px;
        padding: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        body {
            padding: 0 15px;
            height: auto; /* Adjust height for scrollable content */
        }

        .container {
            margin-top: 10px;
        }

        header {
            flex-direction: column; /* Stack logo and title vertically */
            align-items: center;
            text-align: center;
        }

        .logo {
            margin-right: 0; /* Center align logo */
            margin-bottom: 10px; /* Add space below logo */
        }

        h1 {
            font-size: 18px; /* Smaller font size for title */
        }

        .container {
            padding: 15px;
        }

        button {
            font-size: 14px; /* Slightly smaller font for buttons */
        }
    }

    @media (max-width: 480px) {
        header {
            padding: 10px;
        }

        h1 {
            font-size: 16px;
        }

        .container {
            padding: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"] {
            font-size: 12px; /* Smaller input font size for small screens */
        }

        button {
            padding: 10px;
            font-size: 14px;
        }
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
    <h2 id="form-title">Login Portal</h2>
    <div class="form-container" id="create-account">
    <form method="POST" onsubmit="return validateForm()">
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
        <div>
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <button type="button" id="terms-conditions-link" class="text-button">Terms and Conditions</button></label>
            </div>


        <button type="submit" name="create_account">Create Account</button>
    </form>
    <p class="toggle-button" onclick="toggleForm()">Already have an account? Login here.</p>
</div>
    <div class="form-container active" id="login">
        <form method="POST">
            <input type="email" name="email" placeholder="email" required>
            
            <!-- Password input with show/hide toggle -->
            <div class="password-wrapper">
                <input type="password" id="login-password" name="password" placeholder="Password" required minlength="8">
                <span id="toggleLoginPassword" class="eye-icon">&#128065;</span>
            </div>
            
            <button type="submit" name="login">Login</button>
            <div class="g-recaptcha" data-sitekey="f3c4c8ea-07aa-4b9e-9c6e-510ab3703f88"></div>
        </form>
        <p class="toggle-button" onclick="toggleForm()">Don't have an account? Create one here.</p>
        <p class="forgot-password" style="text-align: center; margin-top: 10px;">
            <a href="forgot_password" style="color: #5a67d8; text-decoration: underline;">Forgot Password?</a>
        </p>
    </div>
    <!-- Terms and Conditions Modal -->
<div id="terms-conditions-modal" class="modal">
    <div class="modal-content">
        <span class="close" id="close-modal">&times;</span>
        <h2>Terms and Conditions</h2>
        <p>By creating an account, you agree to the following terms and conditions:</p>
        <ul>
            <li>You will provide accurate and truthful information.</li>
            <li>You agree to comply with all community rules and guidelines.</li>
            <li>Your account may be suspended or terminated if you violate any terms.</li>
            <li>The community management has the right to approve or reject any account registration.</li>
        </ul>
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
        document.getElementById('terms-conditions-link').addEventListener('click', function() {
    document.getElementById('terms-conditions-modal').style.display = 'block';
});

document.getElementById('close-modal').addEventListener('click', function() {
    document.getElementById('terms-conditions-modal').style.display = 'none';
});

window.onclick = function(event) {
    if (event.target == document.getElementById('terms-conditions-modal')) {
        document.getElementById('terms-conditions-modal').style.display = 'none';
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
    function showTerms() {
        var modal = document.getElementById('termsModal');
        modal.style.display = "block";
    }

    function closeTerms() {
        var modal = document.getElementById('termsModal');
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById('termsModal');
        if (event.target == modal) {
            modal.style.display = "none";
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


document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'C' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
        e.preventDefault();
    }
});



    </script>

</body>
</html>
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>




