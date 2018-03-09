<?php
//include the db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";



session_start();
if(isset($_SESSION["owner_id"])){
	$owner_id = $_SESSION["owner_id"];
	//$user_image_url = $_SESSION["user_image_url"];
} else if (isset($_GET["user_id"])) {
	$owner_id = $_GET["user_id"];
	$_SESSION["owner_id"] = $owner_id;
} else	{
  header("Location: /common/login.php");  		//user do not have an active session
  exit();
}

$display = "";
if(isset($_GET["dashboard"])){
	$friends_table = "user".$owner_id."_friends";
	$query_string = "select lec_id from students where student_id = $owner_id and confirm = \"yes\"";		//getting ids of lecturers
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
			select_option($values, "lecturers", "user_id", "form-control", "sr-only");
		}
	}

	//an html to display some command
	$display = <<<end
		<div class = "container-fluid">
			<div class = "row">
			<nav class="navbar nav-tabs navbar-toggleable-md navbar-light col-md-9 pr-0">
			<a id = "brand-logo" class="navbar-brand" href="#">L</a>
      <button id = "toggler-btn" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#pry-nav" aria-controls="pry-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="pry-nav">
        <ul class="navbar-nav mr-auto">
	        <!-- hide this element display when a user select a lecturer and construct thefull url or get from localStorage -->
	        <li class = "link-buttons nav-item">
	          <a href = "/students/home.php?" class = "nav-link active">Home</a>
	        </li>
	        <li class = "link-buttons nav-item">
	          <form method = "GET" action = "$_SERVER[PHP_SELF]" >
	            <div class = "form-group"> $select_result </div>
	            <input type = "submit" id = "choose_lec" class = "submit-buttons hide-item" value = "Select Lecturer" name = "select" />
	            <!-- <input type = "submit" id = "del_lec" class = "submit-buttons" value = "Delete" name = "delete" /> -->
	          </form>
	        </li>
	        <li class = "link-buttons nav-item">
	          <a href = "/students/coursemates.php?coursemates" class = "nav-link">Coursemates</a>
	        </li>
	        <li class="nav-item dropdown">
	          <a class="nav-link dropdown-toggle" href="#" id="courses-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	            Courses
	          </a>
	          <div class="dropdown-menu" aria-labelledby="courses-menu">
							<a href = "/students/courses.php?registered_courses" class = "nav-link">Your Courses</a>
							<a href = "/students/courses.php?courses" class = "nav-link">Courses</a>
	          </div>
	        </li>
	        <li class="nav-item dropdown">
	          <a class="nav-link dropdown-toggle" href="#" id="activities-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	            Activities
	          </a>
	          <div class="dropdown-menu" aria-labelledby="activities-menu">
							<a href = "/students/test.php?tests" class = "nav-link">Tests</a>
							<a href = "/students/discussions.php?discussions" class = "nav-link">Discussions</a>
							<a href = "/students/scores.php?scores" class = "nav-link">Scores</a>
	          </div>
	        </li>
	        <li class = "link-buttons nav-item">
	          <a href = "/common/friends.php?friends" class = "nav-link">Friends</a>
	        </li>
	        <li class = "link-buttons nav-item">
	          <a href = "/students/lecture_note.php?lecture_note" class = "nav-link">Lecture Notes</a>
	        </li>
	        <li class = "link-buttons nav-item">
	          <a href = "/students/videos.php?view_videos" class = "nav-link">Videos</a>
	        </li>
	        <li class = "link-buttons nav-item">
	          <a href = "/students/mynote.php?mynote" class = "nav-link">Notes</a>
	        </li>
	         <li class="nav-item dropdown">
	          <a class="nav-link dropdown-toggle" href="#" id="more-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	            More
	          </a>
	          <div class="dropdown-menu" aria-labelledby="more-menu">
							<a href = "/common/profile.php?profile&user_id=$owner_id" class = "nav-link">Your Profile</a>
	         		<a  id = "profile-link" href = "/common/profile.php?profile&user_id=" class = "nav-link">Instructor Profile</a>

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
	  <div id = "reg-lecturer-form-group" class = "col-sm-12 col-md-3 px-0 align-self-end">
	  	<div class = "d-flex flex-row">
	  	<div class = "align-self-start">
		  	<form name = "reg_lecturer" id = "reg_lec_form" class="form-inline" method = "POST" action = "/common/profile.php" >
		      <div id = "lecturers-name" class = "link_buttons"></div>
		      <input type = "submit"  id = "register-lecturer" class = "btn btn-success hide-item" name = "register_lecturer" value = "Register" />
		    </form>
	    </div>
	    <div class = "align-self-end">
		    <form id = "search_lec_form" class = "form-inline" method = "GET" action = "/common/search_names.php" >
		      <input type = "search" class = "link_buttons form-control mr-sm-2 form-control-sm" id = "search-lecturers" name = "name_like" placeholder = "search for lecturer"/>
		    <input type = "submit" id = "search_lec" class = "btn btn-success my-2 my-sm-0 hide-item" name = "search_lecturers" value = "Search" />
		    </form>
	    </div>
	    </div>
    </div>
	</div>
	</div>

end;
}



//  set session variable when a student select a particular lecturer
// 
if(isset($_GET["select"])){
	if(!isset($_GET["user_id"])){
		$lec_id = "";
	}	else	{
		$lec_id = $_GET["user_id"];
		$_SESSION["lec_id"] = $lec_id;
		$query_string = "select id, lastname, firstname, user_type, picture from registered_users where id = \"$lec_id\"";
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
	if($lec_id == ""){
		$heading = ""; $L_image_url = "";	$nav_buttons = "";
		$display = "<p>You can't perform this action because you are not registered with any lecturer</p>";
	}	else	{
		$lecturers_table = "std".$S_id."_lecturers";
		$query_string = "delete from $lecuteres_table where lecturer_id = \"$lec_id\"";
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


