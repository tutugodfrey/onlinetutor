<?php 
//include required files
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];
$heading = "";
if($owner_id == ""){
$display = "<p>An error occurred while process your request please go back and try again</p>";
}	else	{



if(isset($_GET["results"])){
//get the course_code for which there are test result
$query_string = "select distinct course_id from score_board";

run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>No recorded scores</p>";
}	else	{
$course_ids = build_array($row_num2);
if($row_num2 == 1){
$course_ids = [$course_ids];
}

$courses = foreach_iterator($course_ids, "yes", $lecturer_db);	//get an array of array of course_ids and course_code
$select_result1 = select_option($courses, "course code", "course_id");

$query_string = "select distinct score_type from score_board";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>No Test submission have been made. May be you have not set any test</p>";
}	else	{
$score_types = build_array($row_num2);
if($row_num2 == 1){
$score_types = [$score_types];
}
$type = [];
foreach($score_types as $score_type){
$type[] = [$score_type];
}

//get the type from the score_board
$select_result2 = select_option($type, "type", "score_type");
}
$display = <<< block
<h1>Results</h1>
<form name = "result_form" method = "GET" action = "$_SERVER[PHP_SELF]" >
<p>select a course to view all test submitted</p>
$select_result1 <br />
$select_result2 <br />
<input type = "submit" class = "inner_btns" id = "submittedTest" name = "submitted_test" value = "Submitted Tests" />
<br />
<input type = "submit" class = "inner_btns" id = "totalScores" name = "total_score" value = "Accumulated Scores" />
</form>
block;
}
}		//end results


if(isset($_GET["submitted_test"])){
$course_id = $_GET["course_id"];
$score_type = strtolower($_GET["score_type"]);
if($course_id == "" || $score_type == "" ){
$display = "<p>Please select a course and type to view the student scores</p>";
}	else 	{

//use score_type to write the appropriate query to the database 
if($score_type == "test"){
$query_string = "select distinct test_id from score_board where course_id = \"$course_id\" and score_type = \"test\"";
}
if($score_type == "discussion"){
$query_string = "select distinct discussion_id from score_board where course_id = \"$course_id\" and score_type = \"discussion\"";
}
if($score_type == "exam"){
$query_string = "select distinct test_id from score_board where course_id = \"$course_id\" and score_type = \"exam\"";
}
run_query($query_string, $lecturer_db);
if($row_num2 ==  0){
$display = "<p>No $score_type has been submitted</p>";
}	else	{
$type_ids = build_array($row_num2);	//ids of the score_type
if($row_num2 == 1){
$type_ids = [$type_ids];
}
$fields = array ();
foreach($type_ids as $type_id){
$fields[] = $score_type.$type_id;
}		//end foreach
sort($fields);
array_unshift($fields, "names");
array_push($fields, "total", "average");
$scores_info_collector = array ();
//getting all students who have submited a test/discussion/exam
if($score_type == "test"){
$query_string = "select distinct student_id from score_board where course_id = \"$course_id\" and score_type = \"test\"";
}
if($score_type == "discussion"){
$query_string = "select distinct student_id from score_board where course_id = \"$course_id\" and score_type = \"discussion\"";
}
if($score_type == "exam"){
$query_string = "select distinct student_id from score_board where course_id = \"$course_id\" and score_type = \"exam\"";
}
run_query($query_string, $lecturer_db);
if($row_num2 == 0 ){
$display = "<p>student ids could not be fetched</p>";
}	else	{
$student_ids = build_array($row_num2);
if($row_num2 == 1){
$student_ids = [$student_ids];
}
foreach($student_ids as $student_id){
$student_row = [];
for($i = 0; $i < sizeof($fields); $i++){
$student_row[] = "";		//make the array equal to the length of fields
}
$names = get_names($student_id);
$student_row[0] = $names;
if($score_type == "test"){
$query_string = "select test_id, score from score_board where course_id = \"$course_id\" and score_type = \"test\" and student_id = \"$student_id\"";
}
if($score_type == "discussion"){
$query_string = "select discussion_id, score from score_board where course_id = \"$course_id\" and score_type = \"discussion\" and student_id = \"$student_id\"";
}
if($score_type == "exam"){
$query_string = "select test_id, score from score_board where course_id = \"$course_id\" and score_type = \"exam\" and student_id = \"$student_id\"";
}
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>submitted scores could not be fetched</p>";
}	else	{
$scores = build_array($row_num2);
if($row_num2 == 1){
$scores = [$scores];
}
foreach($scores as $score){
$type_id = $score[0];		//get the id foreach score record
$type_no = $score_type.$type_id;
	//test the position of this id in the fields array
$pos_in_fields = array_search($type_no, $fields);
$student_row[$pos_in_fields] = $score[1];
}		//end inner foreach
}	//end query tests

if($score_type == "test"){
$query_string = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and score_type = \"test\" and student_id = \"$student_id\"";
}
if($score_type == "discussion"){
$query_string = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and score_type = \"discussion\" and student_id = \"$student_id\"";
}
if($score_type == "exam"){
$query_string = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and score_type = \"exam\" and student_id = \"$student_id\"";
}
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$avg_score = "";
}	else {
$avg_score = build_array($row_num2);
$string_pos = array_search("total", $fields);
$student_row[$string_pos] = $avg_score[0];
$string_pos = array_search("average", $fields);
$student_row[$string_pos] = $avg_score[1];
}
$score_info_collector[] = $student_row;
}		//end foreach

}	//end student_ids querys

array_unshift($score_info_collector, $fields);
$table = mytable($score_info_collector, "yes", "yes");
$score_type = ucwords($score_type);
$course_code = get_course_code($lecturer_db, $course_id);
$display = <<<block
<h1>student scores for $course_code $score_type</h1>
$table
block;
}
}

} 		//end submitted_test




