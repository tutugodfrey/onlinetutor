<?php
include "./../includes/db_connect.php";
include "./../includes/functions.php";


session_start();
if(isset($_SESSION["owner_id"])){
  $owner_id = $_SESSION["owner_id"];
  $_SESSION["lecturer_db"] = "lec".$owner_id;
  //$user_image_url = $_SESSION["user_image_url"];
} else if (isset($_GET["user_id"])) {
  $owner_id = $_GET["user_id"];
  $_SESSION["owner_id"] = $owner_id;
  $_SESSION["lecturer_db"] = "lec".$owner_id;
} else  {
  header("Location: /onlinetutor/common/login.php");      //user do not have an active session
  exit();
}

if(isset($_GET["dashboard"])){
$nav_buttons = <<<block
  <nav class="navbar nav-tabs navbar-toggleable-md navbar-light">
    <a id = "brand-logo" class="navbar-brand" href="#">L</a>
    <button id = "toggler-btn" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#pry-nav" aria-controls="pry-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="pry-nav">
      <ul class="navbar-nav mr-auto">
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/home.php?" class = "nav-link active">Home</a>
        </li>
        <li class = "link_buttons nav-item">
          <a id = "save_course" href = "./save_course.php?save_courses" class = "nav-link">Save Courses</a>
        </li>
        <li class = "link_buttons nav-item">
      <a id = "your_students" href = "./registered_students.php?registered_students" class = "nav-link">Students</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "questions" href = "./set_questions.php?set_questions" class = "nav-link">Set Question</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "discussions" href = "./discussions.php?discussions" class = "nav-link">Discussions</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "save_note" href = "./lecture_note.php?lecture_note" class = "nav-link">Save Note</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "addVideo" href = "./videos.php?add_video" class = "nav-link">Add Videos</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "tests" href = "./opentest.php?tests" class = "nav-link">Test/Exams</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id= "results" href = "./results.php?results" class = "nav-link">Results</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "announcements" href = "./announcements.php?announcements" class = "nav-link">Announcements</a>
    </li>
    <li class = "link_buttons nav-item">
      <a id = "friends" href = "./../common/friends.php?friends" class = "nav-link">Friends</a>
    </li>
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="more-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            More
          </a>
          <div class="dropdown-menu" aria-labelledby="more-menu">
            <a href = "/common/profile.php?profile&user_id=$owner_id" class = "nav-link">Your Profile</a>
            <a href = "/students/announcements.php?announcements" class = "nav-link">News</a>
            <a href = "/common/feedback.php?feedback" class = "nav-link">Feedback</a>
            <a href = "/common/login.php?log_out" class = "nav-link">Logout</a>
            <a href = "/common/calculator.php?get_calculator" class = "nav-link">Calculator</a>
            <a href = "/common/help.php?get_help" class = "nav-link">Help</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
block;
}

?>


<?php echo $nav_buttons; ?>


