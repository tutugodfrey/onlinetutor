<?php
//include db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];

$heading = ""; $display = "";
if(isset($_GET["save_courses"]) || isset($_POST["edit_course"])){
	$course_title = "<label for = \"course_title\">Course Title</label>";
	$course_description = "<label for = \"course_description\">Course Description</label><br />";
	$course_unit = "<label for = \"course_unit\" >Course Unit</label>";

	if(isset($_POST["edit_course"])){
		if(empty($_POST["course_id"])){
			$display = "<p>Please select a course to edit</p>";
			$course_title = ""; $course_description = ""; $course_unit; $course_unit = ""; $course_code = ""; $action = ""; $view_courses = "";
		}	else 	{
			$course_id = $_POST["course_id"][0];
			$course_detail = get_course_code($course_id, 3, $lecturer_db);
			if(empty($course_detail)){
				$diplay = "<p>The course you want to edit does not exist</p>";
			}	else	{
				$course_code = get_course_code($course_id, 1, $lecturer_db);
				$heading = "<h1>Your are editing $course_code</h1>";
				$course_code = "<input type = \"hidden\" name = \"course_code\" value = \"$course_id\" />";
				$course_title .= "<input type = \"text\" name = \"course_title\" value = \"$course_detail[1]\" />";
				$course_description .= "<textarea name = \"course_description\" cols = \"50\" rows = \"7\">$course_detail[2]</textarea>";
				$course_unit .= "<input type = \"number\" name = \"course_unit\"  value = $course_detail[3] />";
				$action = "<input type = \"submit\" class = \"btn btn-success\" id = \"updateCourse\" name = \"update_course\" value = \"Update\" />";
				$view_courses = "<a href = \"$_SERVER[PHP_SELF]?&view_courses=yes\" id = \"viewCourses\" class = \"btn btn-primary\">View courses</a>";
			}
		}
	}

	if(isset($_GET["save_courses"])){
		$heading = "<h1>Add a Course to Save</h1>";
		$course_code = "<label for = \"course_code\">Course Code</label><input type = \"text\" class = \"form-control requiredFields\" name = \"course_code\" />";
		$course_title .= "<input type = \"text\" class = \"form-control\" name = \"course_title\" />";
		$course_description .= "<textarea  name = \"course_description\" cols = \"50\" rows = \"7\"></textarea>";
		$course_unit .= "<input type = \"number\" class = \"form-control requiredFields\" name = \"course_unit\" />";
		$action = "<input type = \"submit\" class = \"btn btn-success\" id = \"save\" name = \"save_course\" value = \"SAVE\" />";
		$view_courses = "<a href = \"$_SERVER[PHP_SELF]?&view_courses=yes\" id = \"viewCourses\" class = \"btn btn-primary\" >View courses</a>";
	}
	 

	$display .= <<<end
	<form method = "POST" class = \"form-group\" name= "form1" action = "$_SERVER[PHP_SELF]" id = "my_form" >
	<p id = "validation-notice">Fields mark below are required</p>
	$course_code <br/>
	$course_title<br />
	$course_description <br />
	$course_unit<br />
	$action
	</form>
	$view_courses 
end;
}	


if(isset($_POST["save_course"]) || isset($_POST["update_course"])){
	if(empty($_POST["course_code"]) || empty($_POST["course_title"]) || empty($_POST["course_description"]) || empty($_POST["course_unit"])){
		$display = "<p>please enter valid values in the required fields</p>";
	}	else	{
		admin_connect();
		$course_code = mysqli_real_escape_string($mysqli, trim($_POST["course_code"]));
		$course_title = mysqli_real_escape_string($mysqli, trim($_POST["course_title"]));
		$course_description = mysqli_real_escape_string($mysqli, trim($_POST["course_description"]));
		$course_unit = mysqli_real_escape_string($mysqli, trim($_POST["course_unit"])); 
		if(isset($_POST["update_course"])){
			$query_string = "update courses set course_title = \"$course_title\", course_description = \"$course_description\", unit = \"$course_unit\" where course_id = \"$course_code\""; 	//$course_code is actually the course_id
			$course_code = get_course_code($course_code, 1, $lecturer_db);
			$success_string = "<p>You have successfully updated $course_code</p>";
			$failure_string = "<p>The course could not be added </p>";
		}	
		if(isset($_POST["save_course"])){
			$query_string = "select * from courses where course_code = \"$course_code\"";
			run_query($query_string, $lecturer_db);
			if($row_num2 == 1){
				$display = "<p>This Course has already been save</p><br/>";
				$display .="<a href = \"$_SERVER[PHP_SELF]?view_courses=yes\" id = \"viewCourses\" class = \"btn btn-primary\" >View courses</a>";
			}	else	{
				$query_string = "insert into courses values (null, \"$course_code\", \"$course_title\", \"$course_description\", \"$course_unit\")";
				$success_string = "<p>$course_code has been saved successfully</p>";
				$failure_string = "<p>The course could not be added </p>";
			}
		}
		run_query($query_string, $lecturer_db);
		if($row_num2 == 1){
			$display = $success_string;
		}	else {
			$display = $failure_string;
		}
		$display .= "<a href = \"$_SERVER[PHP_SELF]?view_courses=yes\" id = \"viewCourses\">View courses</a>";
	}
}


if(isset($_GET["view_courses"])){
	$query_string = "select * from courses";
	$fields = array ("course_id", "Course code", "Course Title", "Course description", "unit");
	$values = get_course_code("course_code", 1, $lecturer_db, $query_string);	//get the course details
	if(empty($values)){
		$display = "<p>You have not save any course yet<br />go to the <a href = \"$_SERVER[PHP_SELF]?save_courses=yes\">
				Save Courses</a> page and begin adding courses</p>";
	}	else {
		array_unshift($values, $fields);
		$table_values = mytable($values, "yes", "no");
		$heading = "<h1>Courses you are taking are </h1>";
		$display  = "<form method = \"POST\" action = \"$_SERVER[PHP_SELF]\" name = \"available_courses\" >";
		$display .= $table_values;
		$display .= "<input type = \"submit\" class = \"btn btn-success\" id = \"deleteCourse\" name = \"delete_course\" value = \"DELETE\" />";
		$display .= "<input type = \"submit\" class = \"btn btn-danger\" id = \"editCourse\" name = \"edit_course\" value = \"EDIT\" />";
		$display .= "</form>";
		$display .= "<p>You want to <a href = \"$_SERVER[PHP_SELF]?save_courses=yes\" id = \"saveCourse\" class = \"btn btn-primary\" >add a course</a></p>";
	}
}


if(isset($_POST["delete_course"])){
	if(empty($_POST["course_id"])){
		$display = "<p>please enter a valid value in the required fields</p>";
	}	else {
		$course_id = $_POST["course_id"][0];
		$display = "want to delete a course";
		//admin_connect();
		$query_string = "delete from courses where course_id = \"$course_id\"";
		run_query($query_string, $lecturer_db);
		if($row_num2 == 1){
			$display = "<p>The selected course has been deleted from the record</p>";
		}	else 	{
			$display = "<p>$course_code could not be deleted; the course does not exist in the record</p>";
		}
	}
}


}	else {			//user do not have an active session
echo "<p>You do not have an active user session. please go back and log in properly</p>";
}

?>



<?php echo $heading; ?>
<?php echo $display; ?>
