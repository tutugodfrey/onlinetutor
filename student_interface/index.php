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
<form   method = "GET" action = "/mylecturerapp/common/profile.php" >
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
<form id = "search_lec_form" method = "GET" action = "/mylecturerapp/common/search_names.php" >
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
<form name = "reg_lecturer" id = "reg_lec_form" method = "GET" action = "/mylecturerapp/common/profile.php" >
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
<form target = "first_iframe" id = "sfeedback" method = "GET" action = "/mylecturerapp/common/feedback.php" >
<input type = "submit" class = "submit_buttonss" name = "feedback" value = "Feedback" />
</form>
</li>

<li class = "link_buttonss">
<form id = "slog_out" method = "GET" action = "/mylecturerapp/common/login.php" >
<input type = "submit" id = "slogout" class = "submit_buttonss" value = "log out" name = "log_out" />
</form>
</li>

<li class = "link_buttonss">
<form id = "shelpForm" method = "GET" action = "/mylecturerapp/common/help.php" >
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
<form method = "GET" action = "/mylecturerapp/common/profile.php" >
<input type = "hidden" name = "user_id" value = "$L_id" />
<input type = "submit" id = "mylecturers_profile" class = "submit_buttons" name = "profile" value = "lecturer's profile"/>
</form>
</li>

<li class = "link_buttons">
<form  method ="GET" action ="/mylecturerapp/student_interface/coursemates.php" >
<input type = "submit"  id = "my_course_mates" class = "submit_buttons" name = "coursemates" value = "View coursemates" />
</form>
</li>

<li class = "link_buttons">
<form  method = "GET" action = "/mylecturerapp/student_interface/courses.php" >
<input type = "submit" class = "submit_buttons" name = "registered_courses" id = "my_courses" value = "Registered Courses"/>
</form>
</li>

<li class = "link_buttons">
<form method = "GET" action = "/mylecturerapp/student_interface/courses.php" >
<input type = "submit" class = "submit_buttons" id = "available_courses" name = "courses" value ="view courses"/>
</form>
</li>

<li class = "link_buttons">
<form method = "GET" action = "/mylecturerapp/student_interface/test.php" >
<input type = "submit" class = "submit_buttons" id = "available_tests" name = "tests" value = "View Tests" />
</form>
</li>

<li class = "link_buttons">
<form  method = "GET" action = "/mylecturerapp/student_interface/scores.php" >
<input type = "submit" class = "submit_buttons" id = "your_scores" name = "scores" value = "scores" />
</form>
</li>

<li class = "link_buttons">
<form method = "GET" action = "/mylecturerapp/student_interface/Sdiscussion.php" >
<input type = "submit" class = "submit_buttons" id = "discussions" name = "Sdiscussion" value = "Discussion" />
</form>
</li>

<li class = "link_buttons">
<form  method = "GET" action = "announcement.php" >
<input type = "submit" class = "submit_buttons" id = "announcements" name = "view_announcement" value = "Announcement" />
</form>
</li>

<li class = "link_buttons">
<form  target = "first_iframe" method = "GET" action = "/mylecturerapp/common/friends.php" >
<input type = "submit" class = "submit_buttons" id = "my_friends" name = "friends" value = "Friends" />
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/mylecturerapp/student_interface/lecture_note.php" >
<input type = "submit" class = "submit_buttons" id = "get_lecture_note" name = "lecture_note" value = "Lecture Note" />
</form>
</li>

<li class = "link_buttons">
<form method = "GET" action = "/mylecturerapp/student_interface/Svideos.php" >
<input type = "submit" class = "submit_buttons" id = "viewVideos" name = "view_videos" value = "Videos" />
</form>
</li>


<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/mylecturerapp/student_interface/mynote.php" >
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
<form target = "first_iframe" id = "sfeedback" method = "GET" action = "/mylecturerapp/common/feedback.php" >
<input type = "submit" class = "submit_buttonss" id = "sfeedbacks" name = "feedback" value = "Feedback" />
</form>
</li>

<li class = "link_buttonss">
<form id = "slog_out" method = "GET" action = "/mylecturerapp/common/login.php" >
<input type = "submit" id = "slogout" class = "submit_buttonss" value = "log out" name = "log_out" />
</form>
</li>

<li class = "link_buttonss">
<form id = "shelpForm" method = "GET" action = "/mylecturerapp/common/help.php" >
<input type = "submit" id = "shelp" class = "submit_buttonss" name = "get_help" value = "help" />
</form>
</li>

<li class = "link_buttonss">
<form id = "scalcForm" method = "GET" action = "/mylecturerapp/common/calculator.html" >
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
//admin_connect();
//access the lecturer from the S_id table. you are still using the registration database
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
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}


?>


<!DOCTYPE html>
<html>

<head>
<title><?php echo $title_display ?></title>

<link type = "text/css" rel = "stylesheet" href = "/mylecturerapp/mylecapp_css/style1.css" />
<meta name = "viewport" content = "width=device-width, initial-scale=1" > 
<!-- to run intermetent style sheet -->
<style type = "text/css">


@media only screen and (max-width:480px) {
/*
#chat_msg_div	{
		height:200px; width:100px; overflow:scroll; 
		}
#chat_div {
	 background-color:#acd710; position:fixed;  left:18%; top:26px; z-index:5;   
		}
*/

img	{
		width:45px; height:45px;
	}
#user_img	{
			position:relative; left:0%;
		}

}


@media only screen and (max-width:480px)  {
body 	{
		font-size:8px; /*and (max-width:700px)   and (orientation:portrait)*/
		}


#body_div	{
	background-color:#9dcb15; border:0px; width:150%; height:1000px; margin:0px; margin-top:-15px;
	}
