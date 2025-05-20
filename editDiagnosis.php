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

// Query the medical administration record to edit
$medAdminSql = "SELECT * FROM medical_administration WHERE CONCAT(Patient_SSN, Doctor_SSN, DATE_FORMAT(Date_Time, '%Y%m%d%s%i%k')) = ?";
$medAdminStmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($medAdminStmt, $medAdminSql)) {
    echo "Error preparing SQL statement.";
    exit();
}

mysqli_stmt_bind_param($medAdminStmt, "s", $reference);
mysqli_stmt_execute($medAdminStmt);
$result = mysqli_stmt_get_result($medAdminStmt);
$record = mysqli_fetch_assoc($result);

if (!$record) {
    echo "Medical administration record not found.";
    exit();
}

// Check if form is submitted and process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dateTime = sanitizeInput($_POST['dateTime']);
    $description = sanitizeInput($_POST['description']);
    $complication = sanitizeInput($_POST['complication']);
    $meds = sanitizeInput($_POST['meds']);
    $allergies = sanitizeInput($_POST['allergies']);

    $updateSql = "UPDATE medical_administration SET Date_Time = ?, Description = ?, Complication = ?, Medicine = ?, Allergies = ? WHERE CONCAT(Patient_SSN, Doctor_SSN, DATE_FORMAT(Date_Time, '%Y%m%d%s%i%k')) = ?";
    $updateStmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($updateStmt, $updateSql)) {
        echo "Error preparing update statement.";
        exit();
    }
    mysqli_stmt_bind_param($updateStmt, "ssssss", $dateTime, $description, $complication, $meds, $allergies, $reference);
    if (mysqli_stmt_execute($updateStmt)) {
        $_SESSION['update_success'] = true;
    } else {
        echo "Error updating record.";
    }
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
    .navigation-bar a {
        display: inline-block;
        font-size: 15px;
        text-decoration: none;
        color: #ece2c1;
        padding: 30px 15px;
    }
    .header {
        text-align: center;
        margin: 20px;
    }
    .doctor-info {
        position: absolute;
        top: 10px;
        right: 10px;
        text-align: center;
    }
    .doctor-info img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    .doctor-info h2 {
        margin-top: 5px;
        font-size: 14px;
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
    </style>
    <script type="text/javascript">
    function showUpdateSuccess() {
        alert("Record updated successfully.");
        window.location = 'drecords.php';
    }
    </script>
</head>
<body>
    <title>Edit Diagnosis Record</title>
</head>
<body>
    <h1>PsychER</h1>
    <h2>Psychiatric Patient Health Record</h2>
    <div class="navigation-bar">
        <a href="ddashboard.php">Home</a>
        <a href="drecords.php">Records</a>
        <a href="dinsert.php">Insert</a>
        <a href="dprofile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class='welcome'><h2 class='welcome_mssg'></h2></div>
    <body>
        <h2>Edit Diagnosis Record</h2>
        <form method="POST">
            <!-- Patient Name (Readonly) -->
            <label for="patientName">Patient Name:</label>
            <input type="text" id="patientName" name="patientName" value="<?php echo htmlspecialchars($record['Patient_SSN']); ?>" readonly>

            <label for="dateTime">Date & Time:</label>
            <input type="datetime-local" id="dateTime" name="dateTime" value="<?php echo date('Y-m-d\TH:i', strtotime($record['Date_Time'])); ?>">

            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $record['Description']; ?></textarea>

            <label for="complication">Complication:</label>
            <textarea id="complication" name="complication"><?php echo $record['Complication']; ?></textarea>

            <label for="meds">Medicines:</label>
            <textarea id="meds" name="meds"><?php echo $record['Medicine']; ?></textarea>

            <label for="allergies">Allergies:</label>
            <textarea id="allergies" name="allergies"><?php echo $record['Allergies']; ?></textarea>

            <input type="submit" value="Update Record">
        </form>

        <?php
        if (isset($_SESSION['update_success']) && $_SESSION['update_success'] == true) {
            echo '<script type="text/javascript">showUpdateSuccess();</script>';
            unset($_SESSION['update_success']);
        }
        ?>
    </body>
</body>
</html>
