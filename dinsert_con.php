<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

date_default_timezone_set('Asia/Kuala_Lumpur');
require "connection.php";

$uid = $_SESSION["userID"];

// Check if the form is submitted
if (isset($_POST["input-submit"])) {
    // Get data from the form
    $pssn = $_POST["pssn"] ?? ''; // Patient SSN
    $DateTime = $_POST['dateTime'];
    $comp = $_POST["Complications"]; // Complications
    $treat = $_POST["Treatments"]; // Treatments
    $med = $_POST["Medicines"] ?? null; // Medicines
    $desc = $_POST["Description1"] ?? null; // Description

    // SQL query to fetch the patient's first name and last name
    $sqlFetchPatientName = "SELECT F_Name, L_Name FROM patient WHERE SSN=?";
    $stmtFetchPatientName = mysqli_prepare($conn, $sqlFetchPatientName);
    mysqli_stmt_bind_param($stmtFetchPatientName, "s", $pssn);
    mysqli_stmt_execute($stmtFetchPatientName);
    $resultPatientName = mysqli_stmt_get_result($stmtFetchPatientName);
    $patient = mysqli_fetch_assoc($resultPatientName);
    $pfname = $patient['F_Name'] ?? 'Unknown'; // Patient's first name
    $plname = $patient['L_Name'] ?? 'Unknown'; // Patient's last name

    // Initialize the statement here
    $stmt = mysqli_stmt_init($conn);

    // SQL query
    $sql = "INSERT INTO consultation (Patient_SSN, Doctor_SSN, Date_Time, Complications, Medicines, Treatments, Description1)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: dinsert.php?error=sqlerror");
        exit();
    }
    
    // The number of placeholders should match the number of arguments
    mysqli_stmt_bind_param($stmt, "sssssss", $pssn, $uid, $DateTime, $comp, $med, $treat, $desc);
    
    mysqli_stmt_execute($stmt);

    // Error handling
    if (mysqli_error($conn)) {
        error_log("Error in SQL execution: " . mysqli_error($conn));
        header("Location: dinsert.php?error=executionerror");
        exit();
    } else {
        header("Location: dinsert.php?success=inserted");
        exit();
    }
} else {
    // Redirect if the form is not submitted
    header("Location: dinsert.php");
    exit();
}
?>
