<?php 
//include necessary function
include "db_connect2.php";
include "function2.php";
if(isset($_GET["change"])){
//user provide his username, old password and new password

$display = <<< block
<form method = "POST" action = "$_SERVER[PHP_SELF]" >
<p>Please fill the form below to change your password</p>
<label for = "username" >Username</label>
<input type = "text" name = "username" /><br />
<label for = "old_password" >Old password</label>
<input type = "password" name = "old_password" /><br />
<label for = "new_password1" >New Password</label>
<input type = "password" name = "new_password1" /><br />
<label for = "new_password2" >Confirm Password</label>
<input type = "password" name = "new_password2" /><br />
<fieldset>
<legend>Indicate whether you are a lecturer or student</legend>
<input type = "radio" name = "user_type" value = "lecturer" /><label for = "lecturer" >Lecturer</label>
<input type = "radio" name = "user_type" value = "student" /><label for = "student" >Student</label>
</fieldset><br/>
<input type = "submit" name = "reset_password" value = "Change Password" />
</form>
block;
}


if(isset($_POST["reset_password"]) || isset($_POST["create_new_password"])){
if(empty($_POST["user_type"])){
$display = "<p>Please Indicate whether you are a lecturer or student</p>";
}	else	{
$user_type = $_POST["user_type"];
$username = $_POST["username"];
$new_password1 = $_POST["new_password1"];
$new_password2 = $_POST["new_password2"];
if(isset($_POST["old_password"])){
$old_password = $_POST["old_password"];
}
if($user_type == "" || $username == "" || $new_password1 == "" || $new_password2 == "" || $new_password1 != $new_password2){

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
}


if(isset($_GET["forget_password"])){
//grab the security question the user answered during registration with the answer display the question and 
//compare with answer on response
$display = <<< block
<form method = "POST" action = "$_SERVER[PHP_SELF]" >
<p>Please fill the form below to change your password</p>
<label for = "username" >Username</label>
<input type = "text" name = "username" /><br />
<fieldset>
<legend>Indicate whether you are a lecturer or student</legend>
<input type = "radio" name = "user_type" value = "lecturer" /><label for = "lecturer" >Lecturer</label>
<input type = "radio" name = "user_type" value = "student" /><label for = "student" >Student</label>
</fieldset><br/>
<input type = "submit" name = "forget_password" value = "submit" />
</form>
block;
}

if(isset($_POST["forget_password"])){
if(empty($_POST["user_type"])){
$display = "<p>Please indicate whether you are a lecturer or student</p>";
}	else	{
$user_type = $_POST["user_type"];
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

$display = "<p>Please provide answer to this security question you answer during registration</p>
<form method = \"POST\" action = \"$_SERVER[PHP_SELF]\" >
<input type = \"hidden\" name = \"username\" value = \"$username\" />
<input type = \"hidden\" name = \"user_type\" value = \"$user_type\" />
<input type = \"hidden\" name = \"security_answer\" value = \"$security_answer\" />
<input type = \"text\" value = \"$security_question\" name = \"$security_question\" />
<label for = \"answer\" >Your Answer</label>
<input type = \"text\" name = \"answer\" />
<input type = \"submit\" value = \"Submit\"  name = \"check_answer\" />
</form>";
}
}
}


if(isset($_POST["check_answer"])){
if(empty($_POST["user_type"])){
$display = "<p>An error has occurred. Please ensure that the required fields are field properly</p>";
}	else	{
$user_type = $_POST["user_type"];
$security_answer = $_POST["security_answer"];
if(empty($_POST["answer"])){
$display = "<p>Please provide answer to the question to enable use help recover your password</p>";
}	else	{
$answer = $_POST["answer"];
$username = $_POST["username"];
if($security_answer == $answer){
//form to fill to with username and password
$display = "<p>Please enter your new password</p>
<form method = \"POST\" action = \"$_SERVER[PHP_SELF]\" >
<input type = \"hidden\" name = \"user_type\" value = \"$user_type\" />
<input type = \"hidden\" name = \"username\" value = \"$username\" />
<label for = \"new_password1\">New password</label>
<input type = \"password\" name = \"new_password1\" /><br />
<label for = \"new_password2\">New password</label>
<input type = \"password\" name = \"new_password2\" /><br />
<input type = \"submit\" name = \"create_new_password\" value = \"SUBMIT\" />
</form>";
}	else	{
$display = "<p>The answer you gave is not correct. please reflect back on the question to get the right answer</p>";
}

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