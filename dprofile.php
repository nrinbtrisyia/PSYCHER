<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

require "connection.php";
$uid = $_SESSION["userID"];
$sql = "SELECT SSN, F_Name, CONCAT(F_Name,' ',L_Name) AS Full_name, d.Address, Contact_No, d.Email, h.name, Department, Speciality, Designation FROM doctor d, hospital h WHERE d.Hospital_ID=h.ID AND SSN=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location:ddashboard.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $doctorDetails = $row; // Assign the details to the $doctorDetails variable
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="dprofile_style.css">
    <title>Doctor Profile</title>
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

<div class='welcome'>
    <h2 class='welcome_mssg'> GREETINGS <?php echo strtoupper($doctorDetails['F_Name']); ?></h2>
</div>

<div class='profile-section'>
    <div class= "profile-picture">
        <img src="Resource/rosliza.jpg" alt="Your Profile Picture">
    </div>

    <div class='pi_box'>
        <h3> Personal Info</h3>
        <table>
            <tr>
                <th>FULL NAME</th>
                <td><?php echo $doctorDetails['Full_name']; ?></td>
            </tr>
            <tr>
                <th>DEPARTMENT</th>
                <td><?php echo $doctorDetails['Department']; ?></td>
            </tr>
            <tr>
                <th>DESIGNATION</th>
                <td><?php echo $doctorDetails['Designation']; ?></td>
            </tr>
            <tr>
                <th>SPECIALITY</th>
                <td><?php echo $doctorDetails['Speciality']; ?></td>
            </tr>
            <tr>
                <th>ADDRESS</th>
                <td><?php echo $doctorDetails['Address']; ?></td>
            </tr>
            <tr>
                <th>CONTACT NO</th>
                <td><?php echo $doctorDetails['Contact_No']; ?></td>
            </tr>
            <tr>
                <th>E-MAIL</th>
                <td><?php echo $doctorDetails['Email']; ?></td>
            </tr>
        </table>
        
        <!-- Add "Edit" button -->
        <div class="edit-button">
            <a href="edit_profile.php">Edit</a>
        </div>

        <!-- Add "Forgot Password" button below "Edit" button -->
        <div class="forgot-password-button">
        <div class='res_div'><a class='res' href='d_res_pass.php'>Reset Password</a></div>
        </div>
    </div>
</div>


<div class='footer'></div>
</body>
</html>
