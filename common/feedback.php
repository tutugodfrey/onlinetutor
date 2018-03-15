<?php
//include necessary files
include "./../includes/db_connect.php";
include "./../includes/functions.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
	$heading = "";
	if(isset($_GET["feedback"])){
		$display = <<<block
		<h1>FEEDBACK</h1>
		<p>Please tell us how you are enjoying the app and where we <br /> can make improvement to increase you experience using the app</p>
		<form target = "first_iframe" method = "POST" action = "$_SERVER[PHP_SELF]" >
		<input type = "hidden" name = "user_id" value = "$owner_id" />
		<label for = "feedback" >Your feedback</label><br />
		<textarea id = "feedback" name = "message" class = "requiredFields form-control" cols = "50" rows = "7" ></textarea><br />
		<input type = "submit"  class = "btn btn-primary" value = "Submit" name = "submit_feedback" />
		</form>
block;
	}

	if(isset($_POST["submit_feedback"])){
		$user_id = $_POST["user_id"];
		$message = trim($_POST["message"]);
		if($message === ""){
			$display = "<p>Please type a message to send as feedback.</p>";
		}	else	{
			admin_connect();
			$message = mysqli_real_escape_string($mysqli, $message);
			$query_string = "insert into feedback values(null, \"$user_id\", \"$message\", now())";
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