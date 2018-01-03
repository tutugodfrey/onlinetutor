<?php

session_start();
if(isset($_SESSION["privileged_user"])){
$S_username = $_SESSION["S_username"];
$L_username = $_SESSION["L_username"];




if(isset($_POST["get_username"])) { //for js to initiate localStorage
echo "username = $S_username";
}



} 	else	{
header("Location:/onlinetutor/login.php");  		//user do not have an active session
exit();
}

?>
