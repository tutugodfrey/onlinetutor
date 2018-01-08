<?php
//include db_connect function
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];

$heading = "";


if(isset($_POST["edit"]) || isset($_GET["set_questions"])){
$course_id = ""; $test_id = ""; $text_area = ""; $optionA = ""; $optionB = ""; $optionC = ""; $form_text = "";
$optionD = ""; $save_question = ""; $view_questions = ""; $update = ""; $option_legend = "";	$question_no = "";
$optionAA = ""; $optionBB = ""; $optionCC = ""; $optionDD = ""; $start_fieldset = "";   $end_fieldset = "";

$optionA = "<label for = \"options\">A</label><input type = \"checkbox\" class = \"correctOption\" name = \"correct[]\" value = \"A\"/>";
$optionB = "<label for = \"options\">B</label><input type = \"checkbox\" class = \"correctOption\"  name = \"correct[]\"  value = \"B\"/>";
$optionC = "<label for = \"options\">C</label><input type = \"checkbox\" class = \"correctOption\"  name = \"correct[]\"  value = \"C\"/>";
$optionD = "<label for = \"options\">D</label><input type = \"checkbox\" class = \"correctOption\"  name = \"correct[]\" value = \"D\" />";
$start_fieldset = "<fieldset name = \"options\">"; $end_fieldset = "</fieldset><br />";   $option_legend = "<legend>Options</legend>";


//get the course from the table of saved courses
$query_string = "select course_id, course_code from courses";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$form_text = "<p>You have not saved any course</p>";
} 	else	{
$courses = build_array($row_num2);
if($row_num2 == 1){
$courses = [$courses];
}
$course_code = select_option($courses, "course code", "course_id");


if(isset($_GET["set_questions"])){
$heading = "<h1>SET QUESTION</h1>";
$optionA .= "<input type = \"text\" id = \"optA\" name = \"optionA\" value = \"$optionAA\"/><br />";
$optionB .= "<input type = \"text\" id = \"optB\" name = \"optionB\" value = \"$optionBB\"/><br />";
$optionC .= "<input type = \"text\" id = \"optC\" name = \"optionC\" value = \"$optionCC\"/><br />";
$optionD .= "<input type = \"text\" id = \"optD\" name = \"optionD\" value = \"$optionDD\" />";
$form_text = "<h3>Use the form below to set the questions</h3>";
$test_id = "<br /><label for = \"test_id\" >Test No</label><input type = \"text\"  id = \"testId\" name = \"test_id\" value = \"$test_id\" /><br />";
$text_area = "<br /><label for = \"question\">Question</label><br /><textarea name = \"question\" rows = \"7\" cols = \"50\"></textarea><br />";
$save_question = "<input type = \"submit\" class = \"inner_btns\" id = \"saveQuestion\" name = \"save_question\" value  = \"SAVE\" />";
$view_questions = "<input type = \"submit\" class = \"inner_btns\" id = \"viewQuestions\" name = \"view_questions\" value = \"View Questions\" />";
}
}		//end set_questions

if(isset($_POST["edit"])){
if(empty($_POST["question_id"])){
$form_text = "<p>Please use the checkbox to select a question to edit</p>";
}	else	{
$question_id = trim($_POST["question_id"][0]);
$query_string = "select * from questions  where question_id = \"$question_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>An error occur we could not fetch the question right now</p>";
}	else	{
$result = build_array($row_num2);


$course_id = $result["1"];
$correct_option  = $result["8"];		//unused

$test_id = "<label for = \"test_id\" >Test No</label><input type = \"text\" id = \"testId\" name = \"test_id\" value = \"".$result["2"]."\" /><br />";
$text_area = "<label for = \"question\">Question</label><br /><textarea name = \"question\" rows = \"7\" cols = \"50\">".$result['3']."</textarea><br/>";
$form_text = "<h3>Edit the questions as required to update it</h3>";
$question_no .= "<input type = \"hidden\" name = \"question_id\" value = \"".$result['0']."\"/><br />";
$optionA .= "<input type = \"text\" id = \"optA\" name = \"optionA\" value = \"".$result['4']."\"/><br />";
$optionB .= "<input type = \"text\" id = \"optB\" name = \"optionB\" value = \"".$result['5']."\"/><br />";
$optionC .= "<input type = \"text\" id = \"optC\" name = \"optionC\" value = \"".$result['6']."\"/><br />";
$optionD .= "<input type = \"text\" id = \"optD\" name = \"optionD\" value = \"".$result['7']."\" />";
$update = "<input type = \"submit\" class = \"inner_btns\" id = \"updateQuestion\" name = \"update_question\" value = \"Update Question\" />";

}
}
}		//end edit 

