<?php
session_start();
require "connection.php";

$uid = $_SESSION["userID"];

function formatDateTime($datetime)
{
    return date("Y-m-d H:i", strtotime($datetime));
}

// Function to check availability
function isSlotAvailable($conn, $dateTime)
{
    $checkAvailabilitySql = "SELECT COUNT(*) AS appointment_count FROM consultation WHERE Date_Time = ?";
    $checkAvailabilityStmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($checkAvailabilityStmt, $checkAvailabilitySql)) {
        echo "Statement preparation error: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($checkAvailabilityStmt, "s", $dateTime);
    mysqli_stmt_execute($checkAvailabilityStmt);
    $result = mysqli_stmt_get_result($checkAvailabilityStmt);
    $row = mysqli_fetch_assoc($result);

    // Assuming the maximum allowed appointments is 50
    return $row['appointment_count'] <= 50;
}

if (isset($_GET['datetime'])) {
    $datetime = urldecode($_GET['datetime']);

    // Fetch appointment details for the selected datetime
    $getAppointmentDetailsSql = "SELECT CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname, c.Date_Time 
                                 FROM consultation c 
                                 JOIN patient p ON c.Patient_SSN = p.SSN 
                                 WHERE c.Date_Time = ?";

    $getAppointmentDetailsStmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($getAppointmentDetailsStmt, $getAppointmentDetailsSql)) {
        echo "Statement preparation error: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($getAppointmentDetailsStmt, "s", $datetime);
    mysqli_stmt_execute($getAppointmentDetailsStmt);
    $appointmentDetailsResult = mysqli_stmt_get_result($getAppointmentDetailsStmt);

    if ($appointment = mysqli_fetch_assoc($appointmentDetailsResult)) {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="dinsert_style.css">
            <title>Edit Appointment</title>
            <STYLE>
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

        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 300px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button, input[type="submit"] {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #45a049;
        }
            </STYLE>
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
            </div>

            <?php
            if (isset($_POST['submit'])) {
                $newDateTime = $_POST['dateTime'];
                $originalDateTime = $_POST['originalDateTime'];

                // Check availability for the selected date and time
                if (isSlotAvailable($conn, $newDateTime)) {
                    // Slot is available, proceed with the update
                    $updateAppointmentSql = "UPDATE consultation
                                             SET Date_Time = ?
                                             WHERE Date_Time = ?";

                    $updateAppointmentStmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($updateAppointmentStmt, $updateAppointmentSql)) {
                        echo "Statement preparation error: " . mysqli_error($conn);
                        exit();
                    }

                    mysqli_stmt_bind_param($updateAppointmentStmt, "ss", $newDateTime, $originalDateTime);

                    if (mysqli_stmt_execute($updateAppointmentStmt)) {
                        $_SESSION['successMessage'] = 'Successfully rescheduled';
                        echo '<script>alert("Successfully Rescheduled"); window.location.href = "staffappointments.php";</script>';
                        exit();
                    } else {
                        echo "Error updating appointment: " . mysqli_error($conn);
                    }

                    mysqli_stmt_close($updateAppointmentStmt);
                } else {
                    // Slot is not available
                    echo '<p>Selected slot is not available.</p>';
                }
            }
            ?>

<div class='welcome'><h2 class='welcome_mssg'></h2></div>
    <body>
        <h2>Edit Appointment</h2>

<form method="POST">
    <input type="hidden" name="originalDateTime" value="<?= $datetime ?>">
    
    <label for="patientName">Patient Name:</label>
    <input type="text" id="patientName" name="patientName" value="<?= $appointment['patient_fullname'] ?>" readonly>

    <label for="dateTime">Date and Time:</label>
    <input type="datetime-local" id="dateTime" name="dateTime" value="<?= date('Y-m-d\TH:i', strtotime($appointment['Date_Time'])) ?>">

    <input type="submit" name="submit" value="Update"></input>

    <!-- Add the cancel button and confirmation script -->
    <button type="button" onclick="confirmCancel()">Cancel</button>

    <script>
        function confirmCancel() {
            var confirmCancel = confirm("Are you sure you want to cancel?");
            if (confirmCancel) {
                // Redirect to staffappointments.php after canceling
                window.location.href = "staffappointments.php";

            } else {
                // Do nothing if canceled
            }
        }
    </script>
</form>

        </body>

        </html>
        <?php
    } else {
        echo "Appointment not found.";
    }

    mysqli_stmt_close($getAppointmentDetailsStmt);
} else {
    echo "Datetime parameter not provided.";
}

mysqli_close($conn);
?>