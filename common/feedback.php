<?php
//include necessary files
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["privileged_user"])){
if(empty($_SESSION["S_id"])){
$S_id = "";
}	else	{
$S_id = $_SESSION["S_id"];
}

$L_id = $_SESSION["L_id"];


if(isset($_GET["feedback"])){
if(isset($_SESSION["L_id"]) && !isset($_SESSION["S_id"])){
$sender_type = "lecturer";
$sender_id = $L_id;
}
if(isset($_SESSION["S_id"]) && isset($_SESSION["L_id"])){
$sender_type = "student";
$sender_id = $S_id;
}

$display = <<<block
<h1>FEEDBACK</h1>
<p>Please tell us how you are enjoying the app and where we <br /> can make improvement to increase you experience using the app</p>
<form target = "first_iframe" method = "POST" action = "$_SERVER[PHP_SELF]" >
<input type = "hidden" name = "sender_id" value = "$sender_id" />
<input type = "hidden" name = "sender_type" value = "$sender_type" />
<label for = "feedback" >Your feedback</label><br />
<textarea id = "feedback" name = "message" cols = "50" rows = "7" ></textarea><br />
<input type = "submit" value = "Submit" name = "submit_feedback" />
</form>
block;
}


if(isset($_POST["submit_feedback"])){
$sender_type = $_POST["sender_type"];
$sender_id = $_POST["sender_id"];
$message = trim($_POST["message"]);
if($message === ""){
$display = "<p>Please type a message to send as feedback.</p>";
}	else	{
admin_connect();
$message = mysqli_real_escape_string($mysqli, $message);
$query_string = "insert into feedback values(null, \"$sender_id\", \"$sender_type\", \"$message\", now())";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>Your feedback could not be sent. Please try again later.</p>";
}	else	{
$display = "<p><em>Thank you!</em> We have received your feedback.</p>";
}
}
}		//end submit feedback

}	else {			//user do not have an active session
echo "<p>You do not have an active user session. please go back and log in properly</p>";
}

?>


<!DOCTYPE html >
<html>
<head>
</head>
<body>
<?php echo $display; ?>
</body>
</html>