<?php

function admin_connect($run_as_admin = "yes", $user = "req", $passw = "req", $domain = "localhost"){
	/*
	if($run_as_admin == "yes"){
		$domain = "localhost";
		$user = "root";
		$passw = "53a80";
	}
	*/
	
	if($run_as_admin == "yes"){
		$domain = "d6ybckq58s9ru745.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
		$user = "l2m6izbhqueg0mih";
		$passw = "rvsegg5ufaxgbtbs";
	}
	

	global $mysqli;
	$mysqli = mysqli_connect($domain, $user, $passw);
	if(mysqli_connect_errno()){
		printf("Could Not Connect to Database: %s Please check your login data\n", mysqli_connect_error());
		exit;
	}
}


?>