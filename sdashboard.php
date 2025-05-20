<?php
session_start();
if (!$_SESSION["userID"]) {
    header("Location: staff.login.php");
    exit();
}

require "connection.php";

$uid = $_SESSION["userID"];
$sql = "SELECT SSN, F_Name, CONCAT(F_Name,' ',L_Name) AS Full_name, Contact_No, d.Email, h.name, d.Address, Department, Designation FROM medical_staff d, hospital h WHERE d.Hospital_ID=h.ID AND SSN=?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Statement preparation error: " . mysqli_error($conn);
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $ssn = $row["SSN"];
        $fname = strtoupper($row["F_Name"]);

        // Explicitly set the timezone to Malaysia
        date_default_timezone_set('Asia/Kuala_Lumpur');

        // Fetch the current date
        $currentDate = date("d/m/y");

        // Fetch staff appointments data from consultation table
        $appointmentsSql = "SELECT CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, c.PatientName, c.Date_Time 
                   FROM consultation c 
                   JOIN patient p ON c.Patient_SSN = p.SSN 
                   WHERE c.Date_Time >= ? AND c.Date_Time < DATE_ADD(?, INTERVAL 1 DAY)
                   AND c.Date_Time >= NOW()  -- Add this condition to filter out past appointments
                   ORDER BY c.Date_Time";
        $appointmentsStmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($appointmentsStmt, $appointmentsSql)) {
            echo "Statement preparation error: " . mysqli_error($conn);
            exit();
        }

        mysqli_stmt_bind_param($appointmentsStmt, "ss", $currentDate, $currentDate);
        mysqli_stmt_execute($appointmentsStmt);

        if (mysqli_stmt_error($appointmentsStmt)) {
            echo "Statement execution error: " . mysqli_stmt_error($appointmentsStmt);
            exit();
        }

        $appointmentsResult = mysqli_stmt_get_result($appointmentsStmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="ddash_style.css">
    <title>Dashboard</title>
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

        .clock {
            text-align: right;
            margin-top: 10px;
            margin-right: 10px;
            font-size: 18px;
            display: inline-block;
        }

        .clock div {
            margin-bottom: 5px;
            border: 2px solid #000000; /* Add border to each clock div */
            padding: 5px;
            border-radius: 5px;
            display: inline-block;
            margin-left: 5px; /* Add margin to separate clock divs */
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

    <div class='welcome'>
        <h2 class='welcome_mssg'>GREETINGS <?php echo $fname; ?></h2>
    </div>

    <div class="clock">
        <div>Time: <?php echo date("h:i A"); ?></div>
        <div>Date: <?php echo $currentDate; ?></div>
    </div>

    <div class='pi_box'>
        <div class='pi_table'>
            <h3>Patient Appointment List</h3>
            <table>
                <thead>
                    <th>Patient Name</th>
                    <th>Time</th>
                </thead>
                <?php
                while ($appointment = mysqli_fetch_assoc($appointmentsResult)) {
                    echo "<tr>";
                    echo "<td>" . $appointment['patient_fullname'] . "</td>";
                    // Format the time as needed, including "AM" or "PM"
                    $formattedTime = date("h:i A", strtotime($appointment['Date_Time']));
                    echo "<td>" . $formattedTime . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
