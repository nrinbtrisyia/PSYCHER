<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location: patient.login.php");
    exit(); // Terminate script to prevent further execution
}

// Include your database connection file
require "connection.php";

// Initialize variables
$patientName = "";
$userId = $_SESSION["userID"];
$address = "";
$contactNumber = "";
$email = "";
$updateSuccess = false;
$profileUpdated = false; // Define the variable

// Retrieve patient's name from the database
$query = "SELECT F_Name, L_Name FROM patient WHERE SSN = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("s", $userId);
    if ($stmt->execute()) {
        $stmt->bind_result($firstName, $lastName);
        if ($stmt->fetch()) {
            $patientName = $firstName . " " . $lastName;
        }
    }
    $stmt->close();
}

// Handle form submission for updating contact information
if (isset($_POST["info-submit"])) {
    $address = $_POST["ads"];
    $contactNumber = $_POST["ctc"];
    $email = $_POST["mail"];

    // Perform the database update based on the provided information
    $query = "UPDATE patient SET Address = ?, Contact_No = ?, Email = ? WHERE SSN = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $address, $contactNumber, $email, $userId);
        if ($stmt->execute()) {
            $updateSuccess = true;
            $profileUpdated = true; // Set the variable to true upon successful update
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="pinfoEdit.css">
    <style>
        /* Add this to your existing CSS file */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            z-index: 1;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
    <title>Edit</title>

    <?php if ($profileUpdated): ?>
        <script>
        window.onload = function() {
            alert("Profile Updated Successfully");
            window.location.href = 'pprofile.php'; // Redirect after showing the alert
        };
        </script>
    <?php endif; ?>


</head>
<body>
<div class="header">
    <h1>PsychER</h1>
    <h2>Psychiatric Patient Health Record</h2>
</div>

<div class="navigation-bar" style="text-align: center">
    <a href="pdashboard.php">Home</a>
    <a href="precords.php">Records</a>
    <a href="pprofile.php">Profile</a>
    <a class='logout' href="logout.php">Logout</a>
</div>

<div class="profile-section">
    <a href="pprofile.php">
        <img class="profile-icon" src="resource/profile.png" alt="Profile Icon" />
    </a>
    <p><?php echo $patientName; ?></p>
</div>

<div class='welcome'><h2 class='welcome_mssg'>Contact Information Edit </h2></div>

<div class="wrapper">
    <div class="container">
        <form class="ci_edit_form" action="patient_info_edit.php" method="post">
            <input type="text" name="ads" placeholder="Enter New Address" value="<?php echo $address; ?>"><br>
            <input type="text" name="ctc" placeholder="Enter New Contact Number" value="<?php echo $contactNumber; ?>"><br>
            <input type="text" name="mail" placeholder="Enter New Email" value="<?php echo $email; ?>">
            <input type="submit" name="info-submit" value="Save">
        </form>
        <?php
        if ($updateSuccess) {
            echo "<div id='popup' class='popup'>Edit record successfully</div>";
        } elseif (isset($_POST["info-submit"])) {
            echo "<p class='alert'>Error updating information. Please try again.</p>";
        }
        ?>
    </div>
</div>

<div class="footer"></div>


</body>
</html>
