<?php
include "./../includes/db_connect.php";
include "./../includes/functions.php";


session_start();
if(isset($_SESSION["owner_id"])){
  $owner_id = $_SESSION["owner_id"];
  $_SESSION["lecturer_db"] = "lec".$owner_id;
  $user_image_url = $_SESSION["user_image_url"];
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
    <div class = "container-fluid">
      <div class = "row">
        <div id = "site-logo" class = "px-0 ml-sm-0 col-2 offset-sm-1">
          <a href = './default.html' class = 'nav-lin' >
            <img id = "brand-logo" class = "img-fluid" src = "/images/logo.png" alt ="site logo" />
          </a>
        </div>
        <div id = "top-header" class = "ml-auto col-7 col-sm-5 col-lg-3 offset-1 offsetd-md-2 offset-lg-3 offset-xg-4">
          <ul class = "nav">
            <li class = "nav-item">
              <a href = "/common/profile.php?profile&user_id=$owner_id" class = "pl-0 nav-link">
                <img id = "student-profile-pix" class = "px-1 img-fluid img-thumbnail" src = "$user_image_url" alt = "profile image" />
              </a>
            </li>
            <li class = "nav-item">
              <a href = "/common/calculator.php?get_calculator" class = "px-1 nav-link">Calculator</a>
            </li>
            <li class = "nav-item">
              <a href = "/common/help.php?get_help" class = "px-1 nav-link">Help</a>
            </li>
            <li  id = "logout" class = "nav-item">
              <a href = "/common/login.php?log_out" class = "px-1 nav-link">Logout</a>
            </li>
          </ul>
      </div>
    </div>
  </div>
  <div class = "container">
    <div class = "row">
      <nav class="navbar nav-tabs navbar-toggleable-md navbar-light col-3 col-md-9 pr-0 ml-auto">

        <button id = "toggler-btn" class="navbar-toggler navbar-toggler-left" type="button" data-toggle="collapse" data-target="#pry-nav" aria-controls="pry-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a></a>
        <div class="collapse navbar-collapse" id="pry-nav">
          <ul class="navbar-nav mr-auto">
            <li class = "link-buttons nav-item">
              <a href = "/students/home.php?" class = "nav-link active">Home</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "save_course" href = "/instructors/save_course.php?save_courses" class = "nav-link">Courses</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "your_students" href = "/instructors/registered_students.php?registered_students" class = "nav-link">Students</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "questions" href = "/instructors/set_questions.php?set_questions" class = "nav-link">Questions</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "discussions" href = "/instructors/discussions.php?discussions" class = "nav-link">Discussions</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "save_note" href = "/instructors/lecture_note.php?lecture_note" class = "nav-link">Notes</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "addVideo" href = "/instructors/videos.php?add_video" class = "nav-link">Videos</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "tests" href = "/instructors/opentest.php?tests" class = "nav-link">Test/Exams</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id= "results" href = "/instructors/results.php?results" class = "nav-link">Results</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "friends" href = "/common/friends.php?friends" class = "nav-link">Friends</a>
            </li>
            <li class = "link_buttons nav-item">
              <a id = "announcements" href = "/instructors/announcements.php?announcements" class = "nav-link">News</a>
            </li>
   
          </ul>
        </div>
      </nav>
      <div class = "col-9 col-md-3 offset-md-3 ml-auto">
        <form id = "search_lec_form" class = "form-inline" method = "GET" action = "/common/search_names.php" >
          <input type = "search" size = "22" class = "link_buttons form-control mr-sm-2 form-control-sm" id = "search-lecturers" name = "name_like" placeholder = "search"/>
        <input type = "submit" id = "search_lec" class = "btn btn-success my-2 my-sm-0 hide-item" name = "search_lecturers" value = "Search" />
        </form>
      </div>
    </div>
  </div>
block;
}

?>


<?php echo $nav_buttons; ?>


