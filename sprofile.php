<?php
 session_start();
 if(!$_SESSION["userID"])
 {
   header("Location:staff.login.php");
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
  <style>
        thead {
            text-decoration: underline;
        }

        .pi_table h3 {
            margin-bottom: 5px; /* Reduce the bottom margin for the h3 elements */
        }

        /* Adjust spacing between table headers and content */
        table {
            margin-top: 5px; /* Reduce the top margin for the table */
        }

        .dropdown {
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            text-align: left;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black; /* Set the color for dropdown links */
            text-decoration: none;
            display: block;
            margin: auto;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .header1{
          text-align:center;
        }
  </style>
  <title> Profile </title>
</head>
<body>
<div class="header">
            <h1>PsychER</h1>
            <h2>Psychiatric Patient Health Record</h2>
        </div>
            <div class="navigation-bar" style="text-align: center">
    <a href="sdashboard.php">Home</a>
    <div class="dropdown">
    <a href="#">Patients</a>
    <div class="dropdown-content">
    <a href="srecords.php">Records</a>
    <a href="staffappointments.php">Appointment</a>
    </div>
    </div>
        <a href="aregister.php">Register</a>
        <a href="sprofile.php">Profile</a>
        <a class='logout' href="logout.php">Logout</a>
    </div>

  
  <?php
    require "connection.php";
    $uid=$_SESSION["userID"];
    $sql="SELECT SSN, F_Name, CONCAT(F_Name,' ',L_Name) AS Full_name, Contact_No, d.Email,h.name, d.Address, Department, Designation FROM medical_staff d, hospital h WHERE d.Hospital_ID=h.ID AND SSN=?";
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
      header("Location:sdashboard.php?error=sqlerror");
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $uid);
      mysqli_stmt_execute($stmt);
      $result= mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result))
      {
        $ssn=$row["SSN"];
        $fname=strtoupper($row["F_Name"]);
        $fullname=$row["Full_name"];
        $address=$row["Address"];
        $cont=$row["Contact_No"];
        $mail=$row["Email"];
        $hname=$row["name"];
        $dep=$row["Department"];
        $desg=$row["Designation"];

    echo 
    " <div class='welcome'><h2 class='welcome_mssg'> GREETINGS $fname</h2></div>
    <div class='profile-section'>
    <div class= 'profile-picture'>
        <img src='Resource/irma.jpg' alt='Your Profile Picture'>
    </div>

    <div class='pi_box'>
    <h3> Personal Info</h3>
      <table>
        <tr><th>FULL NAME</th>
            <td>$fullname</td></tr>
        <tr><th>HOSPITAL NAME</th>
           <td>$hname</td></tr>
        <tr><th>DEPARTMENT</th>
            <td>$dep</td></tr>
        <tr><th>DESIGNATION</th>
           <td>$desg</td></tr>
        <tr><th>ADDRESS</th>
            <td>$address</td></tr>
        <tr><th>CONTACT NO</th>
           <td>$cont</td></tr>
        <tr><th>E-MAIL</th>
           <td>$mail</td></tr>
      </table>
      <div class='edit-button'>
      <a href='staff_info_edit.php'>Edit</a>
    </div>

  <div class='forgot-password-button'>
  <div class='res_div'><a class='res' href='s_res_pass.php'>Reset Password</a></div>
      </div>
      </div>
      </div>
  <div class='footer'></div>
";

      }
    }
   ?>

</body>
</html>