if(isset($_GET["total_score"])){
$course_id = $_GET["course_id"];
if($course_id == ""){
$display  = "<p>Please select a course to see the accumulated scores</p>";

}	else	{
$query_strings = array (
"test" => "select distinct test_id from score_board where course_id = \"$course_id\" and score_type = \"test\"",
"discussion" => "select distinct discussion_id from score_board where course_id = \"$course_id\" and score_type = \"discussion\"",
"exam" => "select distinct test_id from score_board where course_id = \"$course_id\" and score_type = \"exam\""
);
$fields = array ();
foreach($query_strings as $type => $query_string){
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
echo "no row return"; 
}	else	{
$type_ids = build_array($row_num2);
if($row_num2 == 1){
$type_ids = [$type_ids];
}
foreach($type_ids as $type_id){
$fields[] = $type.$type_id;
}	//end foeach
}
}		//end foreach
sort($fields);
array_unshift($fields, "names");
array_push($fields, "total", "average");
$scores_collector = array ();
$query_string = "select distinct student_id from score_board where course_id = \"$course_id\"";	//all student who made submission for this course
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>No scores found in the record</p>";
}	else	{
$student_ids = build_array($row_num2);
if($row_num2 == 1 ){
$student_ids = [$student_ids];
}
foreach($student_ids as $student_id){
$student_row = array ();
for($i = 0; $i < sizeof($fields); $i++){
$student_row[] = "";
}
$names = get_names($student_id);
$student_row[0] = $names;
$query_string = "select score_type, test_id, discussion_id, score from score_board where course_id = \"$course_id\" and student_id = \"$student_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "no scores";
}	else	{
$score_records = build_array($row_num2);
if($row_num2 == 1){
$score_records = [$score_records];
}

foreach($score_records as $score_record){
$score_type = strtolower($score_record[0]);
if($score_type === "discussion"){
$key = "discussion$score_record[2]";
$key_pos = array_search($key, $fields);
$student_row[$key_pos] = $score_record[3];
}
if($score_type === "test"){
$key = "test$score_record[1]";
$key_pos = array_search($key, $fields);
$student_row[$key_pos] = $score_record[3];
}
if($score_type === "exam"){
$key = "exam$score_record[2]";
$key_pos = array_search($key, $fields);
$student_row[$key_pos] = $score_record[3];
}
}	//end inner foreach

}	// else for query tester
//get total and avarage scores for each student
$query_string = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and student_id = \"$student_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0 ){
$avg_scores = "no found";
}	else	{
$avg_scores = build_array($row_num2);
$key_pos = array_search("total", $fields);
$student_row[$key_pos] = $avg_scores[0];
$key_pos = array_search("average", $fields);
$student_row[$key_pos] = $avg_scores[1];
}	//else for query tester

$scores_collector[] = $student_row;
}	//end foreach
array_unshift($scores_collector, $fields);
$table = mytable($scores_collector, "yes", "yes");
$course_code = ucwords(get_course_code($lecturer_db, $course_id));
$display = <<<block
<h1>Student accumulated scores for $course_code</h1>
$table
block;
}
}
}		//end total_scores




}		//end verify $L_id
}	else {			
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}

?>

<?php echo $heading; ?>
<?php echo $display; ?>
