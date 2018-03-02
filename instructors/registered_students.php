<?php
//include db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = "lec$owner_id";
$lecturer_friend = "user".$owner_id."_friends";

$heading = ""; $display = "";

if($owner_id == ""){
$display = "<p>Your username is not set please go back and try again</p>";
}	else	{

	if(isset($_GET["registered_students"])){
		//select unconfirmed student
		$query_string  = "select student_id from students where confirm = \"no\" and lec_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0) {
			//be select no action needed
		}	else {
			$values = build_array($row_num2);
			$type = gettype($values);
			if($type === "string") {
				$values = [$values];
			}
			$array_container = [];
			foreach($values as $value ){
				$query_string = "select id, firstname, lastname, picture from registered_users where id = \"$value\"";
				run_query($query_string);
				if($row_num2 == 0){
					//no action 
				}	else 	{ 
					$value = build_array($row_num2);
					$array_container[] = $value;		//push each lecturer info into  the array
				}
			}
			$values = $array_container;
			foreach($values as $value){
				$display .= "
				<img src = \"$value[3]\" alt = \"image\" />
				$value[1] $value[2]<a href = \"/onlinetutor/common/profile.php?profile=yes&user_id=$value[0]\">profile</a>
				<a href = \"$_SERVER[PHP_SELF]?decline=no&user_id=$value[0]&user_type=student\">decline</a>
				<a href = \"$_SERVER[PHP_SELF]?confirm=yes&user_id=$value[0]&user_type=student\">confirm</a>";
			}		//end foreach
		}
		//getting already registered students
		$query_string = "select student_id from students where confirm = \"yes\" and lec_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0 ) {
			$display .= "<p>No student have registered with you yet</p>";
		}	else 	{
			$values = build_array($row_num2);
			//$type = gettype($values);
			//if($type === "string") {
			if($row_num2 == 1) {
				$values = [$values];	//make it an array
			}

			$array_container = [];
			foreach($values as $value ){
				$query_string = "select id, firstname, lastname from registered_users where id = \"$value\"";
				run_query($query_string);
				if($row_num2 == 0){
					$value = "no student";
					//$select_result = "<select><option>No lecturer</option></select>";
				}	else 	{ 
					$value = build_array($row_num2);
					$array_container[] = $value;		//push each lecturer info into  the array
				}
			}	//end foreach

			$values = $array_container;
			$fields = array ("student_id", "Firstname", "Lastname");
			if(sizeof($values) == 0) {
				$display .= "<p>No student have registered with you</p>";
			}	else 	{
				foreach($values as $value) {
					$display .= <<<block
					<p>$value[2] $value[1]
					<a href = "/onlinetutor/common/profile.php?profile=student&user_id=$value[0]">Profile</a>
					<a href = "$_SERVER[PHP_SELF]?registered_courses=yes&student_id=$value[0]" >courses</a>
					</p>
block;
				}	//end foreach
				$heading = "<h1>Registered Students</h1>";
			}
		}	//end confirmed std
	}



	if(isset($_GET["registered_courses"])){
		$student_id = trim($_GET["student_id"][0]);
		if($student_id == ""){
			$display = "<p>Please select a student to view their registered courses</p>";
		}	else	{
			$course_ids = registered_course_ids($student_id, $lecturer_db);
			$course_details  = foreach_iterator2("get_course_code", $course_ids, 3, $lecturer_db );
			if(sizeof($course_details) === 0){
				$display = "<p>This student have not registered any course yet</p>";
			}	else	{
				$fields = ["course_id", "course code", "course title", "unit"];
				array_unshift($course_details, $fields);
				$heading  = "<h1>Registered courses and course status</h1>";
				$select_result = mytable($course_details, "yes", "no"); 	// student username is the select options
				$display = $select_result;
			}
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
				$query_string = "delete from students where student_id = \"$user_id\" and lec_id = \"$owner_id\"";
			}
			if($confirm == "yes"){
				//insert yes in the two tables since it will be the basis for display of names
				$query_string = "update students set confirm = \"yes\" where student_id = \"$user_id\" and lec_id = \"$owner_id\"";
			}
			run_query($query_string);
			if($row_num2 == 0 && $row_num3 == 0){
				$display = "<p>Your request could not be process now please try again late</p>";
			}	else	{
				$display = "<p>Your request is process successfully</p>";
			}
		}
	}		//end confirm


}		//end verify L_id
}	else {			
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}

?>



<?php echo $heading; ?>
<?php echo $display; ?>

