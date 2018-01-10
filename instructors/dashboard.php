<?php
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$user_image_url = $_SESSION["user_image_url"];

$display = "";

if(isset($_GET["dashboard"])){

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
            <a href = "/onlinetutor/common/profile.php?profile&user_id=$owner_id" class = "nav-link">Your Profile</a>
          </li>
          <li class = "link_buttonss nav-item">
            <a href = "/onlinetutor/common/feedback.php?feedback" class = "nav-link">Feedback</a>
          </li>
          <li class = "link_buttonss nav-item">
            <a href = "/onlinetutorp/common/login.php?log_out" class = "nav-link">Logout</a>
          </li>
          <li class = "link_buttons nav-item">
            <a href = "/onlinetutor/common/calculator.php?get_calculator" class = "nav-link">Calculator</a>
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

}	else	{	//if there is not active user session
header("Location:/onlinetutor/common/login.php");
exit();
}
?>


<?php echo $nav_buttons; ?>


