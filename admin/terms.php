<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Kodia NHA Information System</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }
        .terms-container {
            width: 400px;
            background-color: #1c1c1c;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        }
        .terms-container h2 {
            margin-top: 0;
            font-size: 18px;
            color: #ffffff;
            border-bottom: 1px solid #ff4c4c;
            padding-bottom: 10px;
        }
        .terms-container .last-edit {
            font-size: 12px;
            color: #cccccc;
            margin-bottom: 10px;
        }
        .terms-container p {
            font-size: 14px;
            line-height: 1.5;
            color: #cccccc;
        }
        .checkbox-container {
            margin-top: 15px;
            display: flex;
            align-items: center;
            color: #cccccc;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .button-container button {
            width: 48%;
            padding: 10px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #ff4c4c;
            color: #ffffff;
        }
        .decline-btn {
            background-color: #333;
            color: #ffffff;
        }
    </style>
  <script>
        function handleAccept() {
            // Automatically check the checkbox
            document.getElementById('terms-checkbox').checked = true;

            // Save the form data to localStorage (example for a few form fields)
            const formData = {
                username: document.getElementById('username') ? document.getElementById('username').value : '',
                email: document.getElementById('email') ? document.getElementById('email').value : ''
            };
            localStorage.setItem('formData', JSON.stringify(formData));

            // Redirect to the Create Account section in residents.php
            window.location.href = "../admin/residents";  // Correct redirection in JS
        }

        function handleDecline() {
            // If declined, just redirect to the residents.php page
            window.location.href = "../residents";  // Correct redirection in JS
        }

        // Load the form data from localStorage if available
        window.onload = function() {
            const storedData = JSON.parse(localStorage.getItem('formData'));
            if (storedData) {
                document.getElementById('username').value = storedData.username || '';
                document.getElementById('email').value = storedData.email || '';
            }
        };
    </script>
</head>
<body>

<div class="terms-container">
    <h2>TERMS OF SERVICE</h2>
    <div class="last-edit">Last Edit: 11/06/2024</div>
    <p>Welcome to the Kodi NHA Information System. This system is intended for administrators managing the Kodi NHA community information, including household details, block, and lot data.</p>
    <p>By accessing this system, you agree to handle all data with confidentiality and to use the information solely for administrative purposes. Unauthorized access, data sharing, or modification without permission may result in disciplinary action.</p>
    <p>Ensure that all actions taken comply with the privacy policies and data protection regulations governing community information management. The system logs all activities for security and auditing purposes.</p>
    
    <!-- Terms Checkbox -->
    <div class="checkbox-container">
        <input type="checkbox" id="terms-checkbox">
        <label for="terms-checkbox">I agree to the terms of service</label>
    </div>

    <div class="button-container">
        <button class="accept-btn" onclick="handleAccept()">Accept</button>
        <button class="decline-btn" onclick="handleDecline()">Decline</button>
    </div>
</div>

</body>
</html>
