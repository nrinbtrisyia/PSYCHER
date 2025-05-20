<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit();
}

date_default_timezone_set('Asia/Kuala_Lumpur');
require "connection.php";

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
$doctorDetails = mysqli_fetch_assoc($result);

// Modified SQL query with WHERE clause
$sqlAppointments = "SELECT Patient_SSN, Date_Time, Description1 FROM consultation WHERE Doctor_SSN=? AND DATE(Date_Time) = ?";
$stmtAppointments = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtAppointments, $sqlAppointments)) {
    die('SQL error: ' . mysqli_error($conn));
} else {
    $currentDate = date("Y-m-d");  // Get current date
    mysqli_stmt_bind_param($stmtAppointments, "ss", $uid, $currentDate);  // Bind current date
    mysqli_stmt_execute($stmtAppointments);

    $resultAppointments = mysqli_stmt_get_result($stmtAppointments);
    if (!$resultAppointments) {
        die('Error in fetching results: ' . mysqli_error($conn));
    }
}

// Modify the function to accept the $conn variable
function getPatientName($patientID, $conn) {
    $sql = "SELECT CONCAT(F_Name, ' ', L_Name) AS Full_name FROM patient WHERE SSN=?";
    $stmt = mysqli_stmt_init($conn);
    
    if (!$stmt) {
        return "Unknown";
    }

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $patientID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $patientDetails = mysqli_fetch_assoc($result);

        if ($patientDetails) {
            return $patientDetails["Full_name"];
        }
    }
    return "Unknown";
}


?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="ddash_style.css">
    
    <title>Dashboard</title>
</head>
<body>
    <div class="header">
        <div>
            <h1>PsychER</h1>
            <h2>Psychiatric Patient Health Record</h2>
        </div>
         <div class="doctor-info">
        <img src="Resource/rosliza.jpg">
        <h2><?php echo htmlspecialchars($doctorDetails["Full_name"]); ?></h2>
    </div>
    </div>

    <div class="navigation-bar">
        <a href="ddashboard.php">Home</a>
        <a href="drecords.php">Records</a>
        <a href="dinsert.php">Insert</a>
        <a href="dprofile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="clock" id="clock">
        <div id="time"></div>
        <div id="date"></div>
    </div>


    <br><br>
    <div class='pi_table'>
    <h3>Patient Appointment List</h3>
    <table>
        <tr>
            <th>Patient Name</th>
            <th>Date & Time</th>
            <th>Description</th>
        </tr>
        <?php
        while ($rowAppointments = mysqli_fetch_assoc($resultAppointments)) {
            // Check if "Patient_SSN" is defined and not null
            if (isset($rowAppointments["Patient_SSN"]) && $rowAppointments["Patient_SSN"] !== null) {
                // Retrieve the patient's name based on their ID
                $patientID = $rowAppointments["Patient_SSN"];
                $patientName = getPatientName($patientID,$conn);

            } else {
                // Handle the case where "Patient_SSN" is not defined or null
                $patientName = "Unknown";
            }

            echo "<tr>
                    <td>" . htmlspecialchars($patientName) . "</td>
                    <td>" . htmlspecialchars($rowAppointments["Date_Time"]) . "</td>
                    <td>" . htmlspecialchars($rowAppointments["Description1"]) . "</td>
                  </tr>";
        }
        ?>
    </table>
</div>




    <script>
        function updateClock() {
            const now = new Date();
            const dateString = now.toLocaleDateString();
            const timeString = now.toLocaleTimeString();
            document.getElementById('time').textContent = timeString;
            document.getElementById('date').textContent = dateString;
        }

        setInterval(updateClock, 1000);
        updateClock(); // Initialize clock immediately
    </script>
</body>
</html>