<?php
session_start();
if (!$_SESSION["userID"]) {
    header("Location:staff.login.php");
    exit();
}

require "connection.php";

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if a search has been requested
$searchMode = isset($_GET['search']) && !empty($_GET['patientID']);
$patientID = $searchMode ? sanitizeInput($_GET['patientID']) : '';

// Medical Dosage Records Query
$medDosageSql = "SELECT CONCAT(p.SSN, d.SSN, DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, 
                 DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, 
                 CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname, 
                 CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, 
                 Patient_SSN, c.Description, Complication, Medicine, Allergies 
                 FROM medical_administration c, patient p, doctor d 
                 WHERE p.SSN = Patient_SSN AND d.SSN = Doctor_SSN";
if ($searchMode) {
    $medDosageSql .= " AND p.SSN = ?";
}
$medDosageStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($medDosageStmt, $medDosageSql)) {
    header("Location:srecords.php?error=sqlerror");
    exit();
}
if ($searchMode) {
    mysqli_stmt_bind_param($medDosageStmt, "s", $patientID);
}
mysqli_stmt_execute($medDosageStmt);
$medDosageResult = mysqli_stmt_get_result($medDosageStmt);

// Consultation Records Query
$consultationSql = "SELECT DISTINCT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, 
                    DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, 
                    CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname, 
                    CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,
                    c.Patient_SSN, c.Complications, c.PatientName, 
                    c.Medicines, c.Description1, c.Treatments 
                    FROM consultation c
                    JOIN patient p ON c.Patient_SSN = p.SSN
                    JOIN doctor d ON c.Doctor_SSN = d.SSN";
if ($searchMode) {
    $consultationSql .= " AND p.SSN = ?";
}
$consultationStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($consultationStmt, $consultationSql)) {
    header("Location:drecords.php?error=sqlerror");
    exit();
}
if ($searchMode) {
    mysqli_stmt_bind_param($consultationStmt, "s", $patientID);
}
mysqli_stmt_execute($consultationStmt);
$consultationResult = mysqli_stmt_get_result($consultationStmt);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="drecords_style.css">
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
  
  </style>
    <title>Records</title>
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

   <!-- Search Form -->
   <div class="search-form">
    <form action="srecords.php" method="GET">
        <div class="search-container">
            <input type="text" name="patientID" placeholder="Enter Patient ID" class="search-input">
            <button type="submit" name="search" class="search-button">Search</button>
        </div>
    </form>
</div>

    <!-- Display Medical Dosage Records -->

    <div class='welcome'><h2 class='mssg'>Diagnosis Records</h2></div>

    <div class='table_box'>
        <table class='content-table'>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Patient Name</th>
                    <th>Patient ID</th>
                    <th>Description</th>
                    <th>Complications</th>
                    <th>Medicine</th>
                    <th>Allergies</th>
                    <th>Reference No</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($medDosageResult) > 0) {
                    while ($row = mysqli_fetch_assoc($medDosageResult)) {
                        echo "<tr>
                                <td>{$row['Date_Time']}</td>
                                <td>{$row['patient_fullname']}</td>
                                <td>{$row['Patient_SSN']}</td>
                                <td>{$row['Description']}</td>
                                <td>{$row['Complication']}</td>
                                <td>{$row['Medicine']}</td>
                                <td>{$row['Allergies']}</td>
                                <td>{$row['Reference']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No Medical Dosage Records Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Display Consultation Records -->
    <div class='welcome'><h2 class='mssg'>Consultation Records</h2></div>
    <div class='table_box'>
        <table class='content-table'>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Doctor</th>
                    <th>Complications</th>
                    <th>Medicine</th>
                    <th>Treatments</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($consultationResult) > 0) {
                    while ($row = mysqli_fetch_assoc($consultationResult)) {
                        echo "<tr>
                                <td>{$row['Date_Time']}</td>
                                <td>{$row['Patient_SSN']}</td>
                                <td>{$row['patient_fullname']}</td>
                                <td>{$row['doctor_fullname']}</td>
                                <td>{$row['Complications']}</td>
                                <td>{$row['Medicines']}</td>
                                <td>{$row['Treatments']}</td>
                                <td>{$row['Description1']}</td>
                                <td>
                                <a href='seditrecords.php?patientSSN={$row['Patient_SSN']}&dateTime={$row['Date_Time']}'>Edit</a>
                            </td>                        
                          </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No Consultation Records Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>