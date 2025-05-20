<?php
session_start();
if (!isset($_SESSION["userID"]) || empty($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

if (isset($_SESSION['delete_success'])) {
    echo "<p class='success-message'>" . $_SESSION['delete_success'] . "</p>";
    unset($_SESSION['delete_success']);
}

// Function to sanitize input 
function sanitizeInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

require "connection.php";

// Check if a search has been requested
$searchMode = isset($_GET['search']) && !empty($_GET['patientID']);
$patientID = $searchMode ? sanitizeInput($_GET['patientID']) : '';

// Medical Administration Records Query
$medicalAdminSql = "SELECT CONCAT(p.SSN, d.SSN, DATE_FORMAT(m.Date_Time,'%Y%m%d%s%i%k')) AS Reference, 
                 DATE_FORMAT(m.Date_Time,'%M %D %Y %r') AS Date_Time, 
                 CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname, 
                 CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, 
                 m.Patient_SSN, m.Description, m.Complication, m.Medicine, m.Allergies 
                 FROM medical_administration m, patient p, doctor d 
                 WHERE p.SSN = m.Patient_SSN AND d.SSN = m.Doctor_SSN";
if ($searchMode) {
    $medicalAdminSql .= " AND p.SSN = ?";
}
$medicalAdminStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($medicalAdminStmt, $medicalAdminSql)) {
    header("Location:drecords.php?error=sqlerror");
    exit();
}

if ($searchMode) {
    mysqli_stmt_bind_param($medicalAdminStmt, "s", $patientID);
}

mysqli_stmt_execute($medicalAdminStmt);
$medicalAdminResult = mysqli_stmt_get_result($medicalAdminStmt);

// Fetch logged-in doctor's SSN
$uid = $_SESSION["userID"];

// Consultation Records Query
$consultationSql = "SELECT CONCAT(p.SSN, d.SSN, DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, 
                    DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, 
                    CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname, 
                    CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,
                    c.Patient_SSN, c.Complications, c.Medicines, c.Description1, c.Treatments 
                    FROM consultation c 
                    INNER JOIN patient p ON c.Patient_SSN = p.SSN
                    INNER JOIN doctor d ON c.Doctor_SSN = d.SSN
                    WHERE c.Doctor_SSN = ?"; // Adjusted query to filter by Doctor_SSN


if ($searchMode) {
    $consultationSql .= " AND p.SSN = ?";
}

$consultationStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($consultationStmt, $consultationSql)) {
    header("Location:drecords.php?error=sqlerror");
    exit();
}

if ($searchMode) {
    mysqli_stmt_bind_param($consultationStmt, "ss", $uid, $patientID); // Bind Doctor_SSN and Patient_SSN
} else {
    mysqli_stmt_bind_param($consultationStmt, "s", $uid); // Bind only Doctor_SSN
}

mysqli_stmt_execute($consultationStmt);
$consultationResult = mysqli_stmt_get_result($consultationStmt);

// Fetch logged-in doctor details
$uid = $_SESSION["userID"];
$sql = "SELECT SSN, CONCAT(F_Name, ' ', L_Name) AS Full_name FROM doctor WHERE SSN=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ddashboard.php?error=sqlerror");
    exit();
} 

