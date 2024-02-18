<?php
// Start session (if not already started)
session_start();


        // Your database connection code goes here
        // Example:
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "todolistdatabase";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        // Retrieve task details from the form
        $taskTitle = $_POST['task_title'];
        $taskDetails = $_POST['task_details'];
        $username = $_POST['uname1'];

        // Insert task into database
        // Example:
        $sql = "INSERT INTO todolisttable (username, task_title, task_details) VALUES ('$username', '$taskTitle', '$taskDetails')";
        if ($conn->query($sql) === TRUE) {
            echo "Task added successfully";
            header("Location: ../login.html");

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close connection
        $conn->close();
    } else {
        // Redirect user to login page if not logged in
        header("Location: login.html");
        exit();
    }
} else {
    // Redirect user to addtask.html if accessed directly without form submission
    header("Location: addtask.html");
    exit();
}
?>
