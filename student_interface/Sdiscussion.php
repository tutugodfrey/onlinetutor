<?php
//include the common function
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];
$heading = "";

if(isset($_GET["Sdiscussion"])){
$course_ids = registered_course_ids($owner_id, $lecturer_db);
if(empty($course_ids)){
$display = "<p>You have not registered any course with this lecturer yet</p>";
}	else {
$courses = foreach_iterator2("get_course_code", $course_ids, 2, $lecturer_db);
if(empty($courses)){
$display  = "<p>Your registered courses could not be fetched<p>";
}	else	{
$select_result = select_option($courses, "course code", "course_id");
$display = <<<block
<h1>Discussions</h1>
<p>Select a course to see the topic for discussion</p>
<form name = "SdiscussionForm" method = "GET" action = "$_SERVER[PHP_SELF]">
<label for = "course codes">Your Registered Courses</label><br />
$select_result
<input type = "submit" class = "inner_btns" id = "viewDiscussions" name = "view_discussions" value = "View">
</form>
block;
}
}
}


if(isset($_GET["view_discussions"])){
$course_id = $_GET["course_id"];
$heading = "<h1>Topics of Discussion</h1>";
//get the topics for discussion
$query_string = "select discussion_id, course_id, discussion_topic, post_date, type from discussion where course_id = \"$course_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>No Discussion has been posted for this course</p>";
}	else	{
$discussions = build_array($row_num2);
if($row_num2 == 1) {
$discussions = [$discussions];
}

$all_discussions = [];
foreach($discussions as $discussion){
$query_string = "select course_code from courses where course_id = \"".$discussion[1]."\"";
run_query($query_string, $lecturer_db);

if($row_num2 == 0){
$display = "<p>Course code for posted discussion could note be fetch</p>";
}	else	{
$course_code = build_array($row_num2);
$discussion[1] = $course_code;
$all_discussions[] = $discussion;
}

}		//end for block

$fields = array ("discussion_id", "Course Code", "Discusion_topic", "Post Date", "discussion type");

array_unshift($all_discussions, $fields);
$table_values = mytable($all_discussions, "yes", "no");

$display = <<<block
<form name = "SdiscussionForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
$table_values
<label for = "reply">Reply to Post</label><br/>
<textarea name = "post_text" rows = "7" cols = "50" /></textarea><br />
<input type = "submit" class = "inner_btns" id = "respond" name = "respond" value = "POST" />
<input  type = "submit" class = "inner_btns" id = "viewPosts"  name = "view_post" value = "View Post" />
</form>
block;
}
}


if(isset($_POST["respond"])){
if(empty($_POST["discussion_id"])){
$display = "<p>Please use the checkbox to select a course.</p>";
}	else	{
$discussion_id = $_POST["discussion_id"][0];
admin_connect();
$post_text = mysqli_real_escape_string($mysqli, trim($_POST["post_text"]));
if($post_text == "" ){
$display = "<p>Please fill out the required fields to post the topic</p>";
}	else 	{
$query_string = "select course_id, type from discussion where discussion_id = \"$discussion_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>The course code and the discussion id does not match. Please check and try again</p>";
}	else 	{
$result = build_array($row_num2);
$course_id = $result["course_id"];
$type = $result["type"];
$query_string = "insert into post values (null, \"$owner_id\", \"$discussion_id\", \"$course_id\", now(), \"$post_text\", \"$type\", \"no\")";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>Your reply could not be posted</p>";
}	else {
$display = "<p>Your reply has been posted</p>";
}
}
}
}
}

if(isset($_POST["view_post"])){
if(empty($_POST["discussion_id"][0])){
$display = "<p>Please mark the checkbox to select which discussion you want to view</p>";
}	else	{
$discussion_id = $_POST["discussion_id"][0];
	//this will display open discussions so student can see the post of all other students for the open type
$query_string = "select post_id, student_id, post_date, post_text from post where discussion_id = \"$discussion_id\" and type = \"open\" or discussion_id = \"$discussion_id\" and type = \"close\" and student_id = \"$owner_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0 ){
$display = "<p>No post have been submitted for this topic</p>";
}	else{
$heading = "<h1>post for the topics are as display</h1>";
$discussion_posts = build_array($row_num2);
if($row_num2 == 1){
$discussion_posts = [$discussion_posts];
}
$all_posts = [];
foreach($discussion_posts as $discussion_post) {
$names = get_names($discussion_post[1]); 		// the student id
$discussion_post[1] = $names;
$all_posts[] = $discussion_post;
} 	//end foreach

$fields = array ("Post_id", "Post Owner", "Post date", "Post Text");
array_unshift($all_posts, $fields);
$table_values = mytable($all_posts, "yes", "no");
$display = <<<block
<form name = "SdiscussionForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
$table_values
<textarea name = "post_text" rows = "7" cols = "50" /></textarea><br />
<input type = "hidden" name = "discussion_id[]" value = "$discussion_id" />
<input type = "submit" class = "inner_btns" id = "replyToPost" name = "respond" value = "Reply to Post" />
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
