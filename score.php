<?php
session_start();
include 'connection.php'; // Make sure this points to your actual database connection file

if (!isset($_SESSION["userID"])) {
    header("Location: patient.login.php");
    exit();
}

// Retrieve patient's name from the database
$ssn = $_SESSION["userID"];
$query = "SELECT F_Name, L_Name FROM patient WHERE SSN = ?";
$patientName = '';

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("s", $ssn);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);
    if ($stmt->fetch()) {
        $patientName = htmlspecialchars($firstName . ' ' . $lastName);
    }
    $stmt->close();
} else {
    echo "Query error: " . $conn->error;
}

// Check if the score is set in the session. If not, redirect to the dashboard.
if (!isset($_SESSION['score'])) {
    header("Location: pdashboard.php");
    exit();
}

// Retrieve the score from the session and clear it from the session
$score = $_SESSION['score'];
unset($_SESSION['score']);

// Function to interpret the score into a meaningful result
function interpret_score($score) {
    if ($score <= 5) {
        return "Normal Stress or Mild Mood Fluctuations";
    } elseif ($score >= 6 && $score <= 14) {
        return "Moderate Depression/Anxiety";
    } elseif ($score >= 15 && $score <= 22) {
        return "Severe Depression/Anxiety";
    } else {
        return "Urgent Attention Needed - Possible Severe Condition";
    }
}

// Interpret the score
$result = interpret_score($score);
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="pdash_style.css">
    <link rel="stylesheet" type="text/css" href="questionnaire_style.css">
    <title>Mental Health Result</title>
    <style>
        /* Styles for the questionnaire section */
        .questionnaire-container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .questionnaire-container h2 {
            text-align: center;
            color: #333;
        }

        .questionnaire-container form {
            max-width: 500px;
            margin: 0 auto;
        }

        .questionnaire-container label {
            display: block;
            margin-bottom: 10px;
        }

        .questionnaire-container select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
        }

        .questionnaire-container button {
            background-color: black;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .questionnaire-container button:hover {
            background-color: grey;
        }

        .header {
            text-align: center;
            margin-top: 20px;
        }

        .mental-health-awareness img {
            width: 50%; /* Adjust size as needed */
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-message {
            text-align: center;
            margin-top: 20px;
            font-size: 20px; /* Increased font size */
            font-weight: bold; /* Make text bold */
        }

        /* Additional styles for mental health awareness section */
        .mental-health-awareness {
            background-color: #php_strip_whitespace;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
            border-radius: 8px;
        }

        .mental-health-awareness h2 {
            font-family: "Nunito", sans-serif;
            color: #333;
            margin-bottom: 10px;
        }

        .mental-health-awareness p {
            font-family: "Questrial", sans-serif;
            color: #555;
            font-size: 18px;
        }

        .profile-section {
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: center;
        }

        .profile-section img {
            width: 50px;
            height: 50px;
        }

        .profile-section p {
            margin-top: 5px;
            font-size: 14px;
        }

        .score-container {
    width: 60%;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.score-container h2 {
    color: #333;
}

.score-container p {
    font-size: 18px;
    margin-bottom: 10px;
}

.score-container a {
    background-color: black;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none; /* Remove underline from the link */
    display: inline-block; /* Make the link behave as a block element */
    margin-top: 20px;
}

.score-container a:hover {
    background-color: grey;
}
    </style>
</head>
<body>
    <div class="header">
        <h1>PsychER</h1>
        <h2>Psychiatric Patient Health Record</h2>
    </div>

    <div class="profile-section">
        <a href="pprofile.php">
            <img class="profile-icon" src="resource/profile.png" alt="Profile Icon" />
        </a>
        <p><?php echo $patientName; ?></p>
    </div>

    <div class="navigation-bar" style="text-align: center">
        <a href="pdashboard.php">Home</a>
        <a href="precords.php">Records</a>
        <a href="pprofile.php">Profile</a>
        <a class='logout' href="logout.php">Logout</a>
    </div>

    <div class="welcome-message">
        <p>Welcome, <?php echo $patientName; ?></p>
    </div>

    <div class="mental-health-awareness">
    
    <h2>Your Mental Health Matters</h2>
    <p>At PsychER, we understand the journey of mental health. Here you can track your progress, access your records, and engage with our supportive community.</p>
    <img src="Resource/homepage.jpg" alt="" />
  </div>

    <div class="score-container">
        <h2>Questionnaire Results</h2>
        <p>Your Score: <?php echo htmlspecialchars($score); ?></p>
        <p>Interpretation: <?php echo htmlspecialchars($result); ?></p>
        <a href="pdashboard.php">Back to Dashboard</a>
    </div>

</body>
</html>