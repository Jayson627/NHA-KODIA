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

        // Validate required fields
        if (!$fullname || !$dob || !$lot_no || !$house_no || !$email || !$username || !$password || !$role) {
            $_SESSION['message'] = "All fields are required.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

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
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <script>
        function validateRecaptcha() {
            const response = grecaptcha.getResponse();
            if (response.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please complete the reCAPTCHA',
                });
                return false;
            }
            return true;
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
        margin-top: 10px;
    }

    .terms {
        margin: 20px 0;
        display: flex;
        align-items: center;
    }

    .terms input {
        margin-right: 10px;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
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

    .button-container {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    /* Responsive design for smaller screens */
    @media screen and (max-width: 480px) {
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"] {
            font-size: 12px;
            padding: 10px;
        }

        button {
            font-size: 14px;
            padding: 10px;
        }

        h2 {
            font-size: 20px;
        }
    }
    </style>
</head>
<body>
    <header>
        <img src="residents.jpg" alt="Logo" class="logo">
        <h1>Resident Portal</h1>
    </header>
    <div class="container">
        <h2 id="form-title">Create Account</h2>
        <form id="account-form" method="POST" onsubmit="return validateRecaptcha();">
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
            <input type="date" id="dob" name="dob" placeholder="Date of Birth" required>
            <input type="text" id="lot_no" name="lot_no" placeholder="Lot Number" required>
            <input type="text" id="house_no" name="house_no" placeholder="House Number" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="hidden" name="role" value="residents">
            <div class="terms">
                <input type="checkbox" id="termsCheckbox" required>
                <label for="termsCheckbox">I agree to the <span id="termsLink" class="toggle-button">Terms & Conditions</span></label>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="h-captcha" data-sitekey="your-site-key"></div> <!-- Add your hCaptcha site key here -->
            <div class="button-container">
                <button type="submit" name="create_account">Create Account</button>
            </div>
        </form>
        <div class="toggle-button" onclick="toggleForm()">Already have an account? Login here</div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Terms & Conditions</h2>
            <p>Terms and conditions content goes here...</p>
            <button id="acceptTermsBtn">I Agree</button>
        </div>
    </div>

    <script>
        function toggleForm() {
            const formTitle = document.getElementById('form-title');
            const accountForm = document.getElementById('account-form');
            if (formTitle.textContent === 'Create Account') {
                formTitle.textContent = 'Login';
                accountForm.innerHTML = `
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="h-captcha" data-sitekey="your-site-key"></div> <!-- Add your hCaptcha site key here -->
                    <div class="button-container">
                        <button type="submit" name="login">Login</button>
                    </div>
                `;
                document.querySelector('.toggle-button').textContent = "Don't have an account? Create one here";
            } else {
                formTitle.textContent = 'Create Account';
                accountForm.innerHTML = `
                    <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
                    <input type="date" id="dob" name="dob" placeholder="Date of Birth" required>
                    <input type="text" id="lot_no" name="lot_no" placeholder="Lot Number" required>
                    <input type="text" id="house_no" name="house_no" placeholder="House Number" required>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <input type="hidden" name="role" value="residents">
                    <div class="terms">
                        <input type="checkbox" id="termsCheckbox" required>
                        <label for="termsCheckbox">I agree to the <span id="termsLink" class="toggle-button">Terms & Conditions</span></label>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="h-captcha" data-sitekey="your-site-key"></div> <!-- Add your hCaptcha site key here -->
                    <div class="button-container">
                        <button type="submit" name="create_account">Create Account</button>
                    </div>
                `;
                document.querySelector('.toggle-button').textContent = "Already have an account? Login here";
            }
        }

        // Modal functionality
        const modal = document.getElementById('termsModal');
        const termsLink = document.getElementById('termsLink');
        const closeBtn = document.querySelector('.close');
        const acceptBtn = document.getElementById('acceptTermsBtn');
        const termsCheckbox = document.getElementById('termsCheckbox');

        termsLink.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        closeBtn.onclick = function() {
            modal.style.display = 'none';
            termsCheckbox.checked = false;
        };

        acceptBtn.onclick = function() {
            modal.style.display = 'none';
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                termsCheckbox.checked = false;
            }
        };

        // Show the message if exists
        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                icon: 'info',
                title: 'Message',
                text: '<?= $_SESSION['message'] ?>'
            });
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
