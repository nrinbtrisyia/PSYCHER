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
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="precords_style.css">
    <title>Records</title>
    <style>
       .header {
        text-align: center;
        margin-top: 20px;
    }
    .profile-section {
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: center;
        }

        .profile-section img {
            width: 50px;
            height: 50px;
        }

        .profile-section p {
            margin-top: 5px;
            font-size: 14px;
        }

        
        .table-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content-table {
            border-collapse: collapse;
            margin: 25px auto; /* Center table horizontally */
            font-size: 0.9em;
            width: 90%; /* Adjust as needed */
            max-width: 1000px; /* Maximum width */
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }

        .content-table thead tr {
            background-color: black;
            color: white;
            text-align: left;
            font-weight: bold;
        }

        .content-table th, 
        .content-table td {
            padding: 12px 15px;
            text-align: center; /* Center text in cells */
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid black;
        }
      </style>
</head>

<body>
    <div class="header">
        <h1>PsychER</h1>
        <h2>Psychiatric Patient Health Record</h2>
       
    </div>

    <div class="profile-section">
        <a href="pprofile.php">
            <img class="profile-icon" src="resource/profile.png" alt="Profile Icon" />
        </a>
        <p><?php echo $patientName; ?></p>
    </div>

  <div class="navigation-bar" style="text-align: center">
    <a href="pdashboard.php">Home</a>
    <a href="precords.php">Records</a>
    <a href="pprofile.php">Profile</a>
    <a class='logout' href="logout.php">Logout</a>
  </div>



    <?php
    require "connection.php";

    $uid = $_SESSION["userID"];

    #Consultation
    $sql="SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, 
    DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, 
    CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,
    Patient_SSN,
    Doctor_SSN, Complications,
    Medicines, Description1, Treatments 
    FROM consultation c,patient p, doctor d WHERE Patient_SSN=? AND p.SSN=Patient_SSN AND Doctor_SSN=d.SSN";
    
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
      header("Location:drecords.php?error=sqlerror");
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $uid);
      mysqli_stmt_execute($stmt);
      $result= mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result)>0)
      {
        
        echo "<div class='welcome'><h2 class='mssg'> Counsultation Records </h2></div>
        <div class='table_box'>
          <table class='content-table'>
            <thead>
            <tr>
            <th>Date & Time</th>
            <th>Patient ID</th>
          
            <th>Doctor</th>
         
            <th>Medicine</th>
            <th>Treatments</th>
   
            </tr>
            </thead>
        ";

      while ($row = mysqli_fetch_assoc($result)) {
        $dt=$row["Date_Time"];
        $dssn=$row["Patient_SSN"];
      
        $drname=$row["doctor_fullname"];
   
        $med=$row["Medicines"];
        $treat=$row["Treatments"];
  

        echo "
        <tbody>
        <tr>
          <td>$dt</td>
          <td>$dssn</td>
          <td>$drname</td>
   
          <td>$med</td>
          <td>$treat</td>
     
        </tr>
        ";

        
      }
      echo "</tbody></table>

      </div>";
      }
      elseif (mysqli_num_rows($result)==0) {
        echo "<div class='welcome'><h2 class='mssg'> No Consultation Records Found </h2></div>";
      }
    }


#medical administration
$sql = "SELECT 
            DATE_FORMAT(ma.Date_Time, '%M %D %Y %r') AS Date_Time, 
            p.SSN AS Patient_SSN, 
            CONCAT(d.F_Name, ' ', d.L_Name) AS doctor_fullname, 
      
         
            ma.Medicine AS Medicine, 
            ma.Allergies AS Allergies
        FROM 
            medical_administration ma
        JOIN 
            patient p ON ma.Patient_SSN = p.SSN
        JOIN 
            doctor d ON ma.Doctor_SSN = d.SSN
        WHERE 
            ma.Patient_SSN = ?";



$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
echo "SQL error: " . mysqli_error($conn);
} else {
mysqli_stmt_bind_param($stmt, "s", $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {

echo "<div class='welcome'><h2 class='mssg'> Diagnosis Records </h2></div>
        <div class='table_box'>
          <table class='content-table'>
            <thead>
            <tr>
            <th>Date & Time</th>
            <th>Patient ID</th>
          
            <th>Doctor</th>
            
 
            <th>Medicine</th>
            <th>Allergies</th>
           
            </tr>
            </thead>
        ";

        while ($row = mysqli_fetch_assoc($result)) {
            $dt=$row["Date_Time"];
            $dssn=$row["Patient_SSN"];
          
            $drname=$row["doctor_fullname"];
       
            $meds=$row["Medicine"];
            $treat=$row["Allergies"];
        
    
            echo "
            <tbody>
            <tr>
              <td>$dt</td>
              <td>$dssn</td>
              <td>$drname</td>
    
              <td>$meds</td>
              <td>$treat</td>
    
            </tr>
            ";
    
            
          }
          echo "</tbody></table>
    
          </div>";
          }
          elseif (mysqli_num_rows($result)==0) {
            echo "<div class='welcome'><h2 class='mssg'> No Diagnosis Records Found </h2></div>";
          }
        }