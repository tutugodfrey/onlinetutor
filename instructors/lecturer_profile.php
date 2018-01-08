<?php
//this script will get infomation from the lecturer's database
include "db_connect2.php";
include "function2.php";
session_start();
if(isset($_SESSION["privileged_user"])){
$L_id = $_SESSION["L_id"];

//header('Content-Type: application/json');
if(isset($_GET["lecturer_profile"])){
//want to view the lecturer profile
$query_string = "select firstname, lastname, gender, date_of_birth, title, faculty, department, email, phone from registered_lecturers where id = \"$L_id\"";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>Your data  could not be fetch from the database. please ensure your are a registered user</p>";
}	else	{
$values = build_array($row_num2);
$L_firstname = $values["firstname"];
$L_lastname = $values["lastname"];
$L_gender  = $values["gender"];
$L_date_of_birth = $values["date_of_birth"];
$L_title = $values["title"];
$L_faculty = $values["faculty"];
$L_department = $values["department"];
$L_email = $values["email"];
$phone = $values["phone"];
$display = "<h1>Profile</h1><br /><dl><dt>Lastname</td><dd>$L_lastname</dd><dt>Firstname: </dt><dd>$L_firstname</dd>
		<dt>Gender: </dt><dd>$L_gender</dd><dt>Title: </dt><dd>$L_title</dd><dt>Faculty: <dt><dd>$L_faculty</dd>
		<dt>Department: </dt><dd>$L_department</dd><dt>Email: </dt><dd><a href = \"mailto:$L_email\">$L_email</a></dd><dt>Phone</dt><dd>$phone</dd></dl><br /><a href = \" \" ><p>View Courses</p></a>";
}
//}

$display .= <<<block
<form method = "POST" action = "$_SERVER[PHP_SELF]" >
<input type = "submit" id = "editProfile" name ="edit_profile" value ="Edit Your Profile" />
</form>
block;
}


if(isset($_POST["edit_profile"])) {
$heading = "<h1>Update Profile</h1>";
$values = [["firstname"], ["lastname"], ["sex"], ["birthday"], ["titile"], ["department"], ["faculty"], ["email"], ["photo"], ["username"]];

select_option($values, "fields to update", "update_field");
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

$query_string = "update registered_lecturers set $update_field = \"$new_value\" where id = \"$L_id\"";
run_query($query_string);
if ($row_num2 == 0 ) {
$display = "<p> The field could not be updated </p>";
}	else  	{
$display = "<p>The Update is successful</p>";
}

}


}	else {			
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}
?>

<?php 
//header('Content-Type: application/json');
echo $display

?>
