<?php
//include the db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";



session_start();
if(isset($_SESSION["owner_id"])){
	$owner_id = $_SESSION["owner_id"];
	$user_image_url = $_SESSION["user_image_url"];
} else if (isset($_GET["user_id"])) {
	$owner_id = $_GET["user_id"];
	$_SESSION["owner_id"] = $owner_id;
} else	{
  header("Location: /common/login.php");  		//user do not have an active session
  exit();
}

$display = "";
if(isset($_GET["dashboard"])) {
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
				<div id = "site-logo" class = "px-0 ml-sm-0 col-2 offset-sm-1">
					<a href = './default.html' class = 'nav-lin' >
						<img id = "brand-logo" class = "img-fluid" src = "/images/logo.png" alt ="site logo" />
					</a>
				</div>
	  		<div id = "top-header" class = "ml-auto col-9 col-lg-7 col-xl-6 offset-1 offsetd-md-2 offset-lg-3 offset-xg-4">
	  			<ul class = "nav">
	  				<li class = "nav-item">
		          <div id = "lecturers-name" class = "align-self-start">
				    	</div>
		        </li>
	  				<li class = "nav-item">
		          <form method = "GET" action = "$_SERVER[PHP_SELF]" >
		            <div class = "form-group"> $select_result </div>
		            <input type = "submit" id = "choose_lec" class = "submit-buttons hide-item" value = "Select Lecturer" name = "select" />
		            <!-- <input type = "submit" id = "del_lec" class = "submit-buttons" value = "Delete" name = "delete" /> -->
		          </form>
		        </li>
		        <li class = "nav-item">
		        	  <!-- the full url to get the instructor profile will be generated with js when the student selects a lecturer-->
		          <a  id = "profile-link" href = "/common/profile.php?profile&user_id=" class = "nav-link">
		          	<img id = "instructor-profile-pix" class = "px-1 img-fluid img-thumbnail" src = "/personal_data/default-pix.jpeg" alt = "profile image" />
		          </a>
		        </li>
		        <li class = "nav-item">
		          <a href = "/common/profile.php?profile&user_id=$owner_id" class = "nav-link">
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
		        <li class = "link-buttons nav-item">
		          <a href = "/students/coursemates.php?coursemates" class = "nav-link">Coursemates</a>
		        </li>
		        <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" id="courses-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		            Courses
		          </a>
		          <div class="dropdown-menu" aria-labelledby="courses-menu">
								<a href = "/students/courses.php?registered_courses" class = "nav-link">Registered Courses</a>
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
		        <li class = "link-buttons nav-item">
		          <a href = "/students/announcements.php?announcements" class = "nav-link">News</a>
		        </li>
		      </ul>
		    </div>
		  </nav>
		  <div class = "col-9 col-md-3 mr-auto">
		  	<form id = "search_lec_form" class = "form-inline" method = "GET" action = "/common/search_names.php" >
		      <input type = "search" size = "22" class = "link_buttons form-control mr-sm-2 form-control-sm" id = "search-lecturers" name = "name_like" placeholder = "search your lecturers"/>
		    <input type = "submit" id = "search_lec" class = "btn btn-success my-2 my-sm-0 hide-item" name = "search_lecturers" value = "Search" />
		    </form>
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


