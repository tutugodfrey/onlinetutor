<?php
//include required files
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
	$heading = "";
	if(isset($_SESSION["lec_id"])) {
		$lec_id = $_SESSION["lec_id"];

		if(isset($_GET["lecture_note"])){
			$course_ids = registered_course_ids($owner_id, $lec_id);		//get course_id of registered courses
			if($course_ids == ""){
				$display = "<p>You have not registered any course with this lecturer yet <a href = \"/students/courses.php?courses\" id = \"registerCourse\" class = \"btn btn-primary\" >Register a course now!</a></p>";
			}	else	{
				$courses = foreach_iterator2("get_course_code", $course_ids, $lec_id, 2);
				$heading = "<h1>Your registered Courses</h1>";
				$registered_courses = select_option($courses, "course code", "course_id", "form-control");
				$display = <<<block
				<form name = "lectureNoteForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
				<p>Select a course to see note</p>
				$registered_courses
				<input type = "submit" class = "btn btn-primary" id = "lectureNote" name = "view_lecture_note" value = "View Note" />
				</form>
block;
			}
		}		//end lecture_note

		if(isset($_POST["view_lecture_note"])){
			$course_id = $_POST["course_id"];	//view lecturer not available for this course
			if($course_id == ""){
				$display = "<p>Please select a course to see all available note for the course</p>";
			}	else	{
				$course_code = get_course_code( $course_id, $lec_id, 1)[0];
				if($course_code == ""){
					$display = "<p>course code not found</p>";
				}	else	{
					$query_string = "select note_id, title from notes where course_id = \"$course_id\" and user_id = \"$lec_id\"";
					run_query($query_string);
					if($row_num2 == 0){
						$display = "<p>No Note have been saved for this course</p>";
					}	else	{
						$lecture_note = build_array($row_num2);
						if($row_num2 == 1){
							$lecture_note = [$lecture_note];
						}
						$fields = array("note_id", "title");
						array_unshift($lecture_note, $fields);
						mytable($lecture_note, "yes", "no");
						$display = <<<block
						<h1>Lecture Note available for $course_code</h1>
						<p>Please use the checkbox to select a note to read
						<form name = "lectureNoteForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
						<input type = "hidden" name = "course_code" value = "$course_code" />
						<input type = "hidden" name = "course_id" value = "$course_id" />
						$table_values
						<input type = "submit" class = "btn btn-success" id = "readNote" name = "view_note" value = "Read" />
						</form>
block;
					}
				}
			}
		}	//end view_lecture_note

		if(isset($_POST["view_note"])){
			if(empty($_POST["note_id"])){
				$display = "<p>Please select a course to view </p>";
			}	else	{
				$note_id = trim($_POST["note_id"][0]);
				$course_id = $_POST["course_id"];
				$course_code = $_POST["course_code"];
				$query_string = "select title, note, date_format(note_date, \"Posted %D %M %Y\") from notes where note_id = \"$note_id\" and user_id = \"$lec_id\"";
				run_query($query_string);
				if($row_num2 == 0){
					$display = "<p>Your note could not be fetch now. please check your network connectivity and try again</p>";
				}	else 	{
					$note = build_array($row_num2);
					$post_text = trim($note["note"]);		//obtain the note incase user want to edit it
					$title = trim($note["title"]);
					$post_date = $note["date_format(note_date, \"Posted %D %M %Y\")"];

					$display = "
					<h1>$course_code: $title</h1>
					<div id = \"text_area\" >$post_text</div><br />
					<h3>Note posted on $post_date</h3>";
				}
			}
		}		//end view note

	} else {
		$display = "Please Select a lecturer";
	}

}	else {
header("Location:/common/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $heading; ?>
<?php echo $display; ?>
