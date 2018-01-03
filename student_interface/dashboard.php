<?php
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

$query_string = "select friend_id from $friends_table where confirm = \"yes\" and user_type = \"lecturer\"";		//getting ids of lecturers
run_query($query_string);
if($row_num2 == 0){
$select_result = "<select><option>No lecturer</option></select>";
}	else 	{
//get the array of the id's returned in $values
$values  = build_array($row_num2);

if(gettype($values) == "string"){
$values = [$values];
}
$array_container = [];
foreach($values as $value){
$query_string = "select id, lastname, firstname from registered_users where id = \"$value\"";

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

array_unshift($values, ["select", "Select", "lecturer"]);

select_option($values, "", "user_id");
}

}

//an html to display some command
$nav_buttons = <<<end
<div id = "prynav">
<ul>
<li class = "link_buttons">
<form   method = "GET" action = "/onlinetutor/common/profile.php" >
<input type = "hidden" value = "student" name = "profile" />
<input type = "hidden" value = "$owner_id" name = "user_id" />
<input type = "submit" id = "S_my_profile" class = "submit_buttons" name = "student_profile" value = "Your Profile" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "$_SERVER[PHP_SELF]" >
<input type = "hidden" value = "$user_image_url" name = "user_image_url" />
<div id = "lecturers"> $select_result </div>
<input type = "submit" id = "choose_lec" class = "submit_button" value = "Select Lecturer" name = "select" />

<input type = "submit" id = "del_lec" class = "submit_buttons" value = "Delete" name = "delete" />

</form>
</li>


<li class = "link_buttons">
<form id = "search_lec_form" method = "GET" action = "/onlinetutor/common/search_names.php" >
<ul>
<li class = "link_buttons">
<input type = "text" class = "link_buttons" id = "enter_lecturer_name" name = "name_like_js" placeholder = "search for lecturer"/>
</li>
<li class = "link_buttons">
<input type = "submit" id = "search_lec" class = "submit_buttons nojsi_show" name = "search_lecturers" value = "Search" />
</li>
</ul>
</form>
</li>


<li class = "link_buttons">
<form name = "reg_lecturer" id = "reg_lec_form" method = "GET" action = "/onlinetutor/common/profile.php" >
<ul>
<li class = "link_butto">
<div id = "lecturers_name" class = "link_buttons"></div>
</li>
<li class = "link_buttons">
<input type = "submit"  id = "registerLecturer" class = "submit_buttons nojsi_show" name = "register_lecturer" value = "Register" />
</li>
</ul>
</form>
</li>

</ul>
</div>

<div id = "secnavdiv" >
<ul id = "secnav" >
<li>
<hr/> <hr/> <hr/>
<ul>
<li class = "link_buttonss">
<form target = "first_iframe" id = "sfeedback" method = "GET" action = "/onlinetutor/common/feedback.php" >
<input type = "submit" class = "submit_buttonss" name = "feedback" value = "Feedback" />
</form>
</li>

<li class = "link_buttonss">
<form id = "slog_out" method = "GET" action = "/onlinetutorp/common/login.php" >
<input type = "submit" id = "slogout" class = "submit_buttonss" value = "log out" name = "log_out" />
</form>
</li>

<li class = "link_buttonss">
<form id = "shelpForm" method = "GET" action = "/onlinetutor/common/help.php" >
<input type = "submit" id = "shelp" class = "submit_buttonss" name = "get_help" value = "help" />
</form>
</li>
</ul>
</li>
</ul>
</div>
end;
}




