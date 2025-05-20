<?php
session_start();
if (!$_SESSION["userID"]) {
    header("Location:staff.login.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
        <link rel="manifest" href="Resource/favicon/site.webmanifest">
        <link rel="stylesheet" type="text/css" href="aregister_style.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />

        <title> Register </title>
    </head>
    <body>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
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
        </style>
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

        <script>
            jQuery(function ($) {
                $("#dob").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "1920:2020"
                });
            });
        </script>

        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "wronguser") {
                echo "<div class='welcome' style='color: #D61A3C'><h2 class='welcome_mssg'> Wrong Patient ID </h2></div>";
            } elseif ($_GET["error"] == "wronghid") {
                echo "<div class='welcome' style='color: #D61A3C'><h2 class='welcome_mssg'> Wrong Hospital ID </h2></div>";
            }
        } elseif (isset($_GET["login"])) {
            if ($_GET["login"] == "success") {
                echo '<script>alert("Registration Successful"); window.location.href = "aregister.php";</script>';
            }
        } elseif (!isset($_POST["choice-submit"])) {
            echo "<div class='welcome'><h2 class='welcome_mssg'> Choose a Category</h2></div>
      <div class='choice_form_box'>
        <form class='choice_form' action='aregister.php' method='post'>
          <label for='ch'>Patient</label>
          <input type='checkbox' name='ch' value='1'><br>
          <label for='ch'>Doctor</label>
          <input type='checkbox' name='ch' value='2'><br>
          <label for='ch'>Staff</label>
          <input type='checkbox' name='ch' value='3'><br>
          <input type='submit' name='choice-submit' value='NEXT'>
        </form>
      </div>";
        } else {
            if ($_POST["ch"] == "1") {
                echo "<div class='welcome'><h2 class='welcome_mssg'> Patient Registration Form</h2></div>
  <div class='input-form-box'>
    <form class='input-form' action='aregister_p.php' method='post'>
    
    <label for='pssn'>Patient ID</label>
    <input type='text' name='pssn' pattern='\d{12}' title='Please enter a valid 12-digit Patient ID' placeholder='Enter a valid Patient ID' required autofocus><br>    

      <label for='fname'>First Name</label>
      <input type='text' name='fname' placeholder='Enter First Name' required><br>


      <label for='lname'>Last Name</label>
      <input type='text' name='lname' placeholder='Enter Last Name' required><br>


      <label for='adr'>Address</label>
      <input type='text' name='adr' placeholder='Current Address' required ><br>

      <label for='cno'>Contact No.</label>
      <input type='text' name='cno' pattern='\d{10,11}' title='Please enter a valid Contact No.' placeholder='Contact No. without -' required><br>

      <label for='mail'>Email</label>
      <input type='text' name='mail' placeholder='Email' required;><br>

      <label for='dob'>Date of Birth</label>
      <input type='text' name='dob' id='dob' placeholder='Date of birth' required><br>

      <label for='gen'>Gender</label>
      <select name='gen' required>
        <option value='Male'>Male</option>
        <option value='Female'>Female</option>
      </select><br>

      <label for='pass'>Password</label>
      <input type='text' name='pass' placeholder='Password' required><br>

      <input type='submit' name='input-submit' value='Save'>
    </form>

  </div>";
            } elseif ($_POST["ch"] == "2") {
                echo " <div class='welcome'><h2 class='welcome_mssg'> Doctor Registration Form</h2></div>
    <div class='input-form-box'>
      <form class='input-form' action='aregister_d.php' method='post'>
        <label for='dssn'>Doctor ID</label>
        <input type='text' name='dssn' placeholder='Enter a valid Doctor ID' required autofocus><br>

        <label for='fname'>First Name</label>
        <input type='text' name='fname' placeholder='Enter First Name' required><br>


        <label for='lname'>Last Name</label>
        <input type='text' name='lname' placeholder='Enter Last Name' required><br>

        <label for='hid'>Hospital ID</label>
        <input type='text' name='hid' placeholder='Hospital ID' required ><br>

        <label for='adr'>Address</label>
        <input type='text' name='adr' placeholder='Current Address' required ><br>

        <label for='cno'>Contact No.</label>
        <input type='text' name='cno' pattern='\d{10,11}' title='Please enter a valid digit Contact No.' placeholder='Contact No. without -' required><br>

        <label for='mail'>Email</label>
        <input type='text' name='mail' placeholder='Email' required><br>

        <label for='dep'>Department</label>
        <input type='text' name='dep' placeholder='Department' required><br>

        <label for='spec'>Speciality</label>
        <input type='text' name='spec' placeholder='Specialization' required><br>

        <label for='desg'>Designation</label>
        <input type='text' name='desg' placeholder='Designation' required><br>

        <label for='desg'>Password</label>
        <input type='text' name='pass' placeholder='Password' required><br>

        <input type='submit' name='input-submit' value='Save'>
      </form>

    </div>";
            } elseif ($_POST["ch"] == "3") {
                echo "  <div class='welcome'><h2 class='welcome_mssg'> Staff Registration Form</h2></div>
  <div class='input-form-box'>
    <form class='input-form' action='aregister_s.php' method='post'>
      <label for='sssn'>Staff ID</label>
      <input type='text' name='sssn' placeholder='Enter a valid Staff ID' required autofocus><br>

      <label for='fname'>First Name</label>
      <input type='text' name='fname' placeholder='Enter First Name' required><br>


      <label for='lname'>Last Name</label>
      <input type='text' name='lname' placeholder='Enter Last Name' required><br>

      <label for='hid'>Hospital ID</label>
      <input type='text' name='hid' placeholder='Hospital ID' required ><br>

      <label for='adr'>Address</label>
      <input type='text' name='adr' placeholder='Current Address' required ><br>

      <label for='cno'>Contact No.</label>
      <input type='text' name='cno' pattern='\d{10,11}' title='Please enter a valid Contact No.' placeholder='Contact No. without -' required><br>

      <label for='mail'>Email</label>
      <input type='text' name='mail' placeholder='Email' required><br>

      <label for='dep'>Department</label>
      <input type='text' name='dep' placeholder='Department' required><br>

      <label for='desg'>Designation</label>
      <input type='text' name='desg' placeholder='Designation' required><br>

      <label for='desg'>Password</label>
      <input type='text' name='pass' placeholder='Password' required><br>

      <input type='submit' name='input-submit' value='Save'>
    </form>

  </div>";
            }
        }
        ?>



    </body>
</html>
