<?php
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];
$display = ""; $heading = "";

if(isset($_GET["discussions"])) {
	$heading = "";
	$heading = "<h1>Create a discussion</h1>";
	//get the course that the lecturer is taking
	$query_string = "select course_id, course_code from courses where lec_id = \"$owner_id\"";
	run_query($query_string);
	if($row_num2 == 0){
		$heading = "";
		$display = "<p>No Course have been saved in the record <a href = \"/instructors/save_course.php?save_courses=yes\" id = \"saveCourse\" class = \"btn btn-primary\" >add a course now!</a></p>";
	}	else	{
		$values = build_array($row_num2);
		if($row_num2 == 1){
			$values = [$values];
		}
		$select_result = select_option($values, "course code", "course_id", "form-control");
		//write the html to display the input fields for the discussion
		$display =  <<<block
		<form method = "POST" class = "form-group" name = "discussion_form" action = "$_SERVER[PHP_SELF]" >
			<p id = "validation-notice">Fields mark below are required</p>
				$select_result
			<br />
			<label for = "type_of_discussion">Discussion type</label>
			<select name = "type" class = "form-control">
				<option name = "select_type" selected = "selected" value = "default">Select type</option>
				<option value = "open">Open</option>
				<option value = "close">Close</option>
			</select><br />
			<label for = "discussion_topic" >Discussion Topic</label><br />
				<textarea class = "form-control requiredFields" name = "discussion_topic" rows = "7" cols = "50"></textarea><br />
			<input type = "submit" value = "Create" class = "btn btn-success" id = "createDiscussion" name = "create_discussion" />
			<a class = "btn btn-primary" href = "$_SERVER[PHP_SELF]?view_discussions"  id = "viewDiscussions" />View Discussions</a>
		</form>
block;
	}
}		//discussion



if(isset($_POST["create_discussion"])){
$course_id = trim($_POST["course_id"]);
admin_connect();
$discussion_topic = mysqli_real_escape_string($mysqli, trim($_POST["discussion_topic"]));
$type = $_POST["type"];
//check that the input field have been filled out
if($course_id == "" || $discussion_topic == ""){
$display = "<p>Please fill out the required fields to create a discussion topic <a href = \"$_SERVER[PHP_SELF]\" >&lt;&lt; Back</a></p>";
}	else 	{
//check that this discussion topic does not already exist
$query_string = "select discussion_topic from discussions where discussion_topic = \"$discussion_topic\" and type = \"$type\" and lec_id = \"$owner_id\"";
run_query($query_string);
if($row_num2 == 1){
$display = "<p>This discussion topic and type already exit</p>";
}	elseif($row_num2 == 0){ 			//create the discussion if it does not already exist
$heading = "<h1>Discussion Creation Result</h1>";
$query_string = "insert into discussions values (null, '".$owner_id."','".$course_id."', '".$discussion_topic."', now(), '".$type."')";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>The discussion could not be created</p>";
}	else	{
$display = "<p>the discussion have been posted</p>";
}
}
}
}		//end create discussion

if(isset($_GET["view_discussions"])){
	$query_string = "select distinct course_id from discussions where lec_id = \"$owner_id\"";
	run_query($query_string);
	if ($row_num2 == 0){
		$display = "<p>No discussion have been posted<p>";
	}	else	{
	$course_ids = build_array($row_num2);
	if($row_num2 == 1) {
		$course_ids = [$course_ids];
	}

	$course_codes = [];
	foreach($course_ids as $course_id) {
		$query_string = "select course_code from courses where course_id = \"$course_id\" and lec_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>The course code for created discussions could not be fetched</p>";
		}	else 	{
			$course_code = build_array($row_num2);		//course_code for this course_id
			$course_codes[] = $course_code;
		}
	}	//end foreach
	$size_of_course_codes = sizeof($course_codes);
	$size_of_course_ids = sizeof($course_ids);
	if ($size_of_course_codes == 0 ){
		$display = "<p>The course course for created discussions could not be fetched</p>";
	}	else if ($size_of_course_codes === $size_of_course_ids)	{
		$discussion_container = [];
		for($i = 0; $i < $size_of_course_ids; $i++){
			$query_string = "select discussion_id, discussion_topic, post_date, type from discussions where course_id = \"$course_ids[$i]\" and lec_id = \"$owner_id\"";
			run_query($query_string);
			if ($row_num2 == 0) {
				$display = "<p>Error fetching discusssion details</p>";
			}	else	{
				$discussions = build_array($row_num2);
				if($row_num2 == 1){
					$discussions = [$discussions];			//array of arrays
				}
				foreach($discussions as $discussion) {
					$discussion[] = $course_codes[$i];
					$discussion_container[] = $discussion;
				}
			}
		}
		$fields = array ( "discussion_id", "Discussion Topic", "Post Date", "Discussion Type", "Course Code");
		array_unshift($discussion_container, $fields);
		$table_values = mytable($discussion_container, "yes", "no");		//this id will be in the checkbox but will not be displayed
		$heading = "<h1>Discussions</h1>";
		$display = <<<block
		<form name = "discussionForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
		$table_values
		<input type = "submit" class = "btn btn-default" id = "viewPosts" name = "view_posts" value = "View Posts" />
		</form>
		<a id = "getDiscussionForm" href = "$_SERVER[PHP_SELF]?discussions">Create Discussion</a>
block;
		}
	}
}



