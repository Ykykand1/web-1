<?php 

include("lidhja.php");

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM log_in WHERE username ='$username' AND  password = '$password'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC );
    $count = mysqli_num_rows($result);

    if($count == 1){
        
         header("Location: succsesful.php");
         exit(); 
    } else {
         echo '<script> 
         window.location.href="index2.php";
         alert("Incorrect information");
         </script>';
    }
}
?>