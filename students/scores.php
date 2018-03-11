<?php
//include files of functions
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

		if(isset($_GET["scores"])){
			$course_ids = registered_course_ids($owner_id, $lec_id);
			if($course_ids == ""){
				$display = "<p>You have not registered any course with this lecturer</p>";
			}	else 	{
				$courses = foreach_iterator2("get_course_code", $course_ids, $lec_id, 2);
				if(empty($courses)){
					$courses = ["courses not found"];
					$display = "<p>information about your registered courses could not be fetched</p>";
				}	else	{
					$select_result = select_option($courses, "course code", "course_id", "form-control");
					$display = <<<block
					<form name = "scoresForm" class = "form-group" method = "GET" action = "$_SERVER[PHP_SELF]" >
					<p>Select a course code to view your scores</p>
					$select_result
					<input type = "submit" class = "btn btn-success" id = "viewScores" name = "view_scores" value = "View Scores"/>
					</form>
block;
				}
			}
		}		//end get scores

		if(isset($_GET["view_scores"])){
			$course_id = $_GET["course_id"];
			if($course_id == ""){
				$display = "<p>Please select a course to view scores</p>";
			}	else	{
				$query_string = "select score_type, test_id, discussion_id, score from score_board where course_id = \"$course_id\" and student_id = \"$owner_id\" and lec_id = \"$lec_id\"";
				run_query($query_string);

				if($row_num2 == 0 ){
					$display = "<p>No score recorded for this course</p>";
				}	else	{
					$scores = build_array($row_num2);
					if($row_num2 == 1){
						$scores = [$scores];
					}
					$new_array = [];		//will be a 4 column array
					foreach($scores as $score){
						//test the score_type
						$inner_array = [];
						if(strtolower($score[0]) === "exam"){
							if(sizeof($new_array) !== 0 && $new_array[0][0] === 1){
								$new_array[0][3] = $score[3];
							}	else	{
								$new_array[0]	= ["", "", "", ""];	//put the first values in the array
								$new_array[0][0] = 1;
								$new_array[0][3] = $score[3];
							}
						}		//end exam condition


						if(strtolower($score[0]) === "test"){
							$id = $score[1] - 1;
							if(sizeof($new_array) !== 0){
								$size_of_inner = sizeof($new_array[$id]);
								if(!$new_array[$id]){
									$new_array[$id] = ["", "", "", ""];
									$new_array[$id][0] = $id + 1;
									$new_array[$id][1] = $score[3];
								}	else	{
									$new_array[$id][1] = $score[3];
								}
							}	else	{
								$new_array[$id] = ["", "", "", ""];
								$new_array[$id][0] = $id + 1;
								$new_array[$id][1] = $score[3];
							}
						}		//end test condition

						if(strtolower($score[0]) === "discussion"){
							$id = $score[2] - 1;
							if(sizeof($new_array) !== 0){
								//$size_of_inner = sizeof($new_array);
								if(!$new_array[$id]){
									$new_array[$id] = ["", "", "", ""];
									$new_array[$id][0] = $id + 1;
									$new_array[$id][2] = $score[3];
								}	else	{
									//$new_array[$id] = ["", "", "", ""];
									//$new_array[$id][0] = $id + 1;
									$new_array[$id][2] = $score[3];
								}
							}	else	{
								$new_array[$id] = ["", "", "", ""];
								$new_array[$id][0] = $id + 1;
								$new_array[$id][2] = $score[3];
							}
						}		//end discussion condition
					}		//end foreach
					$scores = $new_array;
					$average_scores = ["Average", "", "", ""];
					$total_scores = ["Total", "", "", ""];
					$query_string1 = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and student_id = \"$owner_id\" and score_type = \"test\" and lec_id = \"$lec_id\"";
					$query_string2 = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and student_id = \"$owner_id\" and score_type = \"discussion\" and lec_id = \"$lec_id\"";
					$query_string3 = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and student_id = \"$owner_id\" and score_type = \"exam\" and lec_id = \"$lec_id\"";
					run_query($query_string1);
					if($row_num2 == 0 ){
						$test_result =  "";
					}	else	{
						$test_result = build_array($row_num2);
						$average_scores[1] = $test_result[1];
						$total_scores[1] = $test_result[0];
					}

					run_query($query_string2);
					if($row_num2 == 0 ){
						$discussion_result =  "";
					}	else	{
						$discussion_result = build_array($row_num2);
						$average_scores[2] = $discussion_result[1];
						$total_scores[2] = $discussion_result[0];
					}

					run_query($query_string3, $lecturer_db);
					if($row_num2 == 0 ){
						$exam_result =  "";
					}	else	{
						$exam_result = build_array($row_num2);
						$average_scores[3] = $exam_result[1];
						$total_scores[3] = $exam_result[0];
					}

					$grand_total = ["Grand total", "", "", ""];
					$grand_average = ["Grand average", "", "", ""];
					//work on averages and total
					$query_string = "select sum(score), avg(score) from score_board where course_id = \"$course_id\" and student_id = \"$owner_id\" and lec_id = \"$lec_id\"";
					run_query($query_string);
					if($row_num2 == 0){
						$display = "<p>Your data could not be fetch now. please try again later</p>";
					}	else	{
						$totaling = build_array($row_num2);
						$grand_total[3] = $totaling[0];
						$grand_average[3] = $totaling[1];
					}

					$fields = array ("no", "tests", "discussions", "exam");
					array_unshift($scores, $fields);
					array_push($scores, $average_scores, $total_scores, $grand_total, $grand_average);
					$display = mytable($scores, "no", "yes");
				}
			}
		}		//end view_scores

	} else {
		$display = "Please Select a lecturer";
	}

}	else {
header("Location:/login.php");  		//user do not have an active session
exit();
}
?>

<?php echo $display; ?>
