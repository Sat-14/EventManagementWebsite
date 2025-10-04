<?php
// Start session only if headers haven't been sent
if (!headers_sent()) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
include(__DIR__ . '/../config/dbconnect.php');



if(isset($_POST['submit'])){ 
$username = mysqli_real_escape_string($conn,$_POST['username']);



$password = mysqli_real_escape_string($conn,$_POST['password']);




  $sql_query = "select full_name from sign_up where username = '$username' and password='$password'";

        $result = mysqli_query($conn,$sql_query);



       $row = mysqli_num_rows($result);

        if($row > 0){
            $_SESSION['username'] = $username;



            header('Location: /dashboard.php');
            exit();

        }else{
            echo "Invalid username and password. Username: $username, Rows found: $row";
        }

    









      
}



		
   ?>