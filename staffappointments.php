<?php
session_start();
if (!$_SESSION["userID"]) {
    header("Location: staff.login.php");
    exit();
}

require "connection.php";

$uid = $_SESSION["userID"];

// Function to format the date and time
function formatDateTime($datetime) {
    $formattedDateTime = new DateTime($datetime);
    return $formattedDateTime->format("F jS Y h:i:s A");
}

// Fetch upcoming appointments data
$upcomingAppointmentsSql = "SELECT CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, c.Date_Time, c.Patient_SSN
                   FROM consultation c 
                   JOIN patient p ON c.Patient_SSN = p.SSN 
                   WHERE c.Date_Time >= NOW()";

$upcomingAppointmentsStmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($upcomingAppointmentsStmt, $upcomingAppointmentsSql)) {
    echo "Statement preparation error: " . mysqli_error($conn);
    exit();
}

mysqli_stmt_execute($upcomingAppointmentsStmt);
$upcomingAppointmentsResult = mysqli_stmt_get_result($upcomingAppointmentsStmt);

// Search functionality
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];

    $searchAppointmentsSql = "SELECT CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, c.Date_Time , c.Patient_SSN
                       FROM consultation c 
                       JOIN patient p ON c.Patient_SSN = p.SSN 
                       WHERE (p.F_Name LIKE ? OR p.SSN LIKE ?) AND c.Date_Time >= NOW()";

    $searchAppointmentsStmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($searchAppointmentsStmt, $searchAppointmentsSql)) {
        echo "Statement preparation error: " . mysqli_error($conn);
        exit();
    }

    $searchTerm = '%' . $searchTerm . '%';
    mysqli_stmt_bind_param($searchAppointmentsStmt, "ss", $searchTerm, $searchTerm);
    mysqli_stmt_execute($searchAppointmentsStmt);

    $searchAppointmentsResult = mysqli_stmt_get_result($searchAppointmentsStmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="drecords_style.css">
    <title>Appointments</title>
    <style>
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

        /* Add this CSS for the button */
        button.edit {
            background: none;
            border: none;
            color: blue; /* or your preferred color */
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }

    </style>

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
        <form method="post">
            <div class="search-container">
            <input type="text" id="searchTerm" name="searchTerm" placeholder="Enter Patient IC" class="search-input" required>
            <button type="submit" name="search" class="search-button">Search</button>
        </div>
    </form>
</div>

<div class='welcome'><h2 class='mssg'>Upcoming Appointments</h2></div>
    <div class='table_box'>
        <table class='content-table'>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        <?php
        $appointmentsToDisplay = isset($searchAppointmentsResult) ? $searchAppointmentsResult : $upcomingAppointmentsResult;

        while ($appointment = mysqli_fetch_assoc($appointmentsToDisplay)) {
            echo "<tr>";
            echo "<td>" . formatDateTime($appointment['Date_Time']) . "</td>";
            echo "<td>" . $appointment['Patient_SSN'] . "</td>";
            echo "<td>" . $appointment['patient_fullname'] . "</td>";
            echo "<td>
                    <form action='sedit_appointment.php' method='get'>
                        <input type='hidden' name='datetime' value='" . urlencode($appointment['Date_Time']) . "'>
                        <button type='submit' class='edit' name='edit'>Edit</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        
        ?>
    </table>

    <div class='welcome'><h2 class='mssg'>Past Appointments</h2></div>
    <div class='table_box'>
        <table class='content-table'>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                </tr>
            </thead>
            <tbody>
        <?php
        $pastAppointmentsSql = "SELECT CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, c.Date_Time , c.Patient_SSN
                       FROM consultation c 
                       JOIN patient p ON c.Patient_SSN = p.SSN 
                       WHERE c.Date_Time < NOW()";

        $pastAppointmentsStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($pastAppointmentsStmt, $pastAppointmentsSql)) {
            echo "Statement preparation error: " . mysqli_error($conn);
            exit();
        }

        mysqli_stmt_execute($pastAppointmentsStmt);
        $pastAppointmentsResult = mysqli_stmt_get_result($pastAppointmentsStmt);

        while ($pastAppointment = mysqli_fetch_assoc($pastAppointmentsResult)) {
            echo "<tr>";
            echo "<td>" . formatDateTime($pastAppointment['Date_Time']) . "</td>";
            echo "<td>" . $pastAppointment['Patient_SSN'] . "</td>";
            echo "<td>" . $pastAppointment['patient_fullname'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>