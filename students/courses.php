<?php

//include the db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lec_id = $_SESSION["lec_id"];
$heading = "";

if(isset($_GET["courses"])){
$display = "<h1>Want to register a course</h1>";
$query_string = "select course_id, course_code, course_title, unit from courses where lec_id = \"$lec_id\"";		
run_query($query_string);
$display = $row_num2;
if($row_num2 == 0){
	$display = "<p>No Course have been saved by the selected lecturer </p>";
}	else	{
	$course_details = build_array($row_num2);
	if($row_num2 == 1 ){
		$course_details = [$course_details];
	}

	$fields = array ("course_id", "Course code", "Course Title", "unit");
	array_unshift($course_details, $fields);
	$table_values = mytable($course_details, "yes", "no");
	$display = <<<block
	<form name = "courses_to_register" class = "form-group" method = "POST" action = "$_SERVER[PHP_SELF]">
	$table_values
	<label for = "course_status"> Course Status</label>
	<select class = "form-control" name = "course_status" >
	<option value ="course_status "selected" = "selected" value = "default">Select</option>
	<option value = "core">Core</option>
	<option value = "elective">Elective</option>
	</select>
	<br />
	<br />
	<input type = "submit" class = "btn btn-success" id = "registerCourse"  value = "Register" name = "register" />
	<input type = "submit" class = "btn btn-primary" id = "courseDiscription" name = "course_description" value = "Course Description" />
	</form>
block;
	}
}


if(isset($_POST["register"])){
	if((empty($_POST["course_id"])) || ($_POST["course_status"] == "selected")){
		$display = "<p>Please select a course from the list of courses to register.</p>";
	}	else	{
		$course_id = $_POST["course_id"][0];
		$course_status = $_POST["course_status"];
		$query_string = "select course_id from registered_courses where student_id = \"$owner_id\" and course_id = \"$course_id\" and lec_id = \"$lec_id\"";
		run_query($query_string);
		if($row_num2 > 1){
			$display  = "<p>You have already registered this course</p>";
		}	else{
			$query_string = "insert into registered_courses values(null, \"$lec_id\", \"$owner_id\", \"$course_id\", \"$course_status\")";
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<p>the course could not be registered </p>";
			}	else	{
				$display = "<br /><p>Course registration successfull</p>";
			}
		}
	}
}

if(isset($_GET["registered_courses"])){
	$query_string = "select course_id, course_status from registered_courses where student_id = \"$owner_id\" and lec_id = \"$lec_id\"";
	run_query($query_string);
	if($row_num2 == 0){
		$display = "<p>You have not registered any course with this lecturer</p>";
	}	else	{
		$course_ids = build_array($row_num2);
		if($row_num2 == 1){
			$course_ids = [$course_ids];
		}
		$all_courses = [];
		for($i = 0; $i < sizeof($course_ids); $i++ ){
			$query_string = "select course_id, course_code, course_title, unit from courses where course_id = \"".$course_ids[$i][0]."\" and lec_id = \"$lec_id\"";
			run_query($query_string);
			if($row_num2 == 0 ){
				$display = "<p>Course infomation could not be displayed</p>";
			}	else 	{
				$course_info = build_array($row_num2);
				$course_info[] = $course_ids[$i][1];			//push course_status in
				$all_courses[] = $course_info;
			}
		}		//end for block
		$fields = array ("course_id", "Course Code", "Course Title", "unit", "status");
		array_unshift($all_courses, $fields);
		$table_values = mytable($all_courses, "yes", "no");
		$display = <<<block
		<form name = "registered_courses" method = "POST" action = "$_SERVER[PHP_SELF]">
		$table_values
		<input type = "submit" class = "btn btn-success" id = "courseDiscription" name = "course_description" value = "Course Description" />
		<input type = "submit" class = "btn btn-danger" id = "removeCourse" value = "Remove" name = "remove_course" />
		</form>
block;
	}
}


if(isset($_POST["remove_course"])){
	if(empty($_POST["course_id"])){
		$display = "<p>Please select a course to delete from the record</p>";
	}	else	{
		$course_id = $_POST["course_id"][0];
		$query_string = "delete from registered_courses where course_id = \"$course_id\" and student_id = \"$owner_id\" and lec_id = \"$lec_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>The course could not be remove maybe it does not exist in the record</p>";
		}	else	{
			$display = "<p>The course have been remove from your list of registered courses</p>";
		}
	}
}

if(isset($_POST["course_description"])) {
	if(empty($_POST["course_id"])){
		$display = "<p>Please select a course with the checkbox to view course discription</p>";
	}	else 	{
		$course_id = $_POST["course_id"][0];
		$course_info = get_course_code($course_id, $lec_id, 3);
		if($course_info === "" ){
			$display = "<p>Course description for the selected course could not be fetched</p>";
		}	else 	{
			$fields = array ("course_id", "course code", "course title", "course description", "unit");
			array_unshift($course_info, $fields);
			$table_values = mytable($course_info, "yes", "no");
			$display = <<<block
			<form name = "course_description" method = "POST" action = "$_SERVER[PHP_SELF]" >
			$table_values
			</form>
block;
		}
	}
}



}	else {
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}

?>


<?php echo $heading; ?>
<?php echo $display; ?>
