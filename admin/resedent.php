<!DOCTYPE html>
<html>
<head>
    <title>Registration and Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
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
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <div class="form-container active" id="registration-form">
            <form action="/submit_registration" method="post">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>

                <label for="lot_no">Lot No:</label>
                <input type="text" id="lot_no" name="lot_no" required>

                <label for="house_no">House No:</label>
                <input type="text" id="house_no" name="house_no" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="resident">Residents</option>
                    <option value="president">President</option>
                </select>

                <input type="submit" value="Register">
            </form>
            <div class="toggle-link" onclick="toggleForms()">Already have an account? Login here</div>
        </div>

      
        <div class="form-container" id="login-form">
            <form action="/submit_login" method="post">
                <label for="login_email">Email:</label>
                <input type="email" id="login_email" name="login_email" required>

                <label for="login_password">Password:</label>
                <input type="password" id="login_password" name="login_password" required>

                <input type="submit" value="Login">
            </form>
            <div class="toggle-link" onclick="toggleForms()">Don't have an account? Register here</div>
        </div>
    </div>

    <script>
        function toggleForms() {
            const registrationForm = document.getElementById('registration-form');
            const loginForm = document.getElementById('login-form');
            registrationForm.classList.toggle('active');
            loginForm.classList.toggle('active');
        }
        function toggleForm() {
        const registratinForm = document.getElementById('registration-account');
        const loginForm = document.getElementById('login');
        const formTitle = document.getElementById('form-title');

        if (registrationForm.classList.contains('active')) {
            registrationForm.classList.remove('active');
            loginForm.classList.add('active');
            formTitle.textContent = 'Login';
        } else {
            loginForm.classList.remove('active');
            registrationtForm.classList.add('active');
            formTitle.textContent = 'Create Account';
        }
    }
    </script>
</body>
</html>
