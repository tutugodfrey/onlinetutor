<?php
//this script will display the main view for the student from here the studen can see all lecturer she is registered with
//and can search for and register with new new lecturer. also from here she can will will connect to a lecturer 
// and manage her record with this lecturer
//include the db_connect function
include "db_connect2.php";
include "function2.php";
session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$user_image_url = $_SESSION["user_image_url"];

$display = "";

if(!$_GET){
$friends_table = "user".$owner_id."_friends";

$query_string = "select friend_id from $friends_table where confirm = \"yes\"";
run_query($query_string);
if($row_num2 == 0){
$select_result = "<select><option>No lecturer</option></select>";
}	else 	{
//get the array of the id's returned in $values
$values  = build_array($row_num2);

if($row_num2 == 1){
$values = [$values];
}
$array_container = [];
foreach($values as $value){
$query_string = "select id, firstname, lastname from registered_users where id = \"$value\"";

run_query($query_string);
if($row_num2 == 0){
$select_result = "<select><option>No lecturer</option></select>";
}	else 	{ 
$value = build_array($row_num2);
$array_container[] = $value;		//push each lecturer info into  the array
}
}	//end foreach

$values = $array_container;

$size_of_values = sizeof($values);
if($size_of_values == 0) {
$select_result = "<select><option>No lecturer</option></select>";
}	else {
select_option($values, "lecturers", "user_id");
}

}
//an html to display some command
$nav_buttons = <<<end
<ul>
<li class = "link_buttons">
<form method ="GET" action = "/mylecturerapp/common/profile.php" >
<input type = "hidden" value = "$user_image_url" name = "user_image_url" />
<input type = "hidden" value = "$owner_id" name = "user_id" />
<input type = "submit" class = "submit_buttons" name = "profile" value = "Your Profile" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "$_SERVER[PHP_SELF]" >
<input type = "hidden" value = "$user_image_url" name = "S_image_url" />
<ul>
<li class = "link_buttons">
$select_result
</li>
<li class = "link_buttons">
<input type = "submit" id = "choose_lec" class = "submit_buttons" value = "Select Lecturer" name = "select" />
</li>
<li class = "link_buttons">
<input type = "submit" id = "del_lec" class = "submit_buttons" value = "Delete" name = "delete" />
</li>
</ul>
</form>
</li>
<li class = "link_buttons">
<form id = "reg_lec_form" method = "GET" action = "/mylecturerapp/common/search_names.php" >
<ul>
<li class = "link_buttons">
<label for = "search_lecturer">Search for Lecturer</label>
</li>
<li class = "link_buttons" >
<input type = "text" name = "name_like_nojs" />	<!-- not using javascript -->
</li>
<li class = "link_buttons">
<input type = "submit" id = "reg_lec" class = "submit_buttons" name = "search_lecturers" value = "Search" />
</li>
</ul>
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" id = "feedback" method = "GET" action = "/mylecturerapp/common/feedback.php" >
<input type = "submit" class = "submit_buttons" name = "feedback" value = "Feedback" />
</form>
</li>

<li class = "link_buttons">
<form id = "log_out" method = "GET" action = "/mylecturerapp/common/login.php" >
<input type = "submit" id = "logout" class = "submit_buttons" value = "log out" name = "log_out" />
</form>
</li>
<li class = "link_buttons">
<form id = "helpForm" method = "GET" action = "/mylecturerapp/common/help.php" >
<input type = "submit" id = "help" class = "submit_buttons" name = "get_help" value = "help" />
</form>
</li>
</ul>
end;
}


