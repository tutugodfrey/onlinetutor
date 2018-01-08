<?php
//include db_connect function
include "db_connect2.php";
include "function2.php";

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

$query_string  = "select friend_id from $lecturer_friend where confirm = \"no\" and user_type = \"student\"";
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
$value[1] $value[2]<a href = \"/mylecturerapp/common/profile.php?profile=yes&user_id=$value[0]\">profile</a>
<a href = \"/mylecturerapp/common/profile.php?decline=no&user_id=$value[0]&user_type=student\">decline</a>
<a href = \"/mylecturerapp/common/profile.php?confirm=yes&user_id=$value[0]&user_type=student\">confirm</a>";
}		//end foreach
}

//getting already registered students
$query_string = "select friend_id from $lecturer_friend where confirm = \"yes\" and user_type = \"student\"";
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
<a href = "/mylecturerapp/common/profile.php?profile=student&user_id=$value[0]">Profile</a>
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


/*
if(isset($_GET["confirm"]) || isset($_GET["decline"]) ) {
if (empty($_GET["student_id"])) {
$display  = "<p>Please use the checkbox to select a student </p>";
} 	else 	{
$S_id = $_GET["student_id"];
$friends_table = "std".$S_id."_friends";
$lecturer_friend = "lec".$L_id."_friends";

if(isset($_GET["confirm"])) {
$query_string = array ("update $friends_table set confirm = \"yes\" where friend_id = \"$L_id\" and user_type = \"l\"", "update $lecturer_friend set confirm = \"yes\" where friend_id = \"$S_id\" and user_type = \"s\"" );
$success_string = "<p>The confirmation is successful</p>";
$failure_string = "<p>The request is not successful</p>";
}

if(isset($_GET["decline"])) {
$query_string = array ("delete from $friends_table where friend_id = \"$L_id\"", "use $lecturer_db", "delete from $lecturer_friend where friend_id = \"$S_id\"" );
$success_string = "<p>The decline is process successfully</p>";
$failure_string = "<p>The request is not successfull</p>";
}
run_query($query_string);
if ($row_num2 == 0) {
$display = $failure_string;
}	else	{
$display = $success_string;
}
}
} */
}

}		//end verify L_id
}	else {			
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}

?>



<?php echo $heading; ?>
<?php echo $display; ?>

