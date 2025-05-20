<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

require "connection.php";

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Retrieve the reference ID from the URL
$reference = isset($_GET['ref']) ? sanitizeInput($_GET['ref']) : '';

if (!$reference) {
    echo "No reference ID provided.";
    exit();
}

// Prepare the SQL delete statement for medical administration records
$deleteMedicalSql = "DELETE FROM medical_administration WHERE CONCAT(Patient_SSN, Doctor_SSN, DATE_FORMAT(Date_Time, '%Y%m%d%s%i%k')) = ?";
$deleteMedicalStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($deleteMedicalStmt, $deleteMedicalSql)) {
    echo "Error preparing SQL statement for medical administration records.";
    exit();
}

// Bind the parameter and execute the statement for medical administration records
mysqli_stmt_bind_param($deleteMedicalStmt, "s", $reference);
if (mysqli_stmt_execute($deleteMedicalStmt)) {
    $_SESSION['delete_success'] = true;
} else {
    echo "Error deleting medical administration record.";
}

// Prepare the SQL delete statement for consultation records
$deleteConsultationSql = "DELETE FROM consultation WHERE CONCAT(Patient_SSN, Doctor_SSN, DATE_FORMAT(Date_Time, '%Y%m%d%s%i%k')) = ?";
$deleteConsultationStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($deleteConsultationStmt, $deleteConsultationSql)) {
    echo "Error preparing SQL statement for consultation records.";
    exit();
}

// Bind the parameter and execute the statement for consultation records
mysqli_stmt_bind_param($deleteConsultationStmt, "s", $reference);
if (mysqli_stmt_execute($deleteConsultationStmt)) {
    $_SESSION['delete_success'] = true;
} else {
    echo "Error deleting consultation record.";
}

// Close the database connection
mysqli_close($conn);

// Redirect to drecords.php with a delay
echo "<script>
    setTimeout(function() {
        alert('Record deleted successfully.');
        window.location.href = 'drecords.php';
    }, 100);
</script>";
?>
