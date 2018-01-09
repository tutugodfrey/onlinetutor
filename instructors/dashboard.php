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
<div id = "prynav" class = "page-header navbar">
    <ul class = "nav">
      <li class = "link_buttons">
        <a href = "./../common/profile.php?profile&user_id=$owner_id" class = "link-item">Profile</a>
      </li>
      <li class = "link_buttons">
        <a id = "save_course" href = "./save_course.php?save_courses" class = "link-item">Save Courses</a>
      </li>
      <li class = "link_buttons">
        <a id = "your_students" href = "./registered_students.php?registered_students" class = "link-item">Students</a>
      </li>
      <li class = "link_buttons">
        <a id = "questions" href = "./set_questions.php?set_questions" class = "link-item">Set Question</a>
      </li>
      <li class = "link_buttons">
        <a id = "discussions" href = "./discussions.php?discussions" class = "link-item">Discussions</a>
      </li>
      <li class = "link_buttons">
        <a id = "save_note" href = "./lecture_note.php?lecture_note" class = "link-item">Save Note</a>
      </li>
      <li class = "link_buttons">
        <a id = "addVideo" href = "./videos.php?add_video" class = "link-item">Add Videos</a>
      </li>
      <li class = "link_buttons">
        <a id = "tests" href = "./opentest.php?tests" class = "link-item">Test/Exams</a>
      </li>
      <li class = "link_buttons">
        <a id= "results" href = "./results.php?results" class = "link-item">Results</a>
      </li>
      <li class = "link_buttons">
        <a id = "announcements" href = "./announcements.php?announcements" class = "link-item">Announcements</a>
      </li>
      <li class = "link_buttons">
        <a id = "friends" href = "./../common/friends.php?friends" class = "link-item">Friends</a>
      </li>
    </ul>
  </div>
  <div id = "secnavdiv" class = "page-header navbar">
    <ul id = "secnav" class = "nav" >
      <li class = "nav-item">
        <hr/> <hr/> <hr/>
        <ul class = "nav">
          <li class = "link_buttons nav-item">
            <a href = "/onlinetutor/common/profile.php?profile&user_id=$owner_id" class = "link-item">Your Profile</a>
          </li>
          <li class = "link_buttonss nav-item">
            <a href = "/onlinetutor/common/feedback.php?feedback" class = "nav-link">Feedback</a>
          </li>
          <li class = "link_buttonss nav-item">
            <a href = "/onlinetutorp/common/login.php?log_out" class = "nav-link">Logout</a>
          </li>
          <li class = "link_buttons nav-item">
            <a href = "/onlinetutor/common/calculator.php?get_calculator" class = "nav-item">Calculator</a>
          </li>
          <li class = "link_buttonss nav-item">
            <a href = "/onlinetutor/common/help.php?get_help" class = "nav-link">Help</a>
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
