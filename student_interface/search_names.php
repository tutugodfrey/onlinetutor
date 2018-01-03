<?php
//include function to connect to db
include "db_connect2.php";
include "function2.php";
session_start();
if(isset($_SESSION["privileged_user"])){
$S_id = $_SESSION["S_id"];
$L_id = $_SESSION["L_id"];


if(isset($_GET["name"])){
$name = $_GET["name"];
$query_string = "select id, lastname, firstname from registered_lecturers 
		where firstname like \"$name%\" or lastname like \"$name%\" or username like \"$name%\"";

run_query($query_string);
if($row_num2 == 0){
$result = "<select><option>Not Found</option></select>";
} 	else 	{
$values = build_array($row_num);
select_option($values, "", "L_id");
$result = $select_result;
}
echo $result;
}



} 	else	{		//if no active session
echo  "<p>You do not have an active user session. Please go back and login in</p>";
$nav_buttons = "";
}

?>