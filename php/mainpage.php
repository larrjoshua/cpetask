<?php
// mainpage.php

// Start the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Database connection
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

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Attempt to query database table and retrieve data
try {
    $sql = "SELECT task_title FROM todolisttable"; // Adjust the table and column names as necessary
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        // Fetch the task titles
        $tasks = $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // If no records were found, we can set tasks to an empty array
        $tasks = [];
    }
} catch(PDOException $e) {
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close connection
unset($pdo);

// SQL to fetch tasks
$sql = "SELECT id, task_name, task_details FROM todolisttable";
$result = $conn->query($sql);

// Function to retrieve tasks for the logged-in user
function getTasks($username) {
    // Here you can implement the logic to retrieve tasks for the given username from your database
    // For demonstration purposes, let's assume you have a function called retrieveTasksFromDatabase
    $tasks = retrieveTasksFromDatabase($username);
    return $tasks;
}





?>

<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Welcome to TaskEase!</title>
</head>
<body>
    <header class="topbar">
        <button class="top_button"><img class="top_button" src="assets/search.svg"/></button>
        <h1 class="center-text">TaskEase</h1>
        <button class="top_button" onclick="logout()"><img class="top_button" src="assets/logout.svg"/></button>
    </header>

    <!-- Display username -->
    <p>Hello, <?php echo $_SESSION['username']; ?>!</p>

     <div class="task_grid">
        <?php foreach ($tasks as $task): ?>
            <div class="grid-item">
                <div class="item_top">
                    <p><?php echo htmlspecialchars($task['task_title']); ?></p>
                    <button class="edit_btn"><img class="edit_btn" src="assets/edit.svg"/></button>
                    <button class="edit_btn"><img class="edit_btn" src="assets/delete.svg"/></button>
                </div>
                <div class="item_bottom">
                    <p>No task details yet.</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="addbtn-container">
        <button class="add_task_btn">Add Task</button>
    </div>
    <div class="task_grid">
        <?php
        // Retrieve tasks for the logged-in user
        $tasks = getTasks($_SESSION['username']);

        // Display tasks
        foreach ($tasks as $task) {
            echo '<div class="grid-item">';
            echo '<div class="item_top">';
            echo '<p>' . $task['title'] . '</p>';
            echo '<button class="edit_btn"><img class="edit_btn" src="assets/edit.svg"/></button>';
            echo '<button class="edit_btn"><img class="edit_btn" src="assets/delete.svg"/></button>';
            echo '</div>';
            echo '<div class="item_bottom">';
            echo '<p>' . $task['description'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <script>
        // Function to handle logout
        function logout() {
            // Redirect to logout.php to end session
            window.location.href = "logout.php";
        }
    </script>
</body>
</html>
