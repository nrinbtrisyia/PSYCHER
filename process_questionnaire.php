<?php
session_start();
require "connection.php"; // Ensure this points to your database connection file

// Redirect if the user is not logged in
if (!isset($_SESSION["userID"])) {
    header("Location: patient.login.php");
    exit();
}

// Function to calculate the score based on questionnaire responses
function calculate_score($q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10) {
    $score = 0;

    // Scoring logic for Q1
    if ($q1 == 'normal') {
        $score += 1;
    } elseif ($q1 == 'not-well') {
        $score += 2;
    }

    // Scoring logic for Q2 to Q10
    $negative_symptoms = ['yes' => ['q2', 'q3', 'q5', 'q6', 'q7', 'q9', 'q10'], 'no' => ['q4', 'q8']];
    foreach ($negative_symptoms as $response => $questions) {
        foreach ($questions as $question) {
            if (${$question} == $response) {
                $score += 2;
            }
        }
    }

    return $score;
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_SESSION["userID"];
    
    // Retrieve questionnaire responses
    $q1 = $_POST["q1"] ?? 'default_value';
    $q2 = $_POST["q2"] ?? 'default_value';
    $q3 = $_POST["q3"] ?? 'default_value';
    $q4 = $_POST["q4"] ?? 'default_value';
    $q5 = $_POST["q5"] ?? 'default_value';
    $q6 = $_POST["q6"] ?? 'default_value';
    $q7 = $_POST["q7"] ?? 'default_value';
    $q8 = $_POST["q8"] ?? 'default_value';
    $q9 = $_POST["q9"] ?? 'default_value';
    $q10 = $_POST["q10"] ?? 'default_value';
    // ... Retrieve other questions similarly ...

    // Calculate the score
    $score = calculate_score($q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10);

    // Insert the questionnaire data and the score into the database
    $sql = "INSERT INTO questionnaire_responses (Patient_SSN, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssssssi", $uid, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $score);
        if (!mysqli_stmt_execute($stmt)) {
            // Handle SQL execution error
            echo "Error executing query: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle SQL preparation error
        echo "Error preparing query: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);

    // Store the score in a session and redirect to score.php
    $_SESSION['score'] = $score;
    header("Location: score.php");
    exit();
} else {
    // Redirect if accessed without form submission
    header("Location: pdashboard.php");
    exit();
}
?>
