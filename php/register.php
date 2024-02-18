<?php
// register.php

// Start session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $email = $_POST['email'];
    $uname = $_POST['uname'];
    $psw = $_POST['psw'];

    // Echo to check if data is received
    echo "Received email: " . $email . "<br>";
    echo "Received username: " . $uname . "<br>";
    echo "Received password: " . $psw . "<br>";

    // Hash the password for security
    $hashed_password = password_hash($psw, PASSWORD_DEFAULT);   

    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "todolistdatabase";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO login (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $uname, $psw);
    $stmt->execute();

    // Check if the query was executed successfully
    if ($stmt->error) {
        die("Error: " . $stmt->error);
    }

   // Check if the user was successfully registered
    if ($stmt->affected_rows > 0) {
        // Registration successful, redirect to main page
        header("Location: ../mainpage.html");
        exit();
    } else {
        // Registration failed, redirect back to register page with error message
        header("Location: ../register.html?error=registration_failed");
        exit();
    }


    // Close the connection
    $conn->close();
} else {
    // If the form is not submitted, redirect to register page
    header("Location: register.html");
    exit();
}
?>
