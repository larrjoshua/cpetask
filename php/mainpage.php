<!DOCTYPE html>
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
            <button id="logoutButton" class="top_button"><img class="top_button" src="assets/logout.svg"/></button>
        </header>

        <!-- Fetch and display tasks from the database -->
        <div class="task_grid">
            <?php include 'fetch_tasks.php'; ?>
        </div>

        <div class="addbtn-container">
            <!-- Call redirectToAddTaskPage() function when button is clicked -->
            <button class="add_task_btn" onclick="redirectToAddTaskPage()">Add Task</button>
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
