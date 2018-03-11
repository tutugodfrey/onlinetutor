<?php
//include db_connect function
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

		if(isset($_GET["tests"])){
			$heading = "<h1>Test</h1>";
			$query_string = "select course_id from registered_courses where student_id = \"$owner_id\" and lec_id = \"$lec_id\"";
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<p>You have not registered any course with this lecturer</p>";
			}	else	{
				$course_ids = build_array($row_num2);
				if($row_num2 == 1) {
					$course_ids = [$course_ids];
				}
				// $course_details = [];
				$course_details = foreach_iterator2("get_course_code", $course_ids, $lec_id, 2);
				$select_result = select_option($course_details, "course code", "course_id",  "form-control");
				$display = <<<block
				<p>Please select a course to see which tests are avialable</p>
				<form name = "testForm" method = "GET" action = "$_SERVER[PHP_SELF]" >
				$select_result
				<input type = "submit" class = "btn btn-success"  id = "viewTest" name = "view_tests" value = "View Tests/Exams" />
				</form>
block;
			}
		}

		if(isset($_GET["view_tests"])){
			$course_id = $_GET["course_id"];
			if( $course_id == ""){
				$display = "<p>please select a course to view tests or exams.</p>";
			}	else	{
				$course_code = get_course_code($course_id, $lec_id, 1);
				$query_string = "select test_id, date_format(deadline, \"%D %M %Y\"), test_type from test where course_id = \"$course_id\" and test_status = \"opened\" and lec_id = \"$lec_id\"";
				run_query($query_string);
				if($row_num2 == 0){
					$display = "<p>No test have been set for $course_code[0]</p>";
				}	else	{
					$tests = build_array($row_num2);
					if($row_num2 == 1){
						$tests = [$tests];
					}
					$all_tests = [];
					foreach($tests as $test) {
						if(strtolower($test[2]) == "exam"){
							$test[0] = $test[0]." exam";
						}
						if(strtolower($test[2]) == "test"){
							$test[0] = $test[0]." test";
						}
						$all_tests[] = $test;
					}
					$fields = array ("test_id", "deadline", "test status");
					array_unshift($all_tests, $fields);
					$table_values = mytable($all_tests, "yes", "no");
					$heading = "<h1>Avialable Tests/Exams for $course_code[0]</h1>";
					$display = <<<block
					<p>Select a test to view test specification and start test</p>
					<form  name = "testForm" method = "GET" action = "$_SERVER[PHP_SELF]" >
					<input type = "hidden" name = "course_id" value = "$course_id" />
					$table_values
					<input type = "submit" class = "btn btn-success" id = "startTest" name = "test_spec"  value = "Test Details" />
					</form>
block;
				}
			}
		}

		if(isset($_GET["test_spec"])){
			$course_id = $_GET["course_id"];
			if($course_id == ""){
				$display = "<p>An error occured. Please go back and try again</p>";
			}	else	{
				if(empty($_GET["test_id"])){
					$display = "<p>Please select a test to start test</p>";
				}	else	{
					$course_code = get_course_code($course_id, 1); 		//get the course_code
					$test_ind = $_GET["test_id"][0];	//a string that contain the id and the type test/exam
					$test_ind = explode(" ", $test_ind);
					$test_id = $test_ind[0];
					$test_type = $test_ind[1];
					$query_string = "select date_format(duration, \"%i\"), date_format(deadline, \"%H:%i %p. %W, %D of %M, %Y\"), mark, no_of_questions from test where test_id = \"$test_id\" and course_id = \"$course_id\" and test_type = \"$test_type\" and lec_id = \"$lec_id\"";
					run_query($query_string);
					if($row_num2 == 0 ){
						$display = "<p>Test specification could not be retrieved</p>";
					}	else	{
						$test_spec = build_array($row_num2);
						//use result to get the values
						$duration = $test_spec["date_format(duration, \"%i\")"];
						$deadline = $test_spec["date_format(deadline, \"%H:%i %p. %W, %D of %M, %Y\")"];
						$mark = $test_spec["mark"];
						$no_of_questions = $test_spec["no_of_questions"];
						$question = "questions";
						if($no_of_questions == 1){
							$question = "question";
						}
						$heading = "<h1>Test Taker</h1>";
						$instructions = "
						<pre><h2>Instructions</h2><p>
						This is $test_type $test_id for $course_code[0]. This $test_type will
						last for $duration minutes and your are required to answer
						$no_of_questions $question. Each question carry equal mark Please
						ensure you take the test on or before $deadline.</p>
						</pre>";
						$display = <<<block
						$instructions
						<form name = "test_specification" method = "POST" action = "$_SERVER[PHP_SELF]" >
						<input type = "hidden" name = "instructions" value = "$instructions" />
						<input type = "hidden" name = "test_id"  value = "$test_id" />
						<input type = "hidden" name = "test_type"  value = "$test_type" />
						<input type = "hidden" name = "course_id"  value = "$course_id" />
						<input type = "hidden" name = "duration"  value = "$duration" />
						<input type = "hidden" name = "deadline" value = "$deadline" />
						<input type = "hidden" name = "mark"  value = "$mark" />
						<input type = "hidden" name = "no_of_questions"  value = "$no_of_questions" />
						<input type = "submit" class = "btn btn-success" id = "startTest" name = "start_test" value = "Start Test" />
						</form>
block;
					}
				}
			}
		}

		if(isset($_POST["start_test"])){
			$test_id = $_POST["test_id"];
			$test_type = $_POST["test_type"];
			$course_id = $_POST["course_id"];
			$duration = $_POST["duration"];
			$deadline = $_POST["deadline"];
			$mark = $_POST["mark"];
			$no_of_questions = $_POST["no_of_questions"];
			$instructions = $_POST["instructions"];
			if($instructions == "" || $test_id == "" || $course_id == "" || $duration == "" ||$deadline == ""
					|| $mark == "" || $no_of_questions == ""){
				$display = "<p>An error error has occured. please go back and try again</p>";
			}	else	{
				$course_code = get_course_code($course_id, 1);
				if($test_type == "exam"){		//all question for this course_id
					$query_string = "select questions, option_A, option_B, option_C, option_D, correct_option from
						questions where course_id = \"$course_id\" and lec_id = \"$lec_id\"";
				}	else if ($test_type == "test") 	{
					$query_string = "select questions, option_A, option_B, option_C, option_D, correct_option from
						questions where test_id = \"$test_id\" and course_id = \"$course_id\" and lec_id = \"$lec_id\"";
				}
				run_query($query_string);
				if($row_num2 == 0){
					$display = "<p>Questions for $course_code $test_type $test_id could not be fetch</p>";
				}	else	{
					$questions = build_array($row_num2);
					if($row_num2 == 1){
						$questions = [$questions];
					}
					shuffle($questions);
					$correct_options = array ();		//store correct answers
					$question_html = "";	//initialize the chats
					$display = "";
					for($l = 0; $l < $no_of_questions;  $l++){
						$no = $l+1;
						$question_html .= "<pre>".
						"<p class = \"questions\"> $no:". $questions[$l][0]."<br /></p>".
						"<input type = \"radio\" name = \"ans_$no\" id = \"ans1_$no\" class = \"testradio\" value = \"A\" />A  ". "<label for = \"ans1_$no\">" . $questions[$l][1] ."</label><br />".
						"<input type = \"radio\" name = \"ans_$no\" id = \"ans2_$no\" class = \"testradio\" value = \"B\" />B  ". "<label for = \"ans2_$no\">" . $questions[$l][2] ."</label><br />".
						"<input type = \"radio\" name = \"ans_$no\" id = \"ans3_$no\" class = \"testradio\" value = \"C\" />C  ". "<label for = \"ans3_$no\">" . $questions[$l][3] ."</label><br />".
						"<input type = \"radio\" name = \"ans_$no\" id = \"ans4_$no\" class = \"testradio\" value = \"D\" />D  ". "<label for = \"ans4_$no\">" . $questions[$l][4] ."</label><br />".
						"</pre>";
						//store the correct options in an array
						$correct_options[] = $questions[$l][5];
					}		//end for
					$serialized_options = serialize($correct_options);    //so you can send it through form
					$heading = "<h1>$course_code[0] $test_type $test_id</h1>";
					$display = <<<block
					<form name = "test_specification" method = "POST" action = "$_SERVER[PHP_SELF]" >
					<p id = "durat">Duration : $duration mins, Time left: </p><p id = "time_left"> </p>
					<input type = "hidden" name = "serialized_options" value = $serialized_options />
					<input type = "hidden" name = "course_id" value = "$course_id" />
					<input type = "hidden" name = "no_of_questions" value = "$no_of_questions" />
					<input type = "hidden" name = "mark" value = "$mark" />
					<input type = "hidden" id = "test_duration" name = "duration"  value = "$duration" />
					<input type = "hidden" name = "test_id" value = "$test_id" />
					<input type = "hidden" name = "test_type" value = "$test_type" />
					$question_html
					<input type = "submit" class = "btn  btn-success" id = "submitTest" name = "submit_test" value = "Submit" />
					</form>
block;
				}
			}
		}	//end start_test

		if(isset($_POST["submit_test"])){
			$serialized_options =  $_POST["serialized_options"];
			$course_id = $_POST["course_id"];
			$test_type = $_POST["test_type"];
			$test_id = $_POST["test_id"];
			$mark = $_POST["mark"];
			$no_of_questions = $_POST["no_of_questions"];
			if($test_id == "" || $course_id == "" || $mark == "" || $no_of_questions == ""){
				$display = "<p>the test could not be submitted right now please check your network connection and try again</p>";
			}	else	{
				$answers = unserialize($serialized_options);	//unserialized and get array of answers
				$score  = 0;
				for($i = 0; $i < ($no_of_questions); $i++){
					$ans = $i+1;		//adding 1 to $i mark the first questions who answer is at index 0 of the answers array
					$ans = "ans_$ans";
					if(isset($_POST["$ans"])){		//test each of the submitted answer against the answer array
					$answer = $_POST["$ans"];		//if student does not select option for any question the question is not tested
						if($answer == $answers[$i]){		//and so the no score is assign
							$score = $score + $mark;
						}
					}
				}		//end for
				$total_score = $score;
				$display = $total_score;
				$query_string = "select * from score_board where student_id = \"$owner_id\" and test_id = \"$test_id\" and course_id = \"$course_id\" and score_type = \"$test_type\" and lec_id = \"$lec_id\"";
				run_query($query_string);
				if($row_num2 == 1){
					$display = "<p>Weldone, the result has already been saved</p>";
				}	else		{
					$query_string = "insert into score_board(score_id, lec_id, course_id, student_id, test_id, score, score_type)
							 values(null, \"$lec_id\", \"$course_id\", \"$owner_id\",  \"$test_id\", \"$total_score\", \"$test_type\")";
					run_query( $query_string);
					if($row_num2 == 0 ){
						$display = "<p>Score could not be saved. please try and take the test again</p>";
					}	else	{
						//varied congratulatry messages
						$percentage_score = ($total_score/($no_of_questions*$mark) * 100);	//calculate the percentage of the score
						$display = "<p>You scored $percentage_score % of the total score</p>";
						if($percentage_score < 40){
							$display .= "<p>Oh no you didn't do well in this test. you scored $total_score in the test. Take your time to study
								You can do much better</p>";
						}else if($percentage_score == 50){
							$display .= "<p>Weldone you scored $total_score in the test. please study harder</p>";
						} else if($percentage_score > 50 && $percentage_score <= 80){
							$display .= "<p>Congratulations : you scored $total_score in the test. Your really tried in the test keep it up</p>";
						} else if($percentage_score > 80){
							$display .= "<p>Congratulations, you scored $total_score in the test. you did excelently well in this test.</p>";
						}
					}
				}
			}
		}		//end submit_test

	} else {
		$display = "Please Select a lecturer";
	}
	
}	else {
header("Location:/login.php");  		//user do not have an active session
exit();
}

?>

<?php echo $heading; ?>
<?php echo $display; ?>