mysqli_stmt_bind_param($stmt, "s", $uid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$loggedInDoctorDetails = mysqli_fetch_assoc($result);

if (isset($_SESSION['doctorFullName'])) {
    $doctorFullName = htmlspecialchars($_SESSION['doctorFullName']);
} else {
    $doctorFullName = htmlspecialchars($loggedInDoctorDetails["Full_name"]);
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
    <link rel="stylesheet" type="text/css" href="drecords_style.css">

    <script type="text/javascript">
    function confirmDelete(reference) {
        var confirmAction = confirm("Are you sure you want to delete this record?");
        if (confirmAction) {
            window.location.href = 'deleteRecord.php?action=delete&ref=' + reference;
        } else {
            return false;
        }
    }
    </script>

<script type="text/javascript">
    function confirmDeleteMedical(reference) {
        var confirmAction = confirm("Are you sure you want to delete this record?");
        if (confirmAction) {
            window.location.href = 'deleteMedicalRecord.php?action=delete&ref=' + reference;
        } else {
            return false;
        }
    }
</script>


    <title>Records</title>
  </head>
  <body>
    <div class="header">
        <div>
            <h1>PsychER</h1>
            <h2>Psychiatric Patient Health Record</h2>
        </div>
        <div class="doctor-info">
            <img src="Resource/rosliza.jpg">
            <h2><?php echo htmlspecialchars($doctorFullName); ?></h2>
        </div>
    </div>

    <div class="navigation-bar">
        <a href="ddashboard.php">Home</a>
        <a href="drecords.php">Records</a>
        <a href="dinsert.php">Insert</a>
        <a href="dprofile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Search Form -->
<div class="search-form">
    <form action="drecords.php" method="GET">
        <div class="search-container">
            <input type="text" name="patientID" placeholder="Enter Patient ID" class="search-input">
            <button type="submit" name="search" class="search-button">Search</button>
        </div>
    </form>
</div>

         <!-- Display Medical Administration Records -->
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($medicalAdminResult) > 0) {
                    while ($row = mysqli_fetch_assoc($medicalAdminResult)) {
                        echo "<tr>
                                <td>{$row['Date_Time']}</td>
                                <td>{$row['patient_fullname']}</td>
                                <td>{$row['Patient_SSN']}</td>
                                <td>{$row['Description']}</td>
                                <td>{$row['Complication']}</td>
                                <td>{$row['Medicine']}</td>
                                <td>{$row['Allergies']}</td>
                                <td>{$row['Reference']}</td>
                                <td>
                                    <a href='editDiagnosis.php?ref={$row['Reference']}'>Edit</a>
                                    <a href='javascript:void(0);' onclick='confirmDeleteMedical(\"{$row['Reference']}\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No Medical Administration Records Found</td></tr>";
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
                                        <a href='editRecord.php?ref={$row['Reference']}'>Edit</a>
                                        <a href='javascript:void(0);' onclick='confirmDelete(\"{$row['Reference']}\")'>Delete</a>
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

<!-- Display Questionnaire Records -->
<div class='welcome'><h2 class='mssg'>Questionnaire Records</h2></div>
<div class='table_box'>
    <table class='content-table'>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Question 1</th>
                <th>Question 2</th>
                <th>Question 3</th>
                <th>Question 4</th>
                <th>Question 5</th>
                <th>Question 6</th>
                <th>Question 7</th>
                <th>Question 8</th>
                <th>Question 9</th>
                <th>Question 10</th>
                <th>Score</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            // Perform your database query to fetch questionnaire records
            $questionnaireSql = "SELECT qr.Patient_SSN, qr.q1, qr.q2, qr.q3, qr.q4, qr.q5, qr.q6, qr.q7, qr.q8, qr.q9, qr.q10, qr.score, p.SSN AS Patient_ID 
                     FROM questionnaire_responses qr 
                     INNER JOIN patient p ON qr.Patient_SSN = p.SSN";

            $questionnaireStmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($questionnaireStmt, $questionnaireSql)) {
                header("Location:drecords.php?error=sqlerror");
                exit();
            }

            mysqli_stmt_execute($questionnaireStmt);
            $questionnaireResult = mysqli_stmt_get_result($questionnaireStmt);

            if (mysqli_num_rows($questionnaireResult) > 0) {
                while ($row = mysqli_fetch_assoc($questionnaireResult)) {
                    echo "<tr>
                            <td>{$row['Patient_ID']}</td>
                            <td>{$row['q1']}</td>
                            <td>{$row['q2']}</td>
                            <td>{$row['q3']}</td>
                            <td>{$row['q4']}</td>
                            <td>{$row['q5']}</td>
                            <td>{$row['q6']}</td>
                            <td>{$row['q7']}</td>
                            <td>{$row['q8']}</td>
                            <td>{$row['q9']}</td>
                            <td>{$row['q10']}</td>
                            <td>{$row['score']}</td>
                          
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='13'>No Questionnaire Records Found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </div>
  </body>
</html>
