<?php
//include the db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";


session_start();
if(isset($_SESSION["owner_id"])){
	$owner_id = $_SESSION["owner_id"];
	//$user_image_url = $_SESSION["user_image_url"];
} else if (isset($_GET["user_id"])) {
	$owner_id = $_GET["user_id"];
	$_SESSION["owner_id"] = $owner_id;
} else	{
  header("Location: /onlinetutor/common/login.php");  		//user do not have an active session
  exit();
}

$display = "";
if(isset($_GET["dashboard"])){
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
			select_option($values, "lecturers", "user_id", "", "sr-only");
		}
	}

	//an html to display some command
	$display = <<<end
		<nav class="navbar navbar-toggleable-md navbar-light">
      <a class="navbar-brand" href="#">L</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#prynav" aria-controls="prynav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="prynav">
        <ul class="navbar-nav mr-auto">
        <!-- hide this element display when a user select a lecturer and construct thefull url or get from localStorage -->
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/home.php?" class = "nav-link">Home</a>
        </li>
        <li id = "instructor-profile-link" class = "link_buttons nav-item hide-item" >
          <a  id = "profile-link" href = "/onlinetutor/common/profile.php?profile&user_id=" class = "nav-link">Instructor Profile</a>
        </li>
        <li class = "link-buttons nav-item">
          <form method = "GET" action = "$_SERVER[PHP_SELF]" >
            <div> $select_result </div>
            <input type = "submit" id = "choose_lec" class = "submit-buttons hide-item" value = "Select Lecturer" name = "select" />
            <!-- <input type = "submit" id = "del_lec" class = "submit-buttons" value = "Delete" name = "delete" /> -->
          </form>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/coursemates.php?coursemates" class = "nav-link">Coursemates</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/courses.php?registered_courses" class = "nav-link">Your Courses</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/courses.php?courses" class = "nav-link">Courses</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/test.php?tests" class = "nav-link">Tests</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/scores.php?scores" class = "nav-link">Scores</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/discussions.php?discussions" class = "nav-link">Discussions</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/announcements.php?announcements" class = "nav-link">Announcemnts</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/common/friends.php?friends" class = "nav-link">Friends</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/lecture_note.php?lecture_note" class = "nav-link">Lecture Notes</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/videos.php?view_videos" class = "nav-link">Videos</a>
        </li>
        <li class = "link-buttons nav-item">
          <a href = "/onlinetutor/students/mynote.php?mynote" class = "nav-link">Notes</a>
        </li>
        <li class = "link-buttons nav_item">
          <form id = "search_lec_form" class = "form-inline my-2 my-lg-0 mr-auto" method = "GET" action = "/onlinetutor/common/search_names.php" >
            <input type = "text" class = "link_buttons form-control mr-sm-2 form-control-sm" id = "search-lecturers" name = "name_like" placeholder = "search for lecturer"/>
          <input type = "submit" id = "search_lec" class = "btn btn-success my-2 my-sm-0 hide-item" name = "search_lecturers" value = "Search" />
          </form>
        </li>
        <li class = "link_buttons nav-item">
          <form name = "reg_lecturer" id = "reg_lec_form" class="form-inline my-2 my-lg-0" method = "POST" action = "/onlinetutor/common/profile.php" >
            <div id = "lecturers-name" class = "link_buttons"></div>
            <input type = "submit"  id = "register-lecturer" class = "btn btn-success hide-item" name = "register_lecturer" value = "Register" />
          </form>
        </li>
      </ul>
    </div>
    </nav>
	<!-- <div id = "secnavdiv" class = "page-header navbar">
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
	</div> -->
end;
}



//  set session variable when a student select a particular lecturer
// 
if(isset($_GET["select"])){
	if(!isset($_GET["user_id"])){
		$L_id = "";
	}	else	{
		$L_id = $_GET["user_id"];
		$_SESSION["L_id"] = $L_id;		//set L_id to the global session variable
		$_SESSION["lecturer_db"] = "lec".$L_id;
		$query_string = "select id, lastname, firstname, user_type, picture from registered_users where id = \"$L_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "Error setting the selected lecturer";
		}	else 	{
			$value = build_array($row_num2);
			$display = json_encode($value);
		}
	}
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

?>


<?php echo $display; ?>


