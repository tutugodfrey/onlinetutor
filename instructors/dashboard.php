<?php
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$class = $_SESSION["class"];
$user_image_url = $_SESSION["user_image_url"];

$display = "";

if(!$_POST){

$nav_buttons = <<<block
<div id = "prynav" >
<ul>

<li class = "link_buttons">
<form method = "GET" action = "./../common/profile.php" >
<input type = "hidden" value = "$class" name = "class" />
<input type = "hidden" value = "$owner_id" name = "user_id" />
<input type = "submit" class = "submit_buttons" value = "View Profile" id = "lecturers_profile" name = "profile" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "./save_course.php" >
<input type = "submit" id = "save_course" class = "submit_buttons" value = "Add Course" name = "save_courses" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "./registered_students.php" >
<input type = "submit" class = "submit_buttons" value = "Students" id = "your_students" name = "registered_students" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "./set_questions.php" >
<input type = "submit" class = "submit_buttons" value = "Set Questions" id = "questions" name = "set_questions" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "./discussion.php" >
<input type = "submit" class = "submit_buttons" value = "Create Discussion" id = "create_discussion" name = "discussion" />
</form>
</li>
<li class = "link_buttons">
<form method = "POST" action = "./lecture_note.php" >
<input type = "submit" class = "submit_buttons" id = "save_note" name = "lecture_note" value = "Save Note" />
</form>
</li>

<li class = "link_buttons">
<form method = "GET" action = "./videos.php" >
<input type = "submit" class = "submit_buttons" id = "addVideo" name = "add_video" value = "Add Video" />
</form>
</li>

<li class = "link_buttons">
<form method = "GET" action = "./opentest.php">
<input type = "submit" class = "submit_buttons" value = "Tests/Exams" id = "tests" name = "tests" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "./results.php">
<input type = "submit" class = "submit_buttons" value = "Results" id= "result" name = "results" />
</form>
</li>
<li class = "link_buttons">
<form method = "GET" action = "./announcement.php">
<input type = "submit" id = "announcements" class = "submit_buttons" value = "Annoucement" name = "announcement" />
</form>
</li>

<li class = "link_buttons">
<form id = "friend_form" method = "GET" action = "./../common/friends.php">
<input type = "submit" id = "friendForm" class = "submit_buttons" value = "Friends" name = "friends" />
</form>
</li>

</ul>
</div>

<div id = "secnavdiv" >
<ul id = "secnav" >
<li>

<hr class = "navbar" /><hr class = "navbar" /><hr class = "navbar" />
<ul>
<li class = "link_buttonss">
<form id = "sfeedback" method = "GET" action = "./../common/feedback.php">
<input type = "submit" id = "feedbacks" class = "submit_buttonss" value = "Feedback" name = "feedback" />
</form>
</li>

<li class = "link_buttonss">
<form  id = "slog_out" method = "GET" action = "./../common/login.php" >
<input type = "submit" id = "logout" class = "submit_buttonss" value = "log out" name = "log_out" />
</form>
</li>

<li class = "link_buttonss">
<form id = "shelpForm" method = "GET" action = "./../common/help.php" >
<input type = "submit" id = "help" class = "submit_buttonss" name = "get_help" value = "help" />
</form>
</li>

<li class = "link_buttonss">
<form id = "scalcForm" method = "GET" action = "./../common/calculator.html" >
<input type = "submit" id = "calc" class = "submit_buttonss" name = "get_calculator" value = "calculator" />
</form>
</li>
</ul>

</li>
</ul>
</div>
block;

}



if(isset($_POST["get_username"])) { //for js to initiate localStorage
$display = $L_username;
}



}	else	{	//if there is not active user session
header("Location:/onlinetutor/login.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<link type = "text/css" rel = "stylesheet" href = "/onlinetutor/mylecapp_css/style1.css" />
<meta name = "viewport" content = "width=device-width, initial-scale=1" >
</head>
<body id = "bdy">
 <div id = "body_div">
  <div id = "app_logo_area">
    <p id = "app_logo">MyLecApp</p>
    <p id = "app_desc">Lecturer-Student Interaction cannot be more better. interact more effectively with your students</p>
    <div id = "nav_buttons" >
     <img src = "<?php echo $user_image_url; ?>" alt = "image" />
     <?php echo $nav_buttons; ?>
    </div>
  </div>
  <div><p id = "ajax_neutral">Processing ...</p></div>
<div id = "use_calculator"> </div>
<div id = "chat_div" class = "js_hide" ><p  id = "close_chat">x</p> </div>
  <div id = "main_content" name = "main_content_div">
     <?php echo $display; ?>
  </div>
  <div id = "side_content" name = "side_div">
  </div>
  <div id = "doc_foot" name = "foot">
  </div>
</div>
<script type = "text/javascript" src = "./../jsfiles/funcs.js"></script>
<script type = "text/javascript" src = "./../jsfiles/quotes.js"></script>
</body>
</html>
