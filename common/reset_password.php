<?php 
//include necessary function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
if(isset($_GET["change"])){
//user provide his username, old password and new password

$display = <<< block
<div class = "container">
	<div class = "row">
		<div class = "card col-sm-10 col-md-4 col-lg-4">
			<form method = "POST" action = "$_SERVER[PHP_SELF]" >
				<h3 class = "card-title">Change your password</h3>
				<label for = "username" class = "sr-only" >Username</label>
				<input type = "text" class = "requiredFields form-control" placeholder = "username" name = "username" /><br />
				<label for = "old_password" class = "sr-only" >Old password</label>
				<input type = "password" class = "requiredFields form-control" placeholder = "Old password" name = "old_password" /><br />
				<label for = "new_password1" class = "sr-only" >New Password</label>
				<input type = "password" class = "requiredFields form-control" placeholder = "New password" name = "new_password1" /><br />
				<label for = "new_password2" class = "sr-only" >Confirm Password</label>
				<input type = "password" class = "requiredFields form-control" placeholder = "Confirm Password" name = "new_password2" /><br />
				<input type = "submit" class = "btn btn-success form-control" name = "reset_password" value = "Change Password" />
			</form>
		</div>
	</div>
</div>

block;
}


if(isset($_POST["reset_password"]) || isset($_POST["create_new_password"])){
	$username = $_POST["username"];
	$new_password1 = $_POST["new_password1"];
	$new_password2 = $_POST["new_password2"];
	if(isset($_POST["old_password"])){
		$old_password = $_POST["old_password"];
	}
	if( $username == "" || $new_password1 == "" || $new_password2 == "" || $new_password1 != $new_password2){
		$display = "<p>Please fill the fields for to process your request</p>";
	}	else	{
	//select the id using the username first
		$query_string1 = "select id from registered_users where username = \"$username\"";
		//use the user's previous permission to perform the action success means that the password and username is correct
		run_query($query_string1);
		if($rows == 0){
			$display = "<p>The password or username you entered in incorrect</p>";
		}	else	{
			if($row_num2 == 0 ) {
				$display  = "<p>Your record could not be confirm</p>";
			}	else {
				$user_id = build_array($row_num2);
				$query_string2 = "update registered_users set password = sha1(\"$new_password1\") where id = \"$user_id\"";
				run_query($query_string2);
				if($row_num2 == 1){
					$display = "<p>Your password have be change successfully</p>";
				}	else	{
					$display = "<p>Your new password could not be reset<p>";
				}
			}
		}
	}
}


if(isset($_GET["forget_password"])){
//grab the security question the user answered during registration with the answer display the question and 
//compare with answer on response
$display = <<< block
<div class = "container">
	<div class = "row">
		<div class = "card col-sm-10 col-md-4 col-lg-4">
			<form method = "POST" action = "$_SERVER[PHP_SELF]" >
				<p class = "card-title">Change your password</p>
				<label for = "username" class = "sr-only" >Username</label>
				<input type = "text" class = "form-control" placeholder = "username" name = "username" /><br />
				<input type = "submit" class = "btn btn-success form-control" name = "forget_password" value = "submit" />
			</form>
		</div>
	</div>
</div>

block;
}

if(isset($_POST["forget_password"])){
	$username = trim($_POST["username"]);
	$query_string = "select security_question, security_answer from registered_users where username = \"$username\"";
	admin_connect();	//connect as administrator
	run_query($query_string);
	if($row_num2 == 0){
		$display = "<p>The username you entered is not correct<p>";
	}	else	{
		$values  = build_array($row_num2);
		$security_question = $values["security_question"];
		$security_answer  = $values["security_answer"];
		$display = <<<block
		<div class = "container">
			<div class = "row">
				<div class = "card col-sm-10 col-md-4 col-lg-4">
					<h3 class = "card-title">Forget password</h3>
					<form method = "POST" action = "$_SERVER[PHP_SELF]" >
						<p class =- "form-text">Please provide answer to this security question you answered during registration</p>
						<input type = "hidden" name = "username" value = "$username" />
						<input type = "hidden" name = "security_answer" value = "$security_answer" />
						<input type = "text" class "form-control" value = "$security_question" name = "$security_question" />
						<label for = "answer" class "sr-only" >Your Answer</label>
						<input type = "text" class = "form-control" name = "answer" />
						<input type = "submit" class = "btn btn-success form-control" value = "Submit"  name = "check_answer" />
					</form>"
				</div>
			</div>
		</div>
block;
	}
}


if(isset($_POST["check_answer"])){
	$security_answer = $_POST["security_answer"];
	if(empty($_POST["answer"])){
		$display = "<p>Please provide answer to the question to enable use help recover your password</p>";
	}	else	{
		$answer = $_POST["answer"];
		$username = $_POST["username"];
		if($security_answer == $answer){
			//form to fill to with username and password
			$display = "
			<div class = \"container\">
				<div class = \"row\">
					<div class = \"card col-sm-10 col-md-4 col-lg-4\">
						<h3 class = \"card-title\">Continue</h3>
						<form method = \"POST\" action = \"$_SERVER[PHP_SELF]\" >
							<p class = \"form-text\">Please enter your new password</p>
							<input type = \"hidden\" name = \"username\" value = \"$username\" />
							<label for = \"new_password1\" class = \"sr-only\">New password</label>
							<input type = \"password\" class = \"form-control\" name = \"new_password1\" /><br />
							<label for = \"new_password2\" class = \"sr-only\">New password</label>
							<input type = \"password\" class = \"form-control\" name = \"new_password2\" /><br />
							<input type = \"submit\" class = \"btn btn-success form-control\" name = \"create_new_password\" value = \"SUBMIT\" />
						</form>
					</div>
				</div>
			</div>";
		}	else	{
			$display = "<p>The answer you gave is not correct. please reflect back on the question to get the right answer</p>";
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php echo $display; ?>
</body>
</html>