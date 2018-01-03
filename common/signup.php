
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

$select_result = "<label for = \"security questions\"> Security Questions </label><select name = \"security_question\">";

for($i = 0; $i < sizeof($values); $i++){
$select_result .= "<option value =  \"".$values[$i]."\">".$values[$i]."</option>";
}
$select_result .= "</select>";

?>


<!DOCTYPE html>
<html>
<head>
<title>
</title>
<link type = "text/css" rel = "stylesheet" href = "./../mylecapp_css/style1.css" />
</head>
<body>
<div id = "body_div">
<!-- interface for a lecturer to sign up to use the service -->
<div id = "signup">
<h1>sign up</h1>
<form method = "POST" enctype = "multipart/form-data" action = "signup_process.php" >
<fieldset>
<legend>Sign Up As</legend>
<input type = "radio" name = "user_type" value = "lecturer" /><label for = "lecturer" >Lecturer</label>
<input type = "radio" name = "user_type" value = "student" /><label for = "student" >Student</label>
</fieldset>

<label for = "firstname"> Firstname </label>
<input type = "text"  name = "firstname" size = "30" /><br />
<label for = "lastname" >Lastname</label>
<input type = "text" name = "lastname" size = "30" /><br />
<label for = "title">Title</label>
<input type = "text" name = "title" size = "15" /><br />
<label for = "gender" >Gender</label>
<select name = "gender">
<option value = "Select Gender" selected = "selected">Select Gender</option>
<option value = "male" >Male</option>
<option value = "female" > Female</option>
</select><br />
<label for = "institution">Institution</label>
<input type = "text" name = "institution" /><br />
<label for = "faculty">Faculty</label>
<input type = "text" name = "faculty" /><br />
<label for = "department">Department</label>
<input type = "text" name = "department" /><br />
<fieldset>
<legend> For Students Only</legend>
<label for = "discipline">Discipline</label>
<input type = "text" name = "discipline" /><br />
<label for = "matric_no">Matriculation Number</label>
<input type = "text" name = "matric_no" /><br />
</fieldset>
<?php date_field("date of birth"); echo $date_fields; ?> <br />
<label for = "photo">Photo</label>
<input type = "file" name = "photo" /><br />
<label for = "email" >Email Address</label>
<input type = "email" name = "email" placeholder = "you@yourdomain.com" /><br />
<label for = "phone_number" >Phone</label>
<input type = "text" name = "phone_number" /><br />
<label for = "username" >Enter Prefer Username</label>
<input type = "text" name = "username" size = "30" /><br />
<label for = "password" >Password</label>
<input type = "password" value = "password1" name = "password1" /><br />
<label for = "confirmpassword" >Confirm Password</label>
<input type = "password" value = "password2" name = "password2" /><br />
<!-- the select_result will output security questions -->
<?php echo $select_result; ?><br />
<label for = "answer">Your answer</label>
<input type = "text" name = "security_answer" /><br />

<fieldset>
<p>Agree to our <a href = "/mylecturerapp/term_of_use.htm" >term of use</a> and <a href = "/mylecturerapp/privacypolicy.htm" >privacy policy</a></p><br />

<input type = "radio" name = "agreement" value = "Agree" />
<label for = "agree">Agree</label><br />
<input type = "radio" name = "agreement" value = "Disagree" />
<label for = "disagree">Disagree</label>
</fieldset><br/>
<input type = "submit" value = "Sign Up" name = "singup" />
</form>
</div>
<p> Already have an Account? <a href = "./login.php" >Log In</a></p>
</div>
</body>
</html>
