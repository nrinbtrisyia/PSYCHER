<!DOCTYPE html>
<html>

<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="login_style.css">
  <title>Login</title>
</head>

<body style="background-image: url(Resource/H.jpg); background-size: cover;">

  <div class="login-container">
    <form class="login-form" action="patient_login.process.php" method="post">
      <h2>Psychiatric Patient Health Record</h2>
      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] == "emptyfields") {
          echo "<p class='login-error'>Fill in all the fields</p>";
        } else if ($_GET["error"] == "wrongpass") {
          echo "<p class='login-error'>Password does not match</p>";
        } else if ($_GET["error"] == "nouser") {
          echo "<p class='login-error'>No User Found!</p>";
        }
      } else if (isset($_GET["login"])) {
        if ($_GET["login"] == "success") {
          echo "<p class='login-success'>Login Successful!</p>";
        }
      }
      ?>

      <label for="userID">IC Number</label>
      <input type="text" name="userID" placeholder="Enter your SSN, NID number, or Passport Number">
      <label for="pass">Password</label>
      <input type="password" name="pass" placeholder="Enter your Password">
      <input type="submit" name="login-submit" value="Login">
    </form>
  </div>

</body>

</html>
