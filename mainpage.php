<?php
// Database credentials
$servername = "localhost"; // Change this to your database host
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "todolistdatabase"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from the todolisttable
$sql = "SELECT * FROM todolisttable";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    // Initialize an empty array to store fetched data
    $tasks = [];
    
    // Loop through the result set and store data in the tasks array
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TaskEase!</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="topbar">
        <button class="top_button"><img class="top_button" src="assets/search.svg"/></button>
        <h1 class="center-text">TaskEase</h1>
        <button id="logoutButton" class="top_button"><img class="top_button" src="assets/logout.svg"/></button>
    </header>

    <div class="addbtn-container">
        <!-- Call redirectToAddTaskPage() function when button is clicked -->
        <button class="add_task_btn" onclick="redirectToAddTaskPage()">Add Task</button>
    </div>

    <div class="task_grid">
        <?php foreach ($tasks as $task): ?>
            <div class="grid-item">
                <div class="item_top">
                    <p><?php echo $task['task_title']; ?></p>
                    <button class="edit_btn"><img class="edit_btn" src="assets/edit.svg"/></button>
                    <button class="edit_btn"><img class="edit_btn" src="assets/delete.svg"/></button>
                </div>
                <div class="item_bottom">
                    <p><?php echo $task['task_details']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Function to handle logout
        // Add event listener to the logout button
        document.getElementById("logoutButton").addEventListener("click", logout);
        
        function logout() {
            // Redirect to frontpage.html after logout
            window.location.href = "frontpage.html";
        }

        // Function to handle button click and redirect to addtask.html
        function redirectToAddTaskPage() {
            window.location.href = "addtask.html";
        }
    </script>
</body>
</html>
