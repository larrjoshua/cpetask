<?php
include 'db_connection.php';
if(isset($_GET['deleteid'])){
    $id=$_GET['deleteid'];
    $sql="Delete from `todolisttable` where id=$id";
    $result=mysqli_query($conn,$sql);
    if($result){
        // echo "Deleted successfully";
        echo "<script type='text/javascript'>setTimeout(function() { window.location.href = 'fetch_tasks.php'; }, 500);</script>";
    
    }else{
        die(mysqli_error($conn));
    }
}
?>
