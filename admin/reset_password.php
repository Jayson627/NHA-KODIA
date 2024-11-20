<!-- Trigger/Open The Modal -->
<button id="forgotPasswordBtn">Forgot Password?</button>

<!-- The Modal -->
<div id="resetPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reset Password</h2>

        <!-- Form for Email -->
        <div id="emailForm">
            <form method="POST" action="path-to-your-php-script.php" id="forgotPasswordForm">
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                <button type="submit" name="btn-forgotpass">Send OTP</button>
            </form>
        </div>

        <!-- Form for OTP and New Password -->
        <div id="otpForm" style="display:none;">
            <form method="POST" action="path-to-your-php-script.php" id="resetPasswordForm">
                <input type="email" name="email" id="otpEmail" placeholder="Enter your email" hidden>
                <input type="text" name="otp" id="otp" placeholder="Enter OTP" required>
                <input type="password" name="password" id="newPassword" placeholder="Enter new password" required>
                <button type="submit" name="btn-new-password">Reset Password</button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    // Get modal element
    var modal = document.getElementById("resetPasswordModal");
    var btn = document.getElementById("forgotPasswordBtn");
    var span = document.getElementsByClassName("close")[0];

    // Open modal when button is clicked
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Close modal when the user clicks on <span> (x)
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal if the user clicks anywhere outside of the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Handle OTP and password reset form visibility
    document.getElementById("forgotPasswordForm").onsubmit = function(e) {
        e.preventDefault();

        var email = document.getElementById("email").value;
        
        // Send email to backend (your PHP script) to send OTP
        var formData = new FormData();
        formData.append('email', email);
        formData.append('btn-forgotpass', 'Send OTP');
        
        // Send email request via AJAX
        fetch('path-to-your-php-script.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // If OTP was sent successfully, show OTP form
            document.getElementById("emailForm").style.display = "none";
            document.getElementById("otpForm").style.display = "block";
            document.getElementById("otpEmail").value = email; // Pass email to OTP form
        })
        .catch(error => alert('Error sending OTP: ' + error));
    };

    document.getElementById("resetPasswordForm").onsubmit = function(e) {
        e.preventDefault();

        var email = document.getElementById("otpEmail").value;
        var otp = document.getElementById("otp").value;
        var newPassword = document.getElementById("newPassword").value;

        var formData = new FormData();
        formData.append('email', email);
        formData.append('otp', otp);
        formData.append('password', newPassword);
        formData.append('btn-new-password', 'Reset Password');

        // Send reset password request via AJAX
        fetch('path-to-your-php-script.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Display success or error message
            modal.style.display = "none"; // Close the modal after processing
        })
        .catch(error => alert('Error resetting password: ' + error));
    };
</script>

<!-- CSS for Modal -->
<style>
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
        background-color: rgb(0,0,0); 
        background-color: rgba(0,0,0,0.4); 
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
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

    input[type="email"],
    input[type="password"],
    input[type="text"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        box-sizing: border-box;
        border: 1px solid #ccc;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    button:hover {
        opacity: 0.8;
    }
</style>
c