if(isset($_GET["select"])){
$user_image_url = $_GET["user_image_url"];

if(!isset($_GET["user_id"])){
$L_id = "";
}	else	{
$L_id = $_GET["user_id"];
$_SESSION["L_id"] = $L_id;		//set L_id to the global session variable
$_SESSION["lecturer_db"] = "lec".$L_id;
}

$nav_buttons = <<<end
<div id = "prynav" >
<ul>

<li class = "link_buttons">
	<form method = "GET" action = "/onlinetutor/common/profile.php" >
		<input type = "hidden" name = "user_id" value = "$L_id" />
		<input type = "submit" id = "mylecturers_profile" class = "submit_buttons" name = "profile" value = "lecturer's profile"/>
	</form>
</li>

<li class = "link_buttons">
<form  method ="GET" action ="/onlinetutor/student_interface/coursemates.php" >
<input type = "submit"  id = "my_course_mates" class = "submit_buttons" name = "coursemates" value = "View coursemates" />
</form>
</li>

<li class = "link_buttons">
<form  method = "GET" action = "/onlinetutor/student_interface/courses.php" >
<input type = "submit" class = "submit_buttons" name = "registered_courses" id = "my_courses" value = "Registered Courses"/>
</form>
</li>

<li class = "link_buttons">
	<form method = "GET" action = "/onlinetutor/student_interface/courses.php" >
	  <input type = "submit" class = "submit_buttons" id = "available_courses" name = "courses" value ="view courses"/>
	</form>
</li>

<li class = "link_buttons">
	<form method = "GET" action = "/onlinetutor/student_interface/test.php" >
	  <input type = "submit" class = "submit_buttons" id = "available_tests" name = "tests" value = "View Tests" />
	</form>
</li>

<li class = "link_buttons">
	<form  method = "GET" action = "/onlinetutor/student_interface/scores.php" >
	  <input type = "submit" class = "submit_buttons" id = "your_scores" name = "scores" value = "scores" />
	</form>
</li>

<li class = "link_buttons">
	<form method = "GET" action = "/onlinetutor/student_interface/Sdiscussion.php" >
	  <input type = "submit" class = "submit_buttons" id = "discussions" name = "Sdiscussion" value = "Discussion" />
	</form>
</li>

<li class = "link_buttons">
	<form  method = "GET" action = "announcement.php" >
	  <input type = "submit" class = "submit_buttons" id = "announcements" name = "view_announcement" value = "Announcement" />
	</form>
</li>

<li class = "link_buttons">
	<form  target = "first_iframe" method = "GET" action = "/onlinetutor/common/friends.php" >
	  <input type = "submit" class = "submit_buttons" id = "my_friends" name = "friends" value = "Friends" />
	</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/onlinetutor/student_interface/lecture_note.php" >
<input type = "submit" class = "submit_buttons" id = "get_lecture_note" name = "lecture_note" value = "Lecture Note" />
</form>
</li>

<li class = "link_buttons">
	<form method = "GET" action = "/onlinetutor/student_interface/Svideos.php" >
	  <input type = "submit" class = "submit_buttons" id = "viewVideos" name = "view_videos" value = "Videos" />
	</form>
</li>


<li class = "link_buttons">
	<form target = "first_iframe" method = "GET" action = "/onlinetutor/student_interface/mynote.php" >
	  <input type = "submit" class = "submit_buttons"   id = "get_my_note" name = "mynote" value = "Take Note" "/>
	</form>
</li>

</ul>
</div>

<div id = "secnavdiv" >
<ul id = "secnav">
<li>
<hr class = "navbar" /> <hr class = "navbar" /> <hr class = "navbar" />

<ul>
<li class = "link_buttonss">
<form target = "first_iframe" id = "sfeedback" method = "GET" action = "/onlinetutor/common/feedback.php" >
<input type = "submit" class = "submit_buttonss" id = "sfeedbacks" name = "feedback" value = "Feedback" />
</form>
</li>

<li class = "link_buttonss">
<form id = "slog_out" method = "GET" action = "/onlinetutor/common/login.php" >
<input type = "submit" id = "slogout" class = "submit_buttonss" value = "log out" name = "log_out" />
</form>
</li>

<li class = "link_buttonss">
<form id = "shelpForm" method = "GET" action = "/onlinetutor/common/help.php" >
<input type = "submit" id = "shelp" class = "submit_buttonss" name = "get_help" value = "help" />
</form>
</li>

<li class = "link_buttonss">
<form id = "scalcForm" method = "GET" action = "/onlinetutor/common/calculator.html" >
<input type = "submit" id = "scalc" class = "submit_buttonss" name = "get_calculator" value = "calculator" />
</form>
</li>
</ul>

</li>
</ul>
</div>
end;
}


if(isset($_GET["delete"]))	{
	//the student choose to delete the lecturer
	if($L_id == ""){
		$heading = ""; $L_image_url = "";	$nav_buttons = "";
		$display = "<p>You can't perform this action because you are not registered with any lecturer</p>";
	}	else	{
		$lecturers_table = "std".$S_id."_lecturers";
		$query_string = "delete from $lecuteres_table where lecturer_id = \"$L_id\"";
		run_query();
		if($rows == 1){
			$display = "<p>The lecturer have be remove from your record</p>";
		} elseif($rows == 0){
			$display = "<p>That name does not exit. No action was taken</p>";
		}
	}
}

} 	else	{
header("Location:/onlinetutor/login.php");  		//user do not have an active session
exit();
}
?>


<!DOCTYPE html>
<html>
<head>
<title><?php echo $title_display ?></title>
<link type = "text/css" rel = "stylesheet" href = "/onlinetutor/mylecapp_css/style1.css" />
<meta name = "viewport" content = "width=device-width, initial-scale=1" >
</head>
<body>
<div id = "body_div">
<div id = "app_logo_area">
<p id = "app_logo">MyLecApp</p>
<p id = "app_desc">Lecturer-Student Interaction cannot be more better. interact more effectively with your lecturers</p>
<div id = "nav_buttons" >
<img id = "user_img" src = "<?php echo $user_image_url; ?>" alt = "image" />
<?php echo $nav_buttons; ?>
</div>
</div>
<div><p id = "ajax_neutral">Processing ...</p></div>
<div id = "use_calculator"> </div>
<div id = "chat_div" class = "js_hide"><p  id = "close_chat">x</p> </div>
<div id = "main_content" name = "main_content_div"><?php echo $display; ?></div>
<div id = "side_content" name = "side_div">
</div>
<div id = "doc_foot" name = "foot">
</div>
<script type = "text/javascript" src = "/onlinetutor/jsfiles/funcs.js"></script>
<script type = "text/javascript" src = "/onlinetutor/jsfiles/quotes.js"></script>
</body>
</html>
