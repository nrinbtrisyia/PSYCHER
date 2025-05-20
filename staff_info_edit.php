<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("Location:staff.login.php");
    exit();
}

require "connection.php";
$uid = $_SESSION["userID"];
$profileUpdated = false; // Initialize the variable

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted values
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $address = $_POST['address'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $speciality = $_POST['speciality'];
    $designation = $_POST['designation'];

   // Update query
$update_sql = "UPDATE medical_staff SET F_Name=?, L_Name=?, Address=?, Contact_No=?, Email=?, Department=?, Designation=? WHERE SSN=?";
$update_stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($update_stmt, $update_sql)) {
    header("Location:staff_info_edit.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($update_stmt, "ssssssss", $f_name, $l_name, $address, $contact_no, $email, $department, $designation, $uid);
    if (mysqli_stmt_execute($update_stmt)) {
        $profileUpdated = true;
        // Do not redirect here
    }
}
}

// Fetch existing data for editing
$sql = "SELECT SSN, F_Name, L_Name, Address, Contact_No, Email, Department, Designation FROM medical_staff WHERE SSN=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location:sdashboard.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $doctorDetails = $row; // Assign the details to the $doctorDetails variable
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
     <link rel="stylesheet" type="text/css" href="dprofile_style.css">
     <title>Edit</title>
     <?php if ($profileUpdated): ?>
        <script>
        window.onload = function() {
            alert("Profile Updated Successfully");
            window.location.href = 'sprofile.php'; // Redirect after showing the alert
        };
    </script>
    <?php endif; ?>
     <style>
      h1,h2{
        text-align: center;
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
   </head>
   <body>
   <h1>PsychER</h1>
    <h2>Psychiatric Patient Health Record</h2>
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
     <div class='welcome'>
    <h2 class='welcome_mssg'> GREETINGS <?php echo strtoupper($doctorDetails['F_Name']); ?></h2>
</div>

<div class='profile-section'>
    <div class= "profile-picture">
        <img src="Resource/irma.jpg" alt="Your Profile Picture">
    </div>

    <div class='edit-profile-section'>
        <h2>Edit Profile</h2>
        <form method="post">
            First Name: <input type="text" name="f_name" value="<?php echo $doctorDetails['F_Name']; ?>"><br>
            Last Name: <input type="text" name="l_name" value="<?php echo $doctorDetails['L_Name']; ?>"><br>
            Address: <input type="text" name="address" value="<?php echo $doctorDetails['Address']; ?>"><br>
            Contact Number: <input type="text" name="contact_no" value="<?php echo $doctorDetails['Contact_No']; ?>"><br>
            Email: <input type="email" name="email" value="<?php echo $doctorDetails['Email']; ?>"><br>
            Department: <input type="text" name="department" value="<?php echo $doctorDetails['Department']; ?>"><br>
            Designation: <input type="text" name="designation" value="<?php echo $doctorDetails['Designation']; ?>"><br>
            <input type="submit" value="Update Profile">
            
        </form>
    </div>
</body>
</html>
<div class="forgot-password-button">
    <a href="s_res_pass.php">Forgot Password</a>
</div>

<div class='footer'></div>
</body>
</html>