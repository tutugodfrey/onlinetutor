<?php
//include db_connect function
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lec_id = $_SESSION["lec_id"];

$heading = "";

if(isset($_GET["coursemates"])){
	$query_string = "select distinct course_id from registered_courses where student_id = \"$owner_id\"";
	run_query($query_string);
	if($row_num2 == 0){
		$display = "<p>Your are not registered with this lecturer</p>";
	}	else	{
		$course_ids = build_array($row_num2);
		if($row_num2 == 1){
			$course_ids = [$course_ids];
		}
		$all_student_ids = [];
		foreach($course_ids as $course_id){
			$query_string = "select student_id from registered_courses where course_id = \"$course_id\" and student_id != \"$owner_id\"";
			run_query($query_string);
			if($row_num2 ==0 ){
			//$display = "<p>No other student have registered this course</p>";
			//no action needted
			}	else 	{
				$student_ids = build_array($row_num2);
				if($row_num2 == 1){
					$student_ids = [$student_ids];
				}
				$ids_size = sizeof($student_ids);
				for($i = 0; $i < $ids_size; $i++){	//run through student_ids if not present in all student_ids add it
					if (!in_array( $student_ids[$i], $all_student_ids)){
						$all_student_ids[] = $student_ids[$i];
					}
				}	//end for
			}
		}		//end foreach

		if(sizeof($all_student_ids) === 0){
			$display = "<p>No other student have registered the courses you offer</p>";
		}	else {
			$students_info = [];
			//get the names of and pix of this students
			for($i = 0; $i < sizeof($all_student_ids); $i++) {
				$query_string = "select id, lastname, firstname, picture from registered_users where id = \"$all_student_ids[$i]\"";
				run_query($query_string);	//use genereal database
				if($row_num2 == 0){
					//$display = "<p>Student information could not be fetched</p>";
					// do nothing
				}	else	{
					$student_info = build_array($row_num2);
					$students_info[] = $student_info;
				}
			}	//end for inside for

			if(sizeof($students_info) === 0){
				$display = "<p>Information about students offering the same courses as you could not be fetch</p>";
			}	else 	{
			$display = "";
				foreach($students_info as $student_info){
				$display .= <<<block
				<p id = "coursemate$student_info[0]">
				<img src = "$student_info[3]" alt = "image" />
				$student_info[1] $student_info[2]
				<a href = "/onlinetutor/common/profile.php?register_friend=yes&user_id=$student_info[0]">Add as Friend</a>
				<a href = "/onlinetutor/common/profile.php?profile=yes&user_id=$student_info[0]">View Profile</a>
				</p>
block;
				}		//end foreach inside for block
			}
		}
	}
}		//end coursemates


}	else {
header("Location:/onlinetutor/login.php");  		//user do not have an active session
exit();
}

?>


<?php echo $heading; ?>
<?php echo $display; ?>
