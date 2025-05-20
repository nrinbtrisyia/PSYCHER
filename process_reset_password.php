<?php
session_start();

if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

require "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['passwordUpdateMessage'] = "New passwords do not match. Please try again.";
        header("Location: d_res_pass.php");
        exit;
    }

    $uid = $_SESSION["userID"];
    $sql = "SELECT pass FROM doctor_login WHERE d_ssn = ?";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $_SESSION['passwordUpdateMessage'] = "Error preparing statement";
        header("Location: d_res_pass.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($currentPassword != $row['pass']) {
        $_SESSION['passwordUpdateMessage'] = "Current password is incorrect.";
        header("Location: d_res_pass.php");
        exit();
    }

    $update_sql = "UPDATE doctor_login SET pass = ? WHERE d_ssn = ?";
    $update_stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($update_stmt, $update_sql)) {
        $_SESSION['passwordUpdateMessage'] = "Error preparing update statement";
        header("Location: d_res_pass.php");
        exit();
    }

    mysqli_stmt_bind_param($update_stmt, "ss", $newPassword, $uid);

    if (mysqli_stmt_execute($update_stmt)) {
        $_SESSION['passwordUpdateSuccess'] = true;
        header("Location: d_res_pass.php");
        exit();
    } else {
        $_SESSION['passwordUpdateMessage'] = "Error updating password.";
        header("Location: d_res_pass.php");
        exit();
    }
}
?>


