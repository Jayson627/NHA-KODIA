<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";  // Change as necessary
$password = "";      // Change as necessary
$dbname = "sis_db";  // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for account creation and login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_account'])) {
        $fullname = $_POST['fullname'];
        $dob = $_POST['dob'];
        $lot_no = $_POST['lot_no'];
        $house_no = $_POST['house_no'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
        $role = $_POST['role']; // Use the selected role from form
    
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO residents (fullname, dob, lot_no, house_no, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $fullname, $dob, $lot_no, $house_no, $email, $username, $password, $role);
    
        // Execute and check for success
        if ($stmt->execute()) {
            $_SESSION['message'] = "Account created successfully!";
        } else {
            $_SESSION['message'] = "Error creating account. Please try again.";
        }
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
        exit();
    } if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Prepare and execute the statement to get the user and their role
        $stmt = $conn->prepare("SELECT password, role FROM residents WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
    
        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword, $role);
            $stmt->fetch();
    
            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Convert role to lowercase to avoid case sensitivity issues
                $role = strtolower($role);
    
                // Check role and redirect accordingly
                if ($role === 'president') {  
                    header("Location: dashboard.php"); 
                    exit();
                } else if ($role === 'residents') { 
                    header("Location: people_dashboard.php");
                    exit();
                }
            } else {
                $_SESSION['message'] = "Invalid username or password!";
            }
        } else {
            $_SESSION['message'] = "Invalid username or password!";
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
    </style>
</head>
<body>
<header>
    <img src="lo.png" alt="Logo" class="logo">
    <h1 style="margin: 0;">NHA Kodia Information System</h1>
    <a href="login.php" style="margin-left: auto; color: white; text-decoration: none; padding: 10px 15px; background-color: transparent; border-radius: 4px;">Home</a>
</header>

<div class="container">
    <h2 id="form-title">Login Portal</h2>
    <div class="form-container" id="create-account">
    <form method="POST" onsubmit="return validateForm()">
        <input type="text" name="fullname" placeholder="Full Name" required pattern="^[A-Za-z\s]{3,50}$" title="Full name should only contain letters and be 3-50 characters long">
        <input type="date" name="dob" placeholder="Date of Birth" required max="<?= date('Y-m-d', strtotime('-18 years')) ?>" title="You must be at least 18 years old">
        <input type="text" name="lot_no" placeholder="Lot No" required pattern="^[A-Za-z0-9]{1,10}$" title="Lot number should be alphanumeric and up to 10 characters">
        <input type="text" name="house_no" placeholder="House No" required pattern="^\d{1,4}$" title="House number should contain 1-4 digits">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required pattern="^[A-Za-z0-9_]{5,20}$" title="Username should be alphanumeric, 5-20 characters, and may include underscores">
        <input type="password" name="password" placeholder="Password" required minlength="8" title="Password must be at least 8 characters">
        <select name="role" required style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
            <option value="residents">Residents</option>
            <option value="president">President</option>
        </select>

        <!-- Terms and Conditions Checkbox -->
        <div style="margin: 10px 0;">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">I agree to the <a href="terms.php" target="_blank">Terms and Conditions</a></label>
        </div>

        <button type="submit" name="create_account">Create Account</button>
    </form>
    <p class="toggle-button" onclick="toggleForm()">Already have an account? Login here.</p>
</div>
    <div class="form-container active" id="login">
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required minlength="8">
            <button type="submit" name="login">Login</button>
        </form>
        <p class="toggle-button" onclick="toggleForm()">Don't have an account? Create one here.</p>
        <p class="forgot-password" style="text-align: center; margin-top: 10px;">
            <a href="forgot_password.php" style="color: #5a67d8; text-decoration: underline;">Forgot Password?</a>
        </p>
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

        // Retrieve data from localStorage when loading the page
    window.onload = function() {
        const storedData = JSON.parse(localStorage.getItem('formData'));
        if (storedData) {
            document.getElementById('username').value = storedData.username || '';
            document.getElementById('email').value = storedData.email || '';
        }
    };

        // Check if terms and conditions checkbox is checked
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            alert("You must agree to the Terms and Conditions to create an account.");
            return false;
        }

        return true;
    }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['message'])): ?>
                Swal.fire({
                    icon: '<?php echo (strpos($_SESSION['message'], "Error") !== false || strpos($_SESSION['message'], "Invalid") !== false) ? "error" : "success"; ?>',
                    title: '<?php echo (strpos($_SESSION['message'], "Error") !== false || strpos($_SESSION['message'], "Invalid") !== false) ? "Error" : "Success"; ?>',
                    text: '<?php echo $_SESSION['message']; ?>',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        });
    </script>

</body>
</html>