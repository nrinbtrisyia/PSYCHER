<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location: doctor.login.php");
    exit;
}
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

if (isset($_SESSION['doctorFullName'])) {
    $doctorFullName = htmlspecialchars($_SESSION['doctorFullName']);
} else {
    $doctorFullName = htmlspecialchars($doctorDetails["Full_name"]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
    <link rel="stylesheet" type="text/css" href="dinsert_style.css">
    <title>Insert</title>

    <!-- jQuery and jQuery UI Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
</head>

<body>


    <div class="header">
        <div>
            <h1>PsychER</h1>
            <h2>Psychiatric Patient Health Record</h2>
        </div>
        <div class="doctor-info">
            <img src="Resource/rosliza.jpg">
            <h2><?php echo $doctorFullName; ?></h2>
        </div>
    </div>

    <div class="navigation-bar">
        <a href="ddashboard.php">Home</a>
        <a href="drecords.php">Records</a>
        <a href="dinsert.php">Insert</a>
        <a href="dprofile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

   
    <?php
// PHP logic for handling the form submission, displaying messages, etc.
if (isset($_GET["error"])) {
    if ($_GET["error"] == "wronguser") {
        echo "<div class='welcome' style='color: #D61A3C'><h2 class='welcome_mssg'>Wrong Patient ID</h2></div>";
    }
} else if (isset($_GET["success"])) {
    // Check if the form is submitted successfully
    if ($_GET["success"] == "inserted") {
        // Display a popup message using JavaScript
        echo "<script>
                window.onload = function() {
                    if (confirm('Record Saved Successfully. Click OK to go to Records.')) {
                        // Redirect to drecords.php
                        window.location.href = 'drecords.php';
                    }
                }
              </script>";
    } 

    
} elseif (!isset($_POST["choice-submit"])) {
    echo "<div class='welcome'><h2 class='welcome_mssg'> Choose a Category</h2></div>
    <div class='choice_form_box'>
        <form class='choice_form' action='dinsert.php' method='post'>
        <br>
            <label for='ch'>Consultation</label>
            <input type='checkbox' name='ch' value='1'><br>
            <br>
            <label for='ch'>Diagnosis</label>
            <input type='checkbox' name='ch' value='3'><br>
            <input type='submit' name='choice-submit' value='NEXT'>
        </form>
    </div>";
} else {
    if ($_POST["ch"] == "1") {
        echo "<div class='welcome'><h2 class='welcome_mssg'> Consultation Form</h2></div>
        <div class='input-form-box'>
            <form class='input-form' action='dinsert_con.php' method='post'>
                <label for='pssn'>Patient ID</label>
                <input type='text' name='pssn' placeholder='Enter a valid Patient ID' required><br>
                
                <label for='dateTime'>Date & Time:</label>
                <input type='datetime-local' id='dateTime' name='dateTime' required>

                <label for='Complications'>Complications</label>
                <textarea name='Complications' placeholder='Write complications' required></textarea><br>

                <label for='Medicines'>Medicines</label>
                <textarea name='Medicines' placeholder='Write medicine' required></textarea><br>

                <label for='Treatments'>Treatments</label>
                <textarea name='Treatments' placeholder='Treatments if any'></textarea><br>

                <label for='Description'>Description</label>
                <textarea name='Description1' placeholder='Description if any'></textarea><br>

                <input type='submit' name='input-submit' value='Save'>
            </form>
        </div>";
    } elseif ($_POST["ch"] == "3") {
        echo "  <div class='welcome'><h2 class='welcome_mssg'> Diagnosis Form</h2></div>
        <div class='input-form-box'>
            <form class='input-form' action='dinsert_diag.php' method='post'>
                <label for='pssn'>Patient ID</label>
                <input type='text' name='pssn' placeholder='Enter a valid Patient ID' required><br>

                <label for='description'>Description</label>
                <textarea name='description' placeholder='Diagnosis Description' required ></textarea><br>

                <label for='complication'>Complications</label>
                <textarea name='complication' placeholder='Diagnosis Complications if any'></textarea><br>

                <label for='meds'>Medicines</label>
                <textarea name='meds' placeholder='Medicines if any'></textarea><br>

                <label for='allerigies'>Allergies</label>
                <textarea name='allergies' placeholder='Allergies if any'></textarea><br>

                <input type='submit' name='input-submit' value='Save'>
            </form>
        </div>";
    }
}
?>

<script>
    // Automatically set the current date and time in the Diagnosis form
    $(document).ready(function() {
        var currentDate = new Date();
        var dateString = currentDate.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
        $('#dateTime').val(dateString);
    });
</script>
</body>
</html>