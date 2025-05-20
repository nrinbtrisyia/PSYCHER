<?php
require "connection.php"; // Include your database connection file

// Fetch the list of patients from the database
$sql = "SELECT * FROM `patient`";
$result = mysqli_query($conn, $sql);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="css/ddash_style.css">
    <title>List of Patients</title>

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
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black; /* Set the color for dropdown links */
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-content {
            display: block;
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
        <a>Patients</a>
            <div class="dropdown-content">
                <a href="srecords.php">Records</a>
                <a href="staffappointments.php">Appointment</a>
            </div>
        </div>
        <a href="aregister.php">Register</a>
        <a href="sprofile.php">Profile</a>
        <a class='logout' href="logout.php">Logout</a>
    </div>

    <table>
        <thead>
            <th>SSN</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Contact No</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>Gender</th>
        </thead>
        <tbody>
            <?php
            // Loop through the result set and display patient information
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['SSN'] . "</td>";
                echo "<td>" . $row['F_Name'] . "</td>";
                echo "<td>" . $row['L_Name'] . "</td>";
                echo "<td>" . $row['Address'] . "</td>";
                echo "<td>" . $row['Contact_No'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['Date_Of_Birth'] . "</td>";
                echo "<td>" . $row['Gender'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
