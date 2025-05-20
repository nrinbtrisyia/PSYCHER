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
$deleteSql = "DELETE FROM medical_administration WHERE CONCAT(Patient_SSN, Doctor_SSN, DATE_FORMAT(Date_Time, '%Y%m%d%s%i%k')) = ?";
$deleteStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($deleteStmt, $deleteSql)) {
    echo "Error preparing SQL statement.";
    exit();
}

// Bind the parameter and execute the statement
mysqli_stmt_bind_param($deleteStmt, "s", $reference);
if (mysqli_stmt_execute($deleteStmt)) {
    $_SESSION['delete_success'] = true;
} else {
    echo "Error deleting medical record.";
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
