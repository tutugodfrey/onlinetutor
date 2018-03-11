<?php
//include db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";
$doc_root = $_SERVER["DOCUMENT_ROOT"];
session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$owner_friend = "user".$owner_id."_friends";
if($owner_id === ""){
$display = "<p>You do not have an active user setion to view ths page</p>";

}		else 		{
if(isset($_GET["profile"])){
	$user_id = $_GET["user_id"];
	$query_string = "select picture, lastname, firstname, gender, birthday, title, faculty, department, email, phone from registered_users where id = \"$user_id\"";
	$fields = array ("picture", "lastname", "firstname", "gender", "date of birth", "title", "faculty", "department", "email", "phone");
	run_query($query_string);
	if($row_num2 == 0){
		$display = "<p>Your data  could not be fetch from the database. please ensure your are a registered user</p>";
	}	else	{
		$values = build_array($row_num2);
		$profile = "<dl>";
		for($i = 0; $i < sizeof($fields); $i++) {
			if($fields[$i] == "email"){
				$profile .= "<dt>". ucwords($fields[$i]) ."</dt><dd><a href= \"mailto:$values[$i]\">$values[$i]</a></dd>";
			}	elseif($fields[$i] === "picture"){ 
				$profile .= "<img src= \"$values[$i]\" alt = \"image\" />";
			}	else	{
				$profile .= "<dt>". ucwords($fields[$i]).": </dt><dd>$values[$i]</dd>";
			}
		}	//end for block
		$profile .= "</dl>"; 	//close definitive list
		if($user_id === $owner_id) {
			$display = <<<block
			$profile
			<a href = "$_SERVER[PHP_SELF]?edit_profile=yes&&user_id=$user_id" >Edit Profile</a>
block;
		}	else 	{
			$display = $profile;
		}
	}
}		// end profile

if(isset($_GET["edit_profile"])) {
	$user_id = $_GET["user_id"];
	$heading = "<h1>Update Profile</h1>";
	$values = [["firstname"], ["lastname"], ["gender"], ["birthday"], ["title"], ["department"], ["faculty"], ["email"], ["photo"], ["username"]];

	select_option($values, "fields to update", "update_field", "requiredFields form-control");
	$display = <<<block
	<form name = "profileUpdate" method = "POST" action = "$_SERVER[PHP_SELF]" >
	<input type ="hidden" name = "user_id" value = "$user_id" />
	$select_result <br />
	<label for = "new_value">Enter new Value</label>
	<input type = "text" id = "newValue" class = "form-control" name = "new_value" /><br />
	<input type = "submit" class = "btn btn-primary" id = "updateProfile" name = "update_profile" value = "Update" />
	</form>
	<pre class = "nojs_show">
	Please if you are going to update the a photo or birthday
	ignore filling the field new value here. just click on Update
	</pre>
block;
}	//end edit_profile


if(isset($_POST["update_profile"]) || isset($_POST["new_photo"]) || isset($_PsOST["date_of_birth"])) {
	$user_id = $_POST["user_id"];
	admin_connect();
	$update_field = mysqli_real_escape_string($mysqli, strtolower(trim($_POST["update_field"])));	//clean up the input
	if(isset($_POST["update_profile"])){
		$new_value = mysqli_real_escape_string($mysqli, trim($_POST["new_value"]));
	}
	if(isset($_POST["update_profile"]) && $update_field === "photo"){
		echo<<<block
		<form method = "POST" action = "$_SERVER[PHP_SELF]" enctype = "multipart/form-data" >
		<input type ="hidden" name = "user_id" value = "$user_id" />
		<input type ="hidden" name = "update_field" value = "photo" />
		<label for = "newPhoto" class = "sr-only">Choose a new photo from file</label>
		<input id = "newPhoto" class = "requiredFields form-control" type = "file" name = "photo" />
		<input type = "submit" class = "btn btn-primary"  id = "photo_update" name = "new_photo" value = "Change" />
		</form>
block;
		exit;
	}	elseif (isset($_POST["update_field"]) && $update_field === "birthday"){
	date_field("date of birth"); 
		echo<<<block
		<form method = "POST" action = "$_SERVER[PHP_SELF]">
		<input type = "hidden" name = "class" value = "$class" />
		<input type = "hidden" name = "user_id" value = "$user_id" />
		<input type = "hidden" name = "update_field" value = "date_of_birth" />
		$date_fields
		<input type = "submit" class = "btn btn-primary" id = "update_birthday" name = "date_of_birth" value = "Change" />
		</form>
block;
		exit;
	}	else	{
		if(isset($_POST["date_of_birth"])){
		$bday = $_POST["day"];
		$bmon = $_POST["mon"];
		$byear = $_POST["year"];
		$new_value = "$byear-$bmon-$bday";
		$update_field = "date_of_birth";
		$query_string = "update registered_users set $update_field = date(\"$new_value\") where id = \"$user_id\"";
		}	else	{		//end $_POST[date_of_birth]
			if(isset($_POST["new_photo"])){
				echo  "what is  hapening";
				$store = "$doc_root/personal_data/user$user_id/profile_pix";
				$new_value = "/personal_data/user$user_id/profile_pix/";

				if (is_uploaded_file($_FILES["photo"]['tmp_name'])){
						//move the file from the tempatory folder to a local directory
					move_uploaded_file($_FILES["photo"]['tmp_name'],      
					"$store/".$_FILES["photo"]['name']) or die("Couldn't move file"); //can also be $store."/".
					$new_value = $new_value.$_FILES["photo"]['name'];	//save the url to the database
					$update_field = "picture";
					//echo "file was moved!";
				}  	else	 {
					//the user does not upload a photo so use the default photo
					$new_value = "/personal_data/defaultpix.png";
					$update_field = "picture";
				}
			}		//end $_POST[new_photo]
			$query_string = "update registered_users set $update_field = \"$new_value\" where id = \"$user_id\"";
		}
	}
	run_query($query_string);
	if ($row_num2 == 0 ) {
		$display = "<p> The field could not be updated </p>";
	}	else  	{
		$display = "<p>The Update is successful</p>";
	}
}



if(isset($_POST["register_lecturer"]) || isset($_POST["register_friend"])){
	$user_id = $_POST["user_id"];
	$friends_table = "user".$user_id."_friends";
	if(isset($_POST["register_lecturer"])){
		$query_string = "select * from students where student_id = '$owner_id' and lec_id = $user_id";
	}
	if(isset($_POST["register_friend"])) {
		$query_string = "select * from $friends_table where friend_id = '$owner_id'";
	}
	run_query($query_string);
	if($row_num2 == 0){
		if(isset($_POST["register_lecturer"])){
		//say this lecturer in the student's table
		$query_string = "insert into students values(null, \"$user_id\", \"$owner_id\", \"no\")";
		}

		if(isset($_POST["register_friend"])) {
		$query_string = array ("insert into $owner_friend values(null, \"$user_id\", \"no\", \"$owner_id\", null)", "insert into $friends_table values (null, \"$owner_id\", \"no\", \"$owner_id\", null)");
		}
		run_query($query_string);
		if($row_num2 == 0 && $row_num3 == 0){
			$display = "<p>the registration could not be process</p>";
		}	else	{
			$display = "<p>your registration is successful</p>";
		}
	}	else {
		$display  = "<p>your are already registered with this lecturer</p>";
	}
}





if(isset($_GET["confirm"])){
	$user_id = trim($_GET["user_id"]);
	$confirm = trim($_GET["confirm"]);
	if(empty($_GET["user_type"])){
	}	else	{
		$user_type = $_GET["user_type"];
	}
	if($user_id == "" || $confirm == ""){
		$display = "<p>There is an error in the request. please go back and try again</p>";
	}	else	{
		$friends_table = "user".$user_id."_friends";
		if($confirm == "no"){
			$query_string = array ("delete from $owner_friend where friend_id = \"$user_id\"", "delete from $friends_table where friend_id = \"$owner_id\"");
		}
		if($confirm == "yes"){
			//insert yes in the two tables since it will be the basis for display of names
			$query_string = array ("update $owner_friend set confirm = \"yes\" where friend_id = \"$user_id\"",
				"update $friends_table set confirm = \"yes\" where friend_id = \"$owner_id\"");
		}
		run_query($query_string);
		if($row_num2 == 0 && $row_num3 == 0){
			$display = "<p>Your request could not be process now please try again late</p>";
		}	else	{
			$display = "<p>Your request is process successfully</p>";
		}
	}
}		//end confirm

}	//end verify $owner_id is present

}	else {			
header("Location:/login.php");  		//user do not have an active session
exit();
}
?>

<?php echo $display; ?>
