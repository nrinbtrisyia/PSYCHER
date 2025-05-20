<?php
session_start();
include 'connection.php'; // Make sure this points to your actual database connection file

if (!isset($_SESSION["userID"])) {
    header("Location: patient.login.php");
    exit();
}

// Retrieve patient's name from the database
$ssn = $_SESSION["userID"];
$query = "SELECT F_Name, L_Name FROM patient WHERE SSN = ?";
$patientName = '';

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("s", $ssn);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);
    if ($stmt->fetch()) {
        $patientName = htmlspecialchars($firstName . ' ' . $lastName);
    }
    $stmt->close();
} else {
    echo "Query error: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="pprofile_style.css">
  <title>Dashboard</title>
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

    <?php
    require "connection.php";
    $uid = $_SESSION["userID"];
    $sql = "SELECT SSN, F_Name, CONCAT(F_Name, ' ', L_Name) AS Full_name, Address, Contact_No, Email, Date_Format(Date_Of_Birth, '%M %D %Y') AS Date_Of_Birth, Gender, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(Date_Of_Birth, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(Date_Of_Birth, '00-%m-%d')) AS age FROM patient WHERE SSN=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: pdashboard.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $uid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $ssn = $row["SSN"];
            $fname = strtoupper($row["F_Name"]);
            $fullname = $row["Full_name"];
            $address = $row["Address"];
            $cont = $row["Contact_No"];
            $mail = $row["Email"];
            $dob = $row["Date_Of_Birth"];
            $gen = $row["Gender"];
            $age = $row["age"];
            ?>

            <div class="info-container">
                <h2 class='welcome_mssg'>Greetings, <?php echo $fname; ?></h2>
                <div class='info-box'>
                    <div class='personal-info'>
                        <h3>Personal Info</h3>
                        <table class='info-table'>
                            <tr><th>FULL NAME</th><td><?php echo $fullname; ?></td></tr>
                            <tr><th>BIRTHDAY</th><td><?php echo $dob; ?></td></tr>
                            <tr><th>AGE</th><td><?php echo $age; ?></td></tr>
                            <tr><th>GENDER</th><td><?php echo $gen; ?></td></tr>
                        </table>
                    </div>

                    <div class='contact-info'>
                        <h3>Contact Info</h3>
                        <table class='info-table'>
                            <tr><th>ADDRESS</th><td><?php echo $address; ?></td></tr>
                            <tr><th>CONTACT NO</th><td><?php echo $cont; ?></td></tr>
                            <tr><th>E-MAIL</th><td><?php echo $mail; ?></td></tr>
                        </table>
                    </div>

                    <div class="action-buttons">
                        <a href="patient_info_edit.php" class="edit">Edit</a>
                        <a href='p_res_pass.php' class='res'>Reset Password</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
        <div class='footer'>
            <!-- Footer content -->
        </div>
    </body>
</html>