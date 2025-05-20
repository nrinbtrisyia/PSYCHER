<?php
require 'connection.php';
session_start();

if (isset($_POST['input-submit'])) {
    // Check if the user is logged in
    if (isset($_SESSION['userID'])) {
        $uid = $_SESSION['userID'];

        $pssn = $_POST['pssn'];
        $desc = $_POST['description'];
        $comp = $_POST['complication'];
        $med = $_POST['meds'];
        $alg = $_POST['allergies'];

        // SQL query to fetch the patient's first name and last name
        $sqlFetchPatientName = "SELECT F_Name, L_Name FROM patient WHERE SSN=?";
        $stmtFetchPatientName = mysqli_prepare($conn, $sqlFetchPatientName);
        mysqli_stmt_bind_param($stmtFetchPatientName, "s", $pssn);
        mysqli_stmt_execute($stmtFetchPatientName);
        $resultPatientName = mysqli_stmt_get_result($stmtFetchPatientName);
        $patient = mysqli_fetch_assoc($resultPatientName);
        $pfname = $patient['F_Name'] ?? 'Unknown'; // Patient's first name
        $plname = $patient['L_Name'] ?? 'Unknown'; // Patient's last name

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO medical_administration (Patient_SSN, Doctor_SSN, Date_Time, Description, Complication, Medicine, Allergies) VALUES (?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $pssn, $uid, $desc, $comp, $med, $alg);

        $stmt->execute();

        // Handle errors and redirect accordingly
        if ($stmt->error) {
            error_log("Error in SQL execution: " . $stmt->error);
            header("Location: dinsert.php?error=executionerror");
        } else {
            header("Location: dinsert.php?success=inserted");
        }
        $stmt->close(); // Close the statement
        $conn->close(); // Close the database connection
    } else {
        // Redirect if the user is not logged in
        header("Location: dinsert_diag.php");
        exit();
    }
}
?>
