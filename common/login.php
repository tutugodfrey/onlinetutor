<?php
//include functions to connect to database and run queries
include "db_connect2.php";
include "function2.php";

if(!isset($_POST["login"])){

if(isset($_POST["log_out"])){
session_start();
session_destroy();
//header("Location:/mylecturerapp/login.php");
}

$display = <<<end
  <p>Please Enter Your Username and Password</p>
  <div id = "login">
    <fieldset>
      <form method = "POST" action = "login.php" >
      <legend>Log In As</legend>
      <input type = "radio" name = "user_type" id = 'lecturer' value = "lecturer" /><label for = "lecturer" >Lecturer</label>
      <input type = "radio" name = "user_type" id = 'student' value = "student" /><label for = "student" >Student</label><br />
      <label for = "username">Username</label>
      <input type = "text" name = "username" id = 'username'  size = "30" /><br />
      <label for = "password" >Password</label>
      <input type = "password" id = 'password' value = "password"  name = "password" /><br />
      <input type = "submit" value = "Log-In" name = "login" /><br />
      </form>
    </fieldset>
    <div>
      <p> Don't have an Account? <a href = "signup.php" >Sign Up</a></p>
    </div>
    <div>
    <fieldset>
      <p>I<a href = "reset_password.php?forget_password=yes"> forgot my password?</a>   I want to <a href = "reset_password.php?change=yes">Change password</a></p>
    </fieldset>
    </div>
  </div>
end;
}	elseif(isset($_POST["login"])) {
$username = trim($_POST["username"]);
$password = $_POST["password"];
if(empty($_POST["user_type"])){
$display = "<p>Please indicate whether you are lecturer or student to log in</p>";
}	else	{
$user_type = $_POST["user_type"];
if($username == "" || $user_type == "" || $password == "password" || $password == ""){
$display = "<p>Please Enter your username and password to login<a href = \"login.php\">&lt;&lt; Back</a></p>";
}	else	{

//process log in for students

//authenticate the use
$query_string = "select id, lastname, firstname, picture from registered_users where username = \"$username\" and password = sha1($password)";

run_query($query_string);		//use registration database. if access is denied the user authentication fails
if($row_num2 == 0){
$display = "<p>Your username or password is not con correct. please ensure you are duly registered to use this app</P";
}					//login error message will be displayed
if($row_num2 == 1){

$user_info = build_array($row_num2);
$user_id = $user_info[0];

session_start();
$_SESSION["user_names"] = $user_info[1]." ".$user_info[2];
$_SESSION["user_image_url"] = $user_info[3];

if($user_type == "student"){
$_SESSION["class"] = "student";
$_SESSION["owner_id"] = $user_id;
header("Location:/onlinetutor/student_interface/dashboard.php");
}

if($user_type == "lecturer"){
$_SESSION["class"] = "lecturer";
$_SESSION["lecturer_db"] = "lec".$user_id;
$_SESSION["owner_id"] = $user_id;
header("Location:/onlinetutor/lecturer_interface/dashboard.php");
}
exit();

}

}
}
}
?>

<?php echo $display; ?>
