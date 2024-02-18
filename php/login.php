<?php
// Start session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $uname = $_POST['uname'];
    $psw = $_POST['psw'];

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

    // Prepare SQL statement to retrieve user data
    $stmt = $conn->prepare("SELECT * FROM login WHERE username = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if ($psw == $user['password']) {
            // Password is correct, set session and redirect to mainpage.html
            $_SESSION['username'] = $uname;
            header("Location: ../mainpage.html");
            exit();
        } else {
            // Invalid password, display error message and redirect
            $_SESSION['error'] = 'Invalid password! Please try again.';
            header("Location: ../frontpage.html");
            exit();
        }
    } else {
        // User does not exist, display error message and redirect
        $_SESSION['error'] = 'User does not exist! Please try again.';
        header("Location: ../frontpage.html");
        exit();
    }

    // Close the connection
    $conn->close();
} else {
    // If the form is not submitted, display error message and redirect
    $_SESSION['error'] = 'Invalid request! Please try again.';
    header("Location: ../frontpage.html");
    exit();
}
?>
