<?php
//include the db_connect function
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["privileged_user"])){
$S_id = $_SESSION["S_id"];
$L_id= $_SESSION["L_id"];


if(isset($_POST["student_profile"]) || isset($_GET["student_profile"])){

//admin_connect();
$query_string = "select * from registered_students where id = '$S_id'";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>Your information could not be found our record</p>";
$heading = "";
}	else	{
$values = build_array($row_num2);
$profile = "";
$S_lastname = ucwords($values['lastname']);
$S_firstname = ucwords($values['firstname']);
$date_of_birth = $values["date_of_birth"];
$gender = ucwords($values["gender"]);
$title = $values["title"];
$faculty = ucwords($values["faculty"]);
$department = ucwords($values["department"]);
$S_image_url = $values["picture"];
$email = $values["email"];
$phone =$values["phone"];
$username = $values["username"];
$profile .= "<dl><dt>Lastname: </dt><dd>$S_lastname</dd><dt>Firstname: </dt><dd> $S_firstname<dd><dt>Sex: </dt><dd>$gender
		</dd><dt>Born: </dt><dd>$date_of_birth</dd><dt>Title: <dt><dd>$title</dd><dt>Faculty: <dt><dd>$faculty
		</dd><dt>Department: </dt><dd>$department</dd><dt>Email: </dt><dd><a href= \"mailto:$email\">$email</a></dd>
		<dt>Phone: </dt><dd>$phone</dd><dt>Username: </dt><dd>$username</dd></dl>";

$heading = "<h1>Your Profile</h1>";
$display = <<<block
$profile;
<form name = "profileUpdate" method = "POST" action = "$_SERVER[PHP_SELF]">
<input type = "submit" id = "editProfile" name = "edit_profile" value = "Edit Your Profile" />
</form>
block;
}
}


if(isset($_POST["edit_profile"])) {
$heading = "<h1>Update Profile</h1>";
$values = [["firstname"], ["lastname"], ["sex"], ["birthday"], ["titile"], ["department"], ["faculty"], ["email"], ["photo"], ["username"]];
$select_result = select_option($values, "fields to update", "update_field");
$display = <<<block
<form name = "profileUpdate" method = "POST" action = "$_SERVER[PHP_SELF]" >
$select_result <br />
<label for = "new_value">Enter new Value</label>
<input type = "text" id = "newValue" name = "new_value" /><br />
<input type = "submit" id = "updateProfile" name = "update_profile" value = "Update" />
</form>
block;
}


if(isset($_POST["update_profile"])) {
admin_connect();
$update_field = mysqli_real_escape_string($mysqli, trim($_POST["update_field"]));	//clean up the input
$new_value = mysqli_real_escape_string($mysqli, trim($_POST["new_value"]));
echo $update_field.$new_value;
$query_string = "update registered_students set $update_field = \"$new_value\" where id = \"$S_id\"";
run_query($query_string);

if ($row_num2 == 0 ) {
$display = "<p> The field could not be updated </p>";
}	else  	{
$display = "<p>The Update is successful</p>";
}


}


}	else {
header("Location:/onlinetutor/login.php");  		//user do not have an active session
exit();
}

?>

<?php echo $heading; ?>
<img src = "<?php echo $S_image_url; ?>" alt = "image" /><br /><br /><br />
<?php echo $display; ?>
