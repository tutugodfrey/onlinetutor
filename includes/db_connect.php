<?php
//$open = "C:/xampp/php/pear/include/login.php";
//$fp = fopen($open, r);

//include "login.php"; 	//uncomment the include "login.php" to use this function
//hard coding the values for testing. i will change it later
function db_connect(){
global $mysqli;
//$mysqli = mysqli_connect("localhost", username, password, $cong_database);
$mysqli = mysqli_connect("localhost", username, password, "congregation_publisher");
if(mysqli_connect_errno()){
printf("Could Not Connect to Database: %s Please check your login data\n", mysqli_connect_error());
exit;
}
}


function admin_connect($run_as_admin = "yes", $user = "req", $passw = "req", $domain = "localhost"){
if($run_as_admin == "yes"){
$domain = "localhost";
$user = "root";
$passw = "53a80";
}

global $mysqli;
$mysqli = mysqli_connect($domain, $user, $passw);
//$mysqli = mysqli_connect("localhost", username, password, "congregation_publisher");
if(mysqli_connect_errno()){
printf("Could Not Connect to Database: %s Please check your login data\n", mysqli_connect_error());
exit;
}
}


?>