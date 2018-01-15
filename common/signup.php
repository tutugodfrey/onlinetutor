
<?php
//include function to call date_fields
include "./../includes/date_function.php";
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
<div class = "container">
	<div class = "row">
		<div class = "card col-10 col-sm-6 col-lg-4" >
			<div id = "signup">
			<h3 class = "form-heading card-title">sign up</h3>
			<form method = "POST" class = "form-group" enctype = "multipart/form-data" action = "signup_process.php" >
				<p id = "validation-notice">Fields mark below are required</p>
				<div class="form-check">
					<label for = "check-lecturer" class = "form-check-label">Instructors check here</label>
					<input type = "checkbox" id = "check-lecturer" class = "form-check-inline" name = "user_type" value = "lecturer" />
				</div>
				<label for = "firstname" class = "sr-only"> Firstname </label>
				<input type = "text" class = "form-control requiredFields" placeholder = "firstname" name = "firstname" size = "30" /><br />
				<label for = "lastname" class = "sr-only" >Lastname</label>
				<input type = "text" class = "form-control requiredFields" placeholder="lastname" name = "lastname" size = "30" /><br />
				<label for = "title" class = "sr-only">Title</label>
				<input type = "text" class = "form-control requiredFields" placeholder="title" name = "title" size = "15" /><br />
				<label for = "gender" class = "sr-only" >Gender</label>
				<select class = "form-control requiredFields" name = "gender">
				<option value = "default" selected = "selected">Gender</option>
				<option value = "male" >Male</option>
				<option value = "female" > Female</option>
				</select><br />
				<label for = "institution" class = "sr-only">Institution</label>
				<input type = "text" class = "form-control requiredFields" placeholder="institution" name = "institution" /><br />
				<label for = "faculty" class = "sr-only">Faculty</label>
				<input type = "text" class = "form-control" placeholder="Faculty" name = "faculty" /><br />
				<label for = "department" class = "sr-only">Department</label>
				<input type = "text" class = "form-control" placeholder="department" name = "department" /><br />
				<fieldset>
					<legend> For Students Only</legend>
					<label for = "discipline" class = "sr-only">Discipline</label>
					<input type = "text" class = "form-control" placeholder="discipline" name = "discipline" /><br />
					<label for = "matric_no" class = "sr-only">Student Id</label>
					<input type = "text" class = "form-control" placeholder="Student Id" name = "matric_no" /><br />
				</fieldset>
				<?php date_field("date of birth"); echo $date_fields; ?> <br />
				<label for = "photo" class = "sr-only" >Photo</label>
					<input type = "file" class = "form-control" placeholder="Photo" name = "photo" /><br />
				<label for = "email" class = "sr-only" >Email Address</label>
					<input type = "email" class = "form-control requiredFields" name = "email" placeholder = "Email: you@yourdomain.com" /><br />
				<label for = "phone_number" class = "sr-only" >Phone</label>
					<input type = "text" class = "form-control" placeholder="Phone number" name = "phone_number" /><br />
				<label for = "username" class = "sr-only" >Enter Prefer Username</label>
					<input type = "text" class = "form-control requiredFields" placeholder="username" name = "username" size = "30" /><br />
				<label for = "password" class = "sr-only" >Password</label>
					<input type = "password" class = "form-control requiredFields" placeholder = "password1" name = "password1" /><br />
				<label for = "confirmpassword" class = "sr-only" >Confirm Password</label>
					<input type = "password" class = "form-control requiredFields" placeholder = "password2" name = "password2" /><br />
				<!-- the select_result will output security questions -->
				<?php echo $select_result; ?><br />
				<label for = "answer" class = "sr-only">Your answer</label>
					<input type = "text" class = "form-control requiredFields" placeholder="Answer" name = "security_answer" /><br />
				<fieldset>

					<p>Agree to our <a href = "/onlinetutor/common/term_of_use.htm" >term of use</a> and <a href = "/onlinetutor/privacypolicy.htm" >privacy policy</a></p>
					<div class="form-check">
						<input type = "radio" class = "form-check-inline" name = "agreement" value = "Agree" />
						<label for = "agree" class = "form-check-label">Agree</label>
					</div>
					<div class="form-check">
						<input type = "radio" class = "form-check-inline" name = "agreement" value = "Disagree" />
						<label for = "disagree" class = "form-check-label">Disagree</label>
					</div>
				</fieldset><br/>
				<input type = "submit" class = "btn btn-success form-control" value = "Sign Up" name = "singup" />
			</form>
			<p> Already have an Account? <a href = "./login.php" >Log In</a></p>
			</div>
		</div>
	</div>
</div>
