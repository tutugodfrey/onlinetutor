
<?php
//include function to call date_fields
include "date_function.php";
//include "function2.php";
/*
$values = array ();
$values = array (array ("What is the name of your first school", "How old are you when you got married",
				"What is your brand name of your first car", "What is the name of your favorite teacher"));
$rows = sizeof($values[0]);
select_option("security question", "security_question", $values, $rows);
$result = $select_result;
*/
$values = array ("What is the name of your first school?", "How old are you when you got married?",
				"What is your brand name of your first car?", "What is the name of your favorite teacher?");

$select_result = "<label for = \"security questions\"> Security Questions </label>
									<select class = \"form-control requiredFields\" name = \"security_question\">
									<option selected = \"selected\" value = \"default\">Select a question </option>";

for($i = 0; $i < sizeof($values); $i++){
$select_result .= "<option value =  \"".$values[$i]."\">".$values[$i]."</option>";
}
$select_result .= "</select>";

?>

<!-- interface for a lecturer to sign up to use the service -->
<div id = "signup">
<h3 class = "form-heading">sign up</h3>
<form method = "POST" class = "form-group" enctype = "multipart/form-data" action = "signup_process.php" >
	<p id = "validation-notice">Fields mark below are required</p>
	<label for = "check-lecturer">Please check here if you are an instructor</label>
	<input type = "checkbox" id = "check-lecturer" class = "form-control" name = "user_type" value = "lecturer" />
	<label for = "firstname"> Firstname </label>
	<input type = "text" class = "form-control requiredFields" name = "firstname" size = "30" /><br />
	<label for = "lastname" >Lastname</label>
	<input type = "text" class = "form-control requiredFields" name = "lastname" size = "30" /><br />
	<label for = "title">Title</label>
	<input type = "text" class = "form-control requiredFields" name = "title" size = "15" /><br />
	<label for = "gender" >Gender</label>
	<select class = "form-control requiredFields" name = "gender">
	<option value = "default" selected = "selected">Select Gender</option>
	<option value = "male" >Male</option>
	<option value = "female" > Female</option>
	</select><br />
	<label for = "institution">Institution</label>
	<input type = "text" class = "form-control requiredFields" name = "institution" /><br />
	<label for = "faculty">Faculty</label>
	<input type = "text" class = "form-control" name = "faculty" /><br />
	<label for = "department">Department</label>
	<input type = "text" class = "form-control" name = "department" /><br />
	<fieldset>
		<legend> For Students Only</legend>
		<label for = "discipline">Discipline</label>
		<input type = "text" class = "form-control" name = "discipline" /><br />
		<label for = "matric_no">Matriculation Number</label>
		<input type = "text" class = "form-control" name = "matric_no" /><br />
	</fieldset>
	<?php date_field("date of birth"); echo $date_fields; ?> <br />
	<label for = "photo">Photo</label>
		<input type = "file" class = "form-control" name = "photo" /><br />
	<label for = "email" >Email Address</label>
		<input type = "email" class = "form-control requiredFields" name = "email" placeholder = "you@yourdomain.com" /><br />
	<label for = "phone_number" >Phone</label>
		<input type = "text" class = "form-control" name = "phone_number" /><br />
	<label for = "username" >Enter Prefer Username</label>
		<input type = "text" class = "form-control requiredFields" name = "username" size = "30" /><br />
	<label for = "password" >Password</label>
		<input type = "password" class = "form-control requiredFields" placeholder = "password1" name = "password1" /><br />
	<label for = "confirmpassword" >Confirm Password</label>
		<input type = "password" class = "form-control requiredFields" placeholder = "password2" name = "password2" /><br />
	<!-- the select_result will output security questions -->
	<?php echo $select_result; ?><br />
	<label for = "answer">Your answer</label>
		<input type = "text" class = "form-control requiredFields" name = "security_answer" /><br />
	<fieldset>
		<p>Agree to our <a href = "/onlinetutor/common/term_of_use.htm" >term of use</a> and <a href = "/onlinetutor/privacypolicy.htm" >privacy policy</a></p><br />
		<input type = "radio" class = "form-control" name = "agreement" value = "Agree" />
			<label for = "agree">Agree</label><br />
		<input type = "radio" class = "form-control" name = "agreement" value = "Disagree" />
			<label for = "disagree">Disagree</label>
	</fieldset><br/>
	<input type = "submit" class = "btn btn-success form-control" value = "Sign Up" name = "singup" />
</form>
<p> Already have an Account? <a href = "./login.php" >Log In</a></p>
</div>
