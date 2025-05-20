<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

// Check if password was updated successfully
$successMessage = '';

if (isset($_SESSION['passwordUpdateMessage'])) {
    $successMessage = $_SESSION['passwordUpdateMessage'];
    unset($_SESSION['passwordUpdateMessage']);
}

// Check for success
if (isset($_SESSION['passwordUpdateSuccess']) && $_SESSION['passwordUpdateSuccess']) {
    $successMessage = 'Password updated successfully.';
    unset($_SESSION['passwordUpdateSuccess']);
}

require "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="resetPass.css">
    <script>
        function checkPasswordStrength() {
            console.log("Function called!");  // Add this line for debugging
            var newPassword = document.getElementById("newPassword").value;
            var confirmPassword = document.getElementById("confirmNewPassword").value;
            var newPasswordWarning = document.getElementById("newPasswordWarning");
            var confirmPasswordWarning = document.getElementById("confirmPasswordWarning");

            // Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character
            var strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!strongRegex.test(newPassword)) {
                newPasswordWarning.innerHTML = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
                newPasswordWarning.style.display = "block";
                document.getElementById("newPassword").value = '';
                document.getElementById("confirmNewPassword").value = '';
                return false;
            } else {
                newPasswordWarning.innerHTML = "";
                newPasswordWarning.style.display = "none";
            }

            if (newPassword !== confirmPassword) {
                confirmPasswordWarning.innerHTML = "Passwords do not match.";
                confirmPasswordWarning.style.display = "block";
                document.getElementById("newPassword").value = '';
                document.getElementById("confirmNewPassword").value = '';
                return false;
            } else {
                confirmPasswordWarning.innerHTML = "";
                confirmPasswordWarning.style.display = "none";
            }

            return true;
        }


        window.onload = function() {
            var successMessage = "<?php echo $successMessage; ?>";
            if (successMessage) {
                alert(successMessage);
                // Display the success message in an HTML element instead of an alert if preferred
                // For example, document.getElementById("successMessage").innerHTML = successMessage;
                window.location.href = 'dprofile.php'; // Redirect to the profile page
            }
        }
    </script>

</head>

<body>
    <div class="header">
        <div>
            <h1>PsychER</h1>
            <h2>Psychiatric Patient Health Record</h2>
        </div>
    </div>

    <div class="navigation-bar">
        <a href="ddashboard.php">Home</a>
        <a href="drecords.php">Records</a>
        <a href="dinsert.php">Insert</a>
        <a href="dprofile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <br>
        <form action="process_reset_password.php" method="post" onsubmit="return checkPasswordStrength()">
            <div class="form-group">
                <label for="currentPassword">Current Password:</label>
                <input type="password" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <span id="newPasswordWarning" style="color: red;"></span>
            </div>
            <div class="form-group">
                <label for="confirmNewPassword">Confirm New Password:</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
                <span id="confirmPasswordWarning" style="color: red;"></span>
            </div>
            <button type="submit" class="reset-btn">Reset Password</button>
        </form>
    </div>

</body>

</html>