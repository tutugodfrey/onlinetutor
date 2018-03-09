<?php

function admin_connect($run_as_admin = "yes"){
	// function admin_connect($run_as_admin = "yes", $db_user = "req", $passwd = "req", $domain = "localhost"){

	// echo json_encode($_ENV);
	$domain = getenv("domain");
	$db_user = getenv("db_user");
	$passwd = getenv("passwd");


	global $mysqli;
	$mysqli = mysqli_connect($domain, $db_user, $passwd);
	if(mysqli_connect_errno()){
		printf("Could Not Connect to Database: %s Please check your login data\n", mysqli_connect_error());
		exit;
	}
}


?>