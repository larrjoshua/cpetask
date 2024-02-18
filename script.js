// JavaScript code for dynamic behavior
document.addEventListener("DOMContentLoaded", function() {
    // Fetch and display username
    fetchUsername();

    // Add event listener to logout button
    document.getElementById("logoutButton").addEventListener("click", function() {
        // Redirect to logout.php upon logout
        window.location.href = "logout.php";
    });

    // Fetch and display tasks
    fetchTasks();
});

function fetchUsername() {
    // Make an AJAX request to fetch the username
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_username.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Update the HTML element with the fetched username
                document.getElementById("usernameGreeting").innerText = "Hello, " + xhr.responseText + "!";
            } else {
                console.error("Failed to fetch username.");
            }
        }
    };
    xhr.send();
}

function fetchTasks() {
    // Make an AJAX request to fetch tasks
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_tasks.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the JSON response
                var tasks = JSON.parse(xhr.responseText);

                // Clear the task grid
                var taskGrid = document.querySelector(".task_grid");
                taskGrid.innerHTML = "";

                // Iterate over tasks and populate the task grid
                tasks.forEach(function(task) {
                    var gridItem = document.createElement("div");
                    gridItem.className = "grid-item";
                    gridItem.innerHTML = `
                        <div class="item_top">
                            <p>${task.title}</p>
                            <button class="edit_btn"><img class="edit_btn" src="assets/edit.svg"/></button>
                            <button class="edit_btn"><img class="edit_btn" src="assets/delete.svg"/></button>
                        </div>
                        <div class="item_bottom">
                            <p>${task.description}</p>
                        </div>
                    `;
                    taskGrid.appendChild(gridItem);
                });
            } else {
                console.error("Failed to fetch tasks.");
            }
        }
    };
    xhr.send();
}
