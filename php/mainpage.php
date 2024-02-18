<?php
// mainpage.php

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

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
        <button class="top_button"><img class="top_button" src="assets/logout.svg"/></button>
    </header>

    <!-- Display username -->
    <p>Hello, <?php echo $_SESSION['username']; ?>!</p>

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
</body>
</html>
