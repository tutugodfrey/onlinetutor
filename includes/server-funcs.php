<?php
/////////////////////////////////////////////////////////////////////////////////////
//function for selecting course_code and/or course_id from db
// function get_course_code($course_id, $query_to_run = 1, $lecturer_db, $query_string = "default"){
function get_course_code($course_id, $lec_id,  $query_to_run = 1, $query_string = "default"){
	global $row_num2;
	if($query_string === "default"){
		$query_string = "select course_code from courses where course_id = \"$course_id\" and lec_id = \"$lec_id\"";
		if($query_to_run === 2){
			$query_string = "select course_id, course_code from courses where course_id = \"$course_id\" and lec_id = \"$lec_id\"";
		}  else if($query_to_run === 3){
			$query_string = "select course_id, course_code, course_title, course_description, unit from courses where course_id = \"$course_id\" and lec_id = \"$lec_id\"";
		}
	}	
	run_query($query_string);
	if($row_num2 == 0){
		return "";
		//<p>course code not available</p>
	}	else	{
		$course = build_array($row_num2);
		if($row_num2 === 1) {
			$course = [$course];
		}
		return $course;
	}
}		//end get_course_code

////////////////////////////////////////////////////////////////////////////////
//function to select the course_ids of students from registered_courses
function registered_course_ids($S_id, $lec_id){
	global $row_num2;
	$query_string = "select course_id from registered_courses where student_id = \"$S_id\" and lec_id = \"$lec_id\"";
	run_query($query_string);
	if($row_num2 == 0){
		return	[];		//return an empty array
		//"<p>You have not registered any course with this lecturer</p>"
	}	else	{
		$course_ids = build_array($row_num2);
		if($row_num2 == 1){
			$course_ids = [$course_ids];
		}
		return $course_ids;
	}
}		//end student_courses

////////////////////////////////////////////////////
//function to get the names of student from registration table
function get_names($user_id){
	global $row_num2;
	$query_string = "select lastname, firstname from registered_users where id = \"$user_id\"";
	run_query($query_string);
	if($row_num2 == 0){
		$names = "";
	}	else	{
		$names = build_array($row_num2);
		$names = $names[0]." ".$names[1];
	}
	return ucwords($names);
}

///////////////////////////////////////////////////////////////
//function to get video
function get_videos($course_id, $lec_id, $query_to_run = 1 ) {
	global $row_num2;
	if($query_to_run == 1) {
		$query_string = "select id, video_url, video_name, video_caption, course_id from videos where course_id =\"$course_id\" and lec_id = \"$lec_id\"";
	}
if($query_to_run === 2) {
		$query_string = "select id, video_url, video_name, video_caption, course_id from videos and lec_id = \"$lec_id\"";
	}
	run_query($query_string);
	if($row_num2 == 0 ){
	return;
	}	else 	{
	$video_details = build_array($row_num2);
	if($row_num2 == 1){
	$video_details = [$video_details];
	}
	$videos = [];
	foreach($video_details as $video){
	$video[4] = get_course_code($video[4], $lec_id, 1);
	$videos[] = $video;
	}
	return $videos;
	}
}

?>