if(isset($_GET["select"])){
$S_image_url = $_GET["S_image_url"];

if(!isset($_GET["user_id"])){
$L_id = "";
}	else	{
$user_id = $_GET["user_id"];
$_SESSION["user_id"] = $user_id;		//set L_id to the global session variable
$_SESSION["lecturer_db"] = "lec".$user_id;
}

$nav_buttons = <<<end
<ul>
<li class = "link_buttons">
<form method = "GET"  target = "first_iframe" action = "/mylecturerapp/common/profile.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "hidden" name = "user_id" value = "$user_id" />
<input type = "submit" class = "submit_buttons" name = "profile" value = "Your Lecturer's Profile" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method ="GET" action ="coursemates.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons"name = "coursemates" value = "View coursemates" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "courses.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "registered_courses" value = "Registered Courses" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "courses.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "courses" value ="view courses"/>
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "test.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "tests" value = "Tests/Exams" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "scores.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "scores" value = "scores" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "Sdiscussion.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "Sdiscussion" value = "Discussion" />
</form>
</li>
<li class = "link_buttons">
<form target = "second_iframe" method = "GET" action = "announcement.php" >
<input type = "hidden" value = "$S_username" name = "S_username" />
<input type = "submit" class = "submit_buttons" name = "view_announcement" value = "Announcement" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/mylecturerapp/common/friends.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "friends" value = "Friends" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "lecture_note.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "lecture_note" value = "Lecture Note" />
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "Svideos.php" >
<input type = "submit" class = "submit_buttons" name = "view_videos" value = "Videos" />
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "mynote.php" >
<input type = "hidden" value = "$S_image_url" name = "S_image_url" />
<input type = "submit" class = "submit_buttons" name = "mynote" value = "Take Note" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/mylecturerapp/common/feedback.php" >
<input type = "submit" class = "submit_buttons" name = "feedback" value = "Feedback" />
</form>
</li>
<li class = "link_buttons">
<form id = "log_out" method = "GET" action = "/mylecturerapp/common/login.php" >
<input type = "submit" class = "submit_buttons" value = "log out" name = "log_out" />
</form>
</li>
<li class = "link_buttons">
<form id = "helpForm" method = "GET" action = "/mylecturerapp/common/help.php" >
<input type = "submit" id = "help" class = "submit_buttons" name = "get_help" value = "help" />
</form>
</li>
</ul>
end;
}


if(isset($_GET["delete"]))	{
//the student choose to delete the lecturer
if($user_id == ""){
$heading = ""; $L_image_url = "";	$nav_buttons = "";
$display = "<p>You can't perform this action because you are not registered with any lecturer</p>";
}	else	{
$lecturers_table = "user".$user_id."_friends";
//admin_connect();
//access the lecturer from the S_id table. you are still using the registration database
$query_string = ["delete from $lecturers_table where friend_id = \"$owner_id\"", "delete from $friends_table where friend_id = \"$user_id\"" ];
run_query();
if($rows == 1){
$display = "<p>The lecturer have be remove from your record</p>";
} elseif($rows == 0){
$display = "<p>That name does not exit. No action was taken</p>";
}
}
}

} 	else	{		//if no active session
echo  "<p>You do not have an active user session. Please go back and login in</p>";
$nav_buttons = "";
}


?>


<!DOCTYPE html>
<html>

<head>
<title><?php echo $title_display ?></title>
<link type = "text/css" rel = "stylesheet" href = "/mylecturerapp/mylecapp_css/style2.css" />
<script type = "text/javascript">window.location = "/mylecturerapp/student_interface/index.php"</script>
</head>
<body>
<div id = "app_logo_area">
<p id = "app_logo">MyLecApp</p> <p id = "app_desc">Lecturer-Stuedent Interaction cannot be more better. interact more effectively with your lecturers</p></div>
<div id = "nav_buttons" > <img src = "<?php echo $user_image_url; ?>" alt = "image" />
<?php echo $nav_buttons; ?> </div>
<div > <?php echo $display;  ?> </div>
<div id = "content_area"> <iframe id = "S_iframe" name = "first_iframe"> </iframe> </div>
<div id = "side_content"> <iframe id = "side_iframe" name = "second_iframe"> </iframe> </div>
</body>
</html>