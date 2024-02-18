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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    
<div class="Container">
    <button class="btn btn-primary my-5 mx-5"><a href="../addtask.html" class="text-light">Add Task</a>



</button>

<table class="table" class="table table-striped">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Task Title</th>
      <th scope="col">Task Details</th>
      <th scope="col">username</th>
      <th scope="col">Operations</th>
    </tr>
  </thead>
  <tbody>


<?php
// Query to fetch data from the todolisttable
$sql = "SELECT * FROM todolisttable";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    // Loop through the result set and output data
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $task_title = $row['task_title'];
        $task_details = $row['task_details'];
        $username = $row['username'];
        echo '
            <tr>
                <th scope="row">' . $id . '</th>
                <td>' . $task_title . '</td>
                <td>' . $task_details . '</td>
                <td>' . $username . '</td>
                <td>
                <button class="btn btn-primary"><a href="update.php?updateid='.$id.'" class="text-light">Update</a></button>
                <button class="btn btn-danger"><a href="delete.php?deleteid='.$id.'" class="text-light">Delete</a></button>
                </td>



            </tr>';
    }
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);



?>





  </tbody>
</table>

</body>
</html>