#app_logo_area {	
	position:fixed; top:1px; left:0px; height:120px; width:100%;
	}

#app_logo	{
		font-size:8px; border:2px solid white; width:5%; left:1%; top:-10px; height:35px; padding:1px; z-index:6;
		
		
	}

#app_desc 	{
	font-size:13px; border:2px solid white;	height:46px; width:70%; z-index:9; top:-60px;
		}


#nav_buttons {
	 witdh:100%; /*width:1505px;*/ height:90px; position:relative; left:1px; top:-65px; Z-index:3;
	}

.submit_buttons, .submit_button	{
	 border-bottom:3px solid #9dcb15; border-right:1px solid #9dcb15; border-top:none; 
	padding-right:1px; height:18px; position:relative; top:3px; left:5px; text-align:center; font-size:14px; margin-bottom:3px; margin-right:1px;
	}

#S_my_profile 	{
		position:relative; top:50px; left:0px;
		}

#lecturers 	{	
		position:relative; top:6px; left:-60px;
		}

#lecturers select, #reg_lec_form select	{
		 font-size:8px; height:20px
		}
#choose_lec 	{
		position:relative; left:18px; top:-12px;
		}
#del_lec	{
		position:relative; left:18px; top:-12px;
		}

#registerLecturer {
		position:relative; top:18px; left:-122px;
		}
#lecturers_name	{
			position:relative; top:30px; left:-210px;
		}
#enter_lecturer_name		{
			position:relative; left:-13px; top:-24px; height:10px; width:60px; font-size:8px;
			
			} 

#search_lec {
		position:relative; top:-22px; left:-3px;
		}



#main_content {
	  width:100%; height:1000px; position:absolute; top:120px; left:0%;

	}


#side_content {
	 height:1000px; width:50%; position:absolute; top:120px; left:100%;
	}


#close_chat {
		background:red; width:25px; height:15px; text-align:center; font-size:20px; padding-left:3px; padding-bottom:5px; position:relative; 			top:-20px; left:91%;
}
#chat_div {
		 position:fixed;  left:15%; top:168px; width:252px; height:355px; z-index:5; padding:1px; 
		 border:2px solid white;
	}
#chat_div img { 
		width:50px; height:50px; position:relative; top:-60px; left:0%;
		}
#chat_div #choosen	{
		position:relative; left:-48px; top:-8px;
		}
#chat_form {
		position:relative; top:-50px;
		}

#displayChat 	{
		Z-index:8; position:relative; height:20px; top:-120px; left:78%;
}

#chat_msg_div 	{
		Z-index:8; position:relative; height:200px; top:-45px; left:0%; overflow:scroll;
}



}

/*
@media only screen and (max-width:480px) and (orientation:landscape) {
body 	{
		font-size:8px;
		}
#body_div	{
	background-color:#9dcb15; border:0px; width:100%; height:1000px; margin:0px; margin-top:-15px;
	}
#app_logo_area {	
	position:fixed; top:2px; left:1px; height:80px; 
	}

#app_logo	{
		font-size:6px; border:2px solid white; width:15%; left:1%; top:-5px; height:20px; z-index:6;
	}
#app_desc 	{
	font-size:6px; border:2px solid white;	height:20px; width:50%; z-index:9; top:-35px;
		}

#nav_buttons {
	 witdh:100%; height:50px; position:relative; left:1px; top:-40px; Z-index:3;
	}

.submit_buttons, .submit_button	{
	 border-bottom:3px solid #9dcb15; border-right:1px solid #9dcb15; border-top:none; 
	padding-right:0px; height:18px; position:relative; top:0px; left:5px; text-align:center; font-size:14px; margin-bottom:3px; margin-right:1px;
	}

#S_my_profile 	{
		position:relative; top:20px; left:0px;
		}

#lecturers 	{
		position:relative; top:17px; left:0px; font-size:8px;
		}

#choose_lec 	{
		position:relative; left:0px; top:20px;
		}
#del_lec	{
		position:relative; left:0px; top:20px;
		}

#enter_lecturer_name		{
			position:relative; left:-47px; top:3px; width:70px; height:10px; font-size:8px;
			
			} 

#search_lec {
		position:relative; top:20px; left:-117px;
		}

#registerLecturer {
		position:relative; top:25px; left:150px;
		}
.link_buttons #registerLecturer, #lecturers_name	{
		display:block;
	}


#lecturers_name	{
			position:relative; top:10px; left:185px; height:13px; 
		}
#lecturers select, #lecturers_name select {
			font-size:6px; width:80px;
		}
#log_out	{
		position:absolute; left:89.5%; top:-55px; Z-index:15;
		}


#feedback 	{
		position:absolute; left:79%; top:-55px;
		}

#helpForm	{
		position:absolute; left:73.5%; top:-55px;
		}

#main_content {
	  width:70%; height:1000px; position:absolute; top:83px; left:0%;

	}


#side_content {
	 height:1000px; width:30%; position:absolute; top:83px; left:70%;
	
	}

}
*/

</style>

<!-- to run intermetent javascript -->
<script type = "text/javascript">

}
</script>
</head>
<noscript>
</noscript>
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

<script type = "text/javascript" src = "/mylecturerapp/jsfiles/funcs.js"></script>
<script type = "text/javascript" src = "/mylecturerapp/jsfiles/quotes.js"></script>
<script type = "text/javascript" src = "/mylecturerapp/jsfiles/student_request.js"></script>

</body>

</html>