$display = <<<end
<form name = "setQuestion" method = "POST" action = "$_SERVER[PHP_SELF]" />
$form_text 
$course_code
$test_id
$question_no
$text_area 
$start_fieldset
$option_legend
$optionA
$optionB 
$optionC
$optionD
$end_fieldset
$save_question 
$view_questions 
$update
</form>
end;

}


if(isset($_POST["save_question"]) || isset($_POST["update_question"])){
$course_id = trim($_POST["course_id"]);
$test_id = trim($_POST["test_id"]);
admin_connect();
$question = mysqli_real_escape_string($mysqli, trim($_POST["question"]));
$optionA = mysqli_real_escape_string($mysqli, trim($_POST["optionA"]));
$optionB = mysqli_real_escape_string($mysqli, trim($_POST["optionB"]));
$optionC = mysqli_real_escape_string($mysqli, trim($_POST["optionC"]));
$optionD = mysqli_real_escape_string($mysqli, trim($_POST["optionD"]));
$correct_option = $_POST["correct"][0];
if($optionA == "" || $optionB == "" || $optionC == "" || $optionD == ""){
$display = "<p>Please provide options for the question</p>";
}	else {

if(isset($_POST["save_question"])){
//before saving the question test that it does not already exist
$query_string = "select * from questions where test_id = \"$test_id\" and questions = \"$question\" and course_id = \"$course_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 1){
$display = "<p>This question already exist</p>";	//display this only if the want to re-save the question 
}	else 	{
$query_string = "insert into questions values (null, \"$course_id\", \"$test_id\", \"$question\", \"$optionA\", \"$optionB\", \"$optionC\", \"$optionD\", \"$correct_option\")";

$failure_string = "<p>The question could not be saved now, please try again later</p>";
}
} //end save question

if(isset($_POST["update_question"])){
$question_id = $_POST["question_id"];
$query_string = "update questions set question_id = '".$question_id. "', course_id = '".$course_id."', test_id= '".$test_id.
		"', questions = '".$question."', option_a = '".$optionA."', option_b =  '".$optionB."', option_c = '".$optionC.
		"', option_d =  '".$optionD."', correct_option = '".$correct_option."' where question_id = '".$question_id."'";
$failure_string = "<p>The question could not be updated now, please try again later</p>";

}	//end update question
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = $failure_string;
}	else	{
$display = "<p>the question has been saved</p>";
} 
}
}


if(isset($_POST["view_questions"])){
$course_id = trim($_POST["course_id"]);
if($course_id == ""){
$display = "<p>Please select a course code to see questions you have saved</p>";
} 	else		{
$query_string = "select course_code from courses where course_id = \"$course_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0 ){
$display = "<p>Course code could not be fetched</p>";
}	else 	{
$course_code = build_array($row_num2);
}
$query_string = "select question_id, test_id, questions, option_a, option_b, option_c, option_d, correct_option from questions where course_id = \"$course_id\"";

run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>You have not saved any question for this course</p>";
}	else 	{
$questions = build_array($row_num2);
if($row_num2 == 1){
$questions = [$questions];
}

$fields  = array ("question_id", "test No", "questions", "optionA", "optionB", "optionC", "optionD", "correct_option");
array_unshift($questions, $fields);
$table_values = mytable($questions, "yes", "no");
$heading = "<h1>Your save question for $course_code</h1>";

$display = <<<block
<pre><p>
You can edit or delete a question here. please use the checkbox
to select a question. Note that it will be more preferable to edit 
questions than to delete it. only delete a question when it is
absolutely necessary
</p></pre>
<form name = "testQuestion" method = "POST" action = "$_SERVER[PHP_SELF]" >
<!-- the checkbox contain the id of the question as question_no -->
$table_values
<input type = "submit" class = "inner_btns" id = "editQuestion" name = "edit" value = "Edit Question" />
<input type = "submit" class = "inner_btns" id = "deleteQuestion" name = "delete" value = "Delete" />
block;
}
}
}


if(isset($_POST["delete"])){
if(empty($_POST["question_id"])){
$display  = "<p>Please use the checkbox to select a question to delete</p>";
}	else	{
$question_id = trim($_POST["question_id"][0]);
$query_string = "delete from questions where question_id = \"$question_id\"";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$display = "<p>The action could not be completed. maybe the question does not exist</p>";
}	else {
$display = "<p>the question has been deleted</p>";
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
