<?php
session_start();
if (!$_SESSION["userID"]) {
    header("Location: staff.login.php");
    exit();
}

require "connection.php";

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['info-submit'])) {
    // Retrieve form data
    $patientID = sanitizeInput($_POST['patientID']);
    $complications = sanitizeInput($_POST['complications']);
    $medicine = sanitizeInput($_POST['medicine']);
    $treatments = sanitizeInput($_POST['treatments']);
    $description = sanitizeInput($_POST['description']);
    $date = sanitizeInput($_POST['date']);
    $time = sanitizeInput($_POST['time']);
    $dateTime = $date . ' ' . $time;

    // Update consultation information
    $updateSql = "UPDATE consultation 
                  SET Date_Time = ?, Complications = ?, Medicines = ?, Treatments = ?, Description1 = ? 
                  WHERE Patient_SSN = ? AND Date_Time = ?";
    
    $updateStmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($updateStmt, $updateSql)) {
        die('Error in update SQL query: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($updateStmt, "sssssss", $dateTime, $complications, $medicine, $treatments, $description, $patientID, $dateTime);
    $updateResult = mysqli_stmt_execute($updateStmt);

    if ($updateResult) {
        echo '<script>alert("Successfully Updated"); window.location.href = "srecords.php";</script>';
        // You can redirect the user to a confirmation page or perform additional actions here.
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($updateStmt);
}

mysqli_close($conn);
?>
