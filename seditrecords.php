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

// Retrieve patient SSN and datetime from form
$patientSSN = isset($_GET['patientSSN']) ? sanitizeInput($_GET['patientSSN']) : '';
$dateTime = isset($_GET['dateTime']) ? sanitizeInput($_GET['dateTime']) : '';
$dateTime = date('Y-m-d H:i:s', strtotime($dateTime));

// Query patient information
$patientSql = "SELECT * FROM patient WHERE SSN = ?";
$patientStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($patientStmt, $patientSql)) {
    die('Error in patient SQL query: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($patientStmt, "s", $patientSSN);
mysqli_stmt_execute($patientStmt);
$patientResult = mysqli_stmt_get_result($patientStmt);
$patientData = mysqli_fetch_assoc($patientResult);

// Check if patient data is retrieved successfully
if (!$patientData) {
    die('Patient data not found.');
}

// Query the consultation records for the selected patient SSN and datetime
$consultationSql = "SELECT * FROM consultation WHERE Patient_SSN = ? AND Date_Time = ?";
$consultationStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($consultationStmt, $consultationSql)) {
    die('Error in consultation SQL query: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($consultationStmt, "ss", $patientSSN, $dateTime);
mysqli_stmt_execute($consultationStmt);
$consultationResult = mysqli_stmt_get_result($consultationStmt);

// Fetch the first row (if exists) as an associative array
$consultationData = mysqli_fetch_assoc($consultationResult);

// Check if consultation data is retrieved successfully
if (!$consultationData) {
    die('Consultation data not found.');
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
    <link rel="stylesheet" type="text/css" href="dinsert_style.css">
    <style>
        
    .wrapper {
        width: 50%;
        margin: auto;
        margin-bottom: 15px;
    }

    .psycher{
        text-align: center;
        margin: 20px;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .input-group {
        margin-bottom: 25px;
    }

    .input-group label {
        display: inline-block;
        width: 150px;
    }

    .input-group input,
    .input-group textarea,
    .input-group select {
        width: calc(100% - 160px);
        padding: 8px;
        box-sizing: border-box;
    }

    textarea {
        font-size: 70%;
    }

    textarea:focus {
        font-size: inherit;
    }

    .input-group input[readonly],
    .input-group textarea[readonly],
    .input-group select[readonly] {
        background-color: #ddd; /* Darker color for readonly fields */
        color: #333;
    }

    input[type="submit"] {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

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
    </style>
    
    <title>Edit</title>
</head>
<body>

        <div class="psycher">
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

    <div class='welcome'><h2 class='welcome_mssg'></h2></div>
    <body>
        <h2>Edit Records</h2>

<div class="wrapper">
    <div class="container">
        <form class="ci_edit_form" action="supdaterecords.php" method="post">
            <div class="input-group">
                <label for="patientSSN">Patient ID:</label>
                <input type="text" id="patientSSN" name="patientSSN" value="<?php echo $patientData['SSN']; ?>" readonly>
            </div>

            <div class="input-group">
                <label for="patientName">Patient Name:</label>
                <input type="text" id="patientName" name="patientName" value="<?php echo $patientData['F_Name'] . ' ' . $patientData['L_Name']; ?>" readonly>
            </div>

            <div class="input-group">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" value="<?php echo date('Y-m-d', strtotime($consultationData['Date_Time'])); ?>"readonly>

                <label for="time">Time:</label>
                <input type="time" name="time" id="time" value="<?php echo date('H:i:s', strtotime($consultationData['Date_Time'])); ?>"readonly>
            </div>

            <div class="input-group">
                <label for="complications">Complications:</label>
                <input type="text" id="complications" name="complications" value="<?php echo $consultationData['Complications']; ?>" readonly>
            </div>

            <div class="input-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo $consultationData['Description1']; ?></textarea>
            </div>

            <div class="input-group">
                <label for="treatments">Treatments:</label>
                <textarea id="treatments" name="treatments"><?php echo $consultationData['Treatments']; ?></textarea>
            </div>

            <div class="input-group">
                <label for="medicine">Medicine:</label>
                <textarea id="medicine" name="medicine"><?php echo $consultationData['Medicines']; ?></textarea>
            </div>

            <!-- Add other input groups as needed -->

            <input type="hidden" name="patientID" value="<?php echo $patientSSN; ?>">
            <input type="submit" name="info-submit" value="Update Record">
        </form>
    </div>
</div>

</body>
</html>
