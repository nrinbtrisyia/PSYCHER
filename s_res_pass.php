<?php
session_start();
if (!$_SESSION["userID"])
    header("Location:staff.login.php")
    ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
        <link rel="manifest" href="Resource/favicon/site.webmanifest">
        <link rel="stylesheet" type="text/css" href="resetPass.css">
        <title>Reset</title>
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

        <div class="reset-password-container">
            <h2>Reset Password</h2>
                <form class="ci_edit_form" action="s_res_pass.php" method="post">

                    <div class="form-group">
                    <label for="currentPassword">Current Password:</label>
                    <input type="password" name="cp" placeholder="Current Password" required><br>
                    </div>
                   
                    <div class="form-group">
                    <label for="confirmNewPassword">Confirm New Password:</label>
                    <input type="password" name="np" placeholder="New Password" required><br>
                    </div>
                    <div class="form-group">
                    <label for="confirmNewPassword">Confirm New Password:</label>
                    <input type="password" name="npr" placeholder="Re-enter Password" required>
                    </div>
                    <input class="reset-btn" type="submit" name="info-submit" value="Reset Password">
                </form>
            </div>
        </div>

        <?php
        require "connection.php";
        if (isset($_POST["info-submit"])) {
            if (!empty($_POST["cp"]) && !empty($_POST["np"]) && !empty($_POST["npr"])) {
                $uid = $_SESSION["userID"];
                $cp = $_POST["cp"];
                $np = $_POST["np"];
                $npr = $_POST["npr"];

                $sql = "SELECT pass FROM staff_login WHERE s_ssn = '$uid'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                if ($cp == $row["pass"]) {
                    if ($np == $npr) {
                        $sql = "UPDATE staff_login SET pass= '$np' WHERE s_ssn='$uid'";
                        $is_updated = mysqli_query($conn, $sql);
                        if ($is_updated) {
                            echo '<script>alert("Password Updated Successfully"); window.location.href = "sprofile.php";</script>';
                        } else {
                            header("Location:s_res_pass.php?Error");
                            exit();
                        }
                    } else {
                        echo '<script>alert("Please Enter Same Password"); window.location.href = "s_res_pass.php";</script>';
                    }
                } else {
                    echo '<script>alert("Current Password Does Not Match"); window.location.href = "s_res_pass.php";</script>';
                        
                }
            }
        }
        ?>

        <div class="footer"></div>
    </body>
</html>
