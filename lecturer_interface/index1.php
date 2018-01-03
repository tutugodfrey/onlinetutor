<?php
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$user_image_url = $_SESSION["user_image_url"];


$display = "";
if(!$_POST){

$nav_buttons = <<<block
<ul>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/mylecturerapp/common/profile.php" >
<input type = "hidden" value = "$class" name = "class" />
<input type = "hidden" value = "$owner_id" name = "user_id" />
<input type = "submit" class = "submit_buttons" value = "View Profile" name = "profile" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "save_course.php" >
<input type = "submit" class = "submit_buttons" value = "Add Course" name = "save_courses" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "registered_students.php" >
<input type = "submit" class = "submit_buttons" value = "Students" name = "registered_students" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "set_questions.php" >
<input type = "submit" class = "submit_buttons" value = "Set Questions" name = "set_questions" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "discussion.php" ></li><li class = "link_buttons" class = "submit_buttons">
<input type = "submit" class = "submit_buttons" value = "Create Discussion" name = "discussion" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "POST" action = "lecture_note.php" >
<input type = "submit" class = "submit_buttons" name = "lecture_note" value = "Save Note" />
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "videos.php" >
<input type = "submit" class = "submit_buttons" name = "add_video" value = "Add Video" />
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "opentest.php">
<input type = "submit" class = "submit_buttons" value = "Tests/Exams" name = "tests" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "results.php">
<input type = "submit" class = "submit_buttons" value = "Results" id= "result" name = "results" />
</form>
</li>
<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "announcement.php">
<input type = "submit" class = "submit_buttons" value = "Annoucement" name = "announcement" />
</form>
</li>

<li class = "link_buttons">
<form target = "first_iframe" id = "friend_form" method = "GET" action = "/mylecturerapp/common/friends.php">
<input type = "submit" id = "friendForm" class = "submit_buttons" value = "Friends" name = "friends" />
</form>
</li>


<li class = "link_buttons">
<form target = "first_iframe" method = "GET" action = "/mylecturerapp/common/feedback.php">
<input type = "submit" class = "submit_buttons" value = "Feedback" name = "feedback" />
</form>
</li>
<li class = "link_buttons">
<form  id = "log_out" method = "GET" action = "/mylecturerapp/common/login.php" >
<input type = "submit" class = "submit_buttons" value = "log out" name = "log_out" />
</form>
</li>
<li class = "link_buttons">
<form id = "helpForm" method = "GET" action = "/mylecturerapp/common/help.php" >
<input type = "submit" id = "help" class = "submit_buttons" name = "get_help" value = "help" />
</form>
</li>
</ul>
block;

}

}	else	{	//if there is not active user session
$display = "<p>You do not have an active user session. please go back and login</p>";
$nav_buttons = "";
}
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<link type = "text/css" rel = "stylesheet" href = "/mylecturerapp/mylecapp_css/style2.css" />
<script type = "text/javascript">window.location = "/mylecturerapp/lecturer_interface/index.php"</script>

<style type = "text/css"> 
.videos {position:relative; top:50px; left:10px; float:left; clear:both; }     </style>
</head>
<body>
<div id = "body_div">
<div id = "app_logo_area">
<p id = "app_logo">MyLecApp</p><p id = "app_desc">Lecturer-Stuedent Interaction cannot be more better. interact more effectively with your students</p></div>
<div id = "nav_buttons" > <img src = "<?php echo $user_image_url; ?>" alt = "image" />
 <?php echo $nav_buttons; ?></div>
<?php echo $display; ?>
<div id = "main_content" ><iframe id = "L_iframe" name = "first_iframe"></iframe></div>
<div id = "side_content"> <iframe id = "side_iframe" name = "second_iframe"> </iframe></div>
<div>
</body>
</html>