if(isset($_POST["view_posts"])){
	$heading = ""; $dispaly = "";
	if(empty($_POST["discussion_id"][0])){
		$display = "<p>Please mark the checkbox to select which discussion you want to view</p>";
	}	else	{
		$discussion_id = $_POST["discussion_id"][0];
		$query_string = "select distinct student_id from posts where discussion_id = \"$discussion_id\" and lec_id = \"$owner_id\"";		//all student who has posted
		run_query($query_string);
		if($row_num2 == 0 ) {
			$display = "<p>No post have been submitted for this discusion</p>";
		}	else	{
		$student_ids= build_array($row_num2);
		if($row_num2 == 1){
			$student_ids = [$student_ids];		//make it an array
		}		
		$all_students	= [];
		foreach($student_ids as $student_id){
			$query_string = "select lastname, firstname from registered_users where id = \"$student_id\"";
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<p>An error occurred trying to fetch students</p>";
			}	else	{
				$student_names = build_array($row_num2);		//expected to be array of arrays
				$student = ucwords("$student_names[0] $student_names[1]");		//make the names a string
				$all_students[] = $student;
			}
		}	//end foreach
		$size_of_student_ids = sizeof($student_ids);
		$size_of_all_students = sizeof($all_students);
		if($size_of_all_students == 0){
			$display = "<p>Student informations could not be fetch</p>";
		}	else	{
			$discussion_info = [];
			for($i = 0; $i < $size_of_student_ids; $i++){
				//get all post by this student for this discussion
				$query_string = "select post_id, student_id, post_date, post_text, graded from posts where discussion_id = \"$discussion_id\" and lec_id = \"$owner_id\" and student_id = \"$student_ids[$i]\"";
				run_query($query_string);
				if($row_num2 == 0 ){
					$display = "<p>An error while fetching discussion information</p>";
				}	else 	{
					$student_discuss = build_array($row_num2);
					if( $row_num2 == 1){
						$student_discuss = [$student_discuss];
					}
					foreach($student_discuss as $add_name){
						$add_name[1] = $all_students[$i];
						$discussion_info[] = $add_name;
					}
				}
			}		//end for
			//get information abt this discussion
			$query_string = "select course_id, discussion_topic from discussions where discussion_id = \"$discussion_id\" and lec_id = \"$owner_id\"";
			run_query($query_string);
			if($row_num2 == 0 ){
				$display = "<p>Discussion details could not be fetched</p>";
			}	else	{
				$discussion_detail = build_array($row_num2);
				//get the course code
				$query_string = "select course_code from courses where course_id = \"$discussion_detail[0]\" and lec_id = \"$owner_id\"";
				run_query($query_string);
				if($row_num2 == 0 ){
					$display = "<p>Course code for this discussion could not be fetched</p>";
				}	else 	{
					$course_code = build_array($row_num2);
				}
				$discussion_detail = "<h1>Posts for $discussion_detail[1] | Course:$course_code</h1>";
			}
				$fields = array ("post_id",  "Post By", "Post Date", "Post Text", "Graded");
				array_unshift($discussion_info, $fields);
				$table_values = mytable($discussion_info, "yes", "no", "yes");
				$display = <<<block
				$discussion_detail
				<form name = "discussionForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
				<p>Select student and grade their performance</p>
				<!-- in the table_values is contain the post_id as that is use to identify the person who make the post -->
				<input type = "hidden" name = "discussion_id" value = "$discussion_id" />
				$table_values 
				<input type = "submit" class = "btn btn-success" id = "gradeStudent" name = "grade_student" value = "Grade Student" />
				</form>
block;
			}
		}
	}
}		//end view_posts

if(isset($_POST["grade_student"])){
	if(empty($_POST["post_id"])){
		$display = "<p>Please use the checkbox to select the student to grade</p>";
	}	elseif(empty($_POST["grade"])){
		$display = "<p>Use the check box to grade student performance from 1 to 5</p>";
	}	else 	{
		//collect the rest of the data
		$post_id = trim($_POST["post_id"][0]);
		$grade = trim($_POST["grade"]);
		//verify the post exist
		$query_string = "select discussion_id, student_id, course_id, graded from posts where post_id = \"$post_id\" and lec_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>This post does not exist </p>";
		}	else	{
			//gather the return data
			$result = build_array($row_num2);
			$student_id = $result["student_id"];
			$discussion_id =  $result["discussion_id"];
			$course_id = $result["course_id"];
			$graded = strtolower($result["graded"]);
			if($graded == "no" )	{
				//insert the data to the score_board and update the post table
				$query_string = Array ("insert into score_board (score_id, lec_id, student_id, course_id, test_id, discussion_id, score, score_type) values (null, \"$owner_id\", \"$student_id\", \"$course_id\", null, \"$discussion_id\", \"$grade\", 'discussion')",
							"update posts set graded = \"YES\" where discussion_id = \"$discussion_id\" and post_id = \"$post_id\" and lec_id = \"$owner_id\"");
				run_query($query_string);
				if($row_num2 == 1 || $row_num3 == 1){
					$display = "<p id = \"graded\">The grade has been recorded</p>";
				}	else {
					$display = "<p>Student score could not be saved now please try again</p>";
				}
			}	else 	{
				$display  = "<p>This student has already been graded for this post</p>";
			}
		}
	}
}





}	else {		
header("Location:/common/login.php");  		//user do not have an active session
exit();
}
?>

<?php echo $heading; ?>
<?php echo $display; ?>


