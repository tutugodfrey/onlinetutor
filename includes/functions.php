<?php 
////////////////////////////////////////////////////
function run_query($query_string, $database = "lecturer_app"){
	global $mysqli;
	//declare global variables
	global $rows;
	global $fields_length; 
	global $run; 
	$choose_database = "use $database";		//select a database to use
	if(is_array($query_string)){
		array_unshift($query_string, $choose_database);
		$queries = $query_string;
	}	else	{
		$query_string = trim($query_string);
		$queries = array ($choose_database, $query_string);
	}

	$row = 0; 	//this will ignore the chose_database string
	admin_connect();	//connect to the database
	foreach($queries as $query){
	$action = explode(" ", $query);
	$action = strtolower($action[0]);
	$row++;		//increment row by 1
	$row_num = 'row_num'.$row;
	global $$row_num;		//read like $row_num1, $row_num2 ...
	$run = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

		switch($action){
			case "select": $$row_num = mysqli_num_rows($run); 
					 $fields_length = mysqli_num_fields($run);
					 //$table_fields = mysql_list_fields($run, $mysqli);
					 break;
			case "use": 	$$row_num = "success";		//this when choosing a databases
			case "update": $$row_num = mysqli_affected_rows($mysqli); break;
			case "delete": $$row_num= mysqli_affected_rows($mysqli); break;
			case "insert": $$row_num = mysqli_affected_rows($mysqli); break;
			default:	"no action";
		}
	}	

	if ($row_num === 'row_num2') {
		$rows = $$row_num;
	}
}



/////////////////////////////////////////////////////////
//function to build an array of the returned result from mysqli select query
function build_array($row_num){
	global $get_json;
	//set in run_query func
	global $run;
	global $fields_length;
	global $values;		//an array of arrays result type
	//result hold the an array of the values result from the query while row how the number of rows return
	//so you can know how many times to iterate
	$type =  gettype($run);
	// echo $run;
	// echo $type;
	if($type === "boolean") {
		$values = "The query is successful";
	}	else {
		if($row_num == 1 && $fields_length == 1){		//one rows one column values
			$result = mysqli_fetch_array($run);
			$values = $result[0];		//simple return the value as a single string
		}
		if($row_num == 1 && $fields_length > 1){		//one rows multiple column values
			$result = mysqli_fetch_array($run);
			$values = $result;		//return a one row array
		}
		if($row_num > 1 && $fields_length == 1){		//multi rows one column. return a straight array
			$values = [];
			while($result = mysqli_fetch_array($run)){
			array_push($values, $result[0]);
			}
		}
		if($row_num > 1 && $fields_length > 1){		//multiple rows multiple column  return an array of array
			$values = [];
			while($result = mysqli_fetch_array($run)){
				//echo json_encode($result);
			array_push($values, $result);
			}
		}
	}		//end if block
	return $values;
}



//////////////////////////////////////////////////
function mytable($values, $checkbox = "no", $display_col1 = "yes", $grade = "no"){
	//declare global variables
	$fields = array_shift($values); //fields in the table in array format
	global $table_values;

	$checkbox = trim(strtolower($checkbox));
	$display_col1 = trim(strtolower($display_col1));
	$grade = trim(strtolower($grade));

	if($grade == "no"){
		$grade = "";
		$graded = "";
	}

	if($grade == "yes"){
		$graded = "<tr><th></th><td colspan = \"2\">
		<input type = \"radio\" name = \"grade\" value = \"1\" />
		<input type = \"radio\" name = \"grade\" value = \"2\" />
		<input type = \"radio\" name = \"grade\" value = \"3\" />
		<input type = \"radio\" name = \"grade\" value = \"4\" />
		<input type = \"radio\" name = \"grade\" value = \"5\" />
		</td></tr>";
		//a checkbox to indicate that a student has been graded or not
		//$graded = "<input type = \"checkbox\" class = \"graded\" value = \"no\" name = \"graded[]\" checked = \"checked\" />";
		//$graded = "<input type = \"checkbox\" class = \"graded\" value = \"yes\" name = \"graded[]\" checked = \"checked\" />";
	}

	//check to know how many field are in the table and build the table headings
	if($checkbox == "yes"){
		$table_values = "<table><tr><th> </th>";
	} 	elseif($checkbox == "no"){
		$table_values = "<table><tr>";		//in this case we do not need the empty colomn
	}
	if($display_col1 == "yes") {
		for($i = 0; $i < sizeof($fields); $i++){
			$table_values .= "<th>".ucwords($fields[$i])."</th>";
		}
	}	elseif($display_col1 == "no") {
		for($i = 1; $i < sizeof($fields); $i++){
			$table_values .= "<th>".ucwords($fields[$i])."</th>";
		}
	}
	$table_values .= "</tr>";

	if($checkbox == "yes"){
		if($display_col1 == "yes"){
			foreach($values as $value){
				$identity = strtolower($fields[0]);
				$table_values .= "<tr><td><input type = \"checkbox\" class = \"$identity\"  name = \"$identity"."[]\" value = \"$value[0]\" /></td>";
				for($i = 0; $i < sizeof($fields); ++$i){
					$table_values .= "<td>$value[$i]</td>";
				}
				$table_values .= "</tr>";
			}
		} elseif($display_col1 == "no") {
			foreach($values as $value){
				$identity = strtolower($fields[0]);
				$table_values .= "<tr><td><input type = \"checkbox\" class = \"$identity\" name = \"$fields[0][]\" value = \"$value[0]\" /></td>";

				if($grade == "yes" ) {
					$length_of_field = sizeof($fields) - 1;
				} 	else  {
					$length_of_field = sizeof($fields);
				}
				for($i = 1; $i < $length_of_field; ++$i){
					$table_values .= "<td>$value[$i]</td>";
				}
				if($grade == "yes") {			//this field is check by default but it will be hidden by css and revealed only if student have been graded
					$table_values .= "<td><input type = \"checkbox\" class = \"graded\" id = \"grade$value[0]\" value = \"$value[4]\" name = \"graded[]\" checked = \"checked\" disabled = \"disabled\"/></td>";
				}
				$table_values .= "</tr>";
				$table_values .= $graded;
			}
		}
	}
	if($checkbox == "no"){
		foreach($values as $value){
			$table_values .= "<tr>";
			for($i = 0; $i < sizeof($fields); ++$i){
				$table_values .= "<td>$value[$i]</td>";
			}
			$table_values .= "</tr>";
		}
	}
	$table_values .= "</table><br />";

	return $table_values;
}



/////////////////////////////////////////////////////
//function to output result in a select option field
function select_option($values, $label, $name_of_field, $select_class = "", $lable_class = ""){
	$rows = sizeof($values);
	global $select_result;
	global $L;
	//  echo $values;
	$L = sizeof($values[0]);
	// echo $L;
	//if($rows == 1){
	/**
		it appear that when i select a select colomn and call mysqli_fetch_array(), the column returned is two
	  likewise selected two column return 4 and selected 3 column return 6 ... so i have to device this
	  this walk_around to get the appropriate column i am expecting from the result. since this happens
	  when the rows returned i 1 i have to streamline it to only 1 rows return
	*/
	if($L == 2){		
		$L = 1;			
	} 			
	if($L == 4){		
		$L = 2;
	}
	if($L == 6){
		$L = 3;
	}
	//}
	//$rows = sizeof($values);
	$select_result = "<label for = \"$label\" class = \"$lable_class\" > ".ucwords($label)." </label><select id = \"$label\" name = \"$name_of_field\" class = \"$select_class\">";
	if($L == 1) {	//it a one column one or more rows array
		$L = $L- 1;
		for($i = 0; $i < $rows; $i++) {
			$select_result .= "<option value =  \"".$values[$i][$L]."\">".$values[$i][$L]."</option>";
		}
	}  elseif($L == 2) {
		$index0 = $L - 2; 	// == 0
		$index1 = $L - 1; 	// ==1
		for($i = 0; $i < $rows; $i++) {
			$select_result .= "<option value =  \"".$values[$i][$index0]."\">".$values[$i][$index1]."</option>";
		}
	} elseif($L == 3) {
		$index0 = $L -3;  	// ==0
		$index1 = $L - 2; 	// == 1
		$index2 = $L - 1; 	// ==2
		for($i = 0; $i < $rows; $i++) {
			$select_result .= "<option value =  \"".$values[$i][$index0]."\">".$values[$i][$index1]." ".$values[$i][$index2]."</option>";
		}
	}
	$select_result .= "</select>";
	return $select_result;
}


///////////////////////////////////////////////////////////

function get_chats($values, $sender, $receiver) {
	global $top_post_time;
	$chats = "";
	$top_post_time = $values[0][3];	    // the oldest post time among the ones fetch. a hook to fetch old post
	foreach($values as $value){
		$field_length = sizeof($value);
		//set the class for the sender and the reciever to uniquely identify each
		if($value[0] == $receiver){
			$class_value = "receiver";
		}
		if($value[0] == $sender){
			$class_value = "sender";
		}
		$chats .= "<p chat-post-succeed=true class = \"$class_value chats\">$value[1]<br />    $value[3]";
		$chats .= "</p>";
		//if a media file is uploaded
		if($value[2] != ""){
			//$url = "C:/xampp/htdocs$value[2]";
			$url = $value[2];
			$pos_media_indicator = strrpos($url, "-");
			$media_indicator = substr($url, $pos_media_indicator);
			$url = substr($url, 0, $pos_media_indicator);
			if($media_indicator === "-image") {
				$chats .= "<br /><img src = \"$url\" alt = \"image\" />";
			}
			if($media_indicator === "-audio") {
				$source = multi_source($url);
				$chats .= "<audio> $source </audio>";
			}
			if($media_indicator === "-video") {
				$source = multi_source($url);
				$chats .= "<video> $source </video>";
			}
			//$url = basename($url);
			//$filetype = filetype($url);
			//echo $filetype;
			//$openf = fopen($url, r);
			//$type = fread($openf,);
		}
	}	//end foreach
	return $chats;
} 	//end get_chat function



//////////////////////////////////////
function get_chat_xml($chats, $sender, $receiver) {

$dom = new DOMDocument("1.0");
$container = $dom -> createElement("chats");
$dom -> appendChild($container);
foreach($chats as $chat) {
$para_ele = $dom -> createElement("p");
$para_text = $dom -> createTextNode($chat[1]. $chat[3]);
$para_ele -> appendChild($para_text);
$container ->appendChild($para_ele);

}	//end foreach

$chat_xml = $dom -> saveXML();
return $chat_xml;
}		//end get_chat_xml

/////////////////////////////////////////////////////////////
//for downloading files
function file_download($path, $filename) {


if(!$filename) {
echo "error";
 }	else 	{
//$path = $path . $filename;

if(file_exists($path) && is_readable($path)) {

$size  = filesize($path);
header("Content-Type:application/Octet-Stream");
header("Content-Length:$size");
header("Content-Disposition:attachment; filename=$filename");
header("Content-Transfer-encoding:binary");
//open the file read and deliver
$file = @fopen($path, "rb");
if($file) {
fpassthru($file);
exit;
} else	{
echo "error1";
}
}	else	{

echo "error2";
}

}


}		//end file_download

/*
/////////////////////////////////////////////
//to run iterative query on an array
function query_iterator ($values) {
$array_container = array ();
foreach($values as $value ){
echo "values = ".$value;
$query_string = "select id, firstname, lastname from registered_students where id = \"$value\"";

//$query_string = $query_string;
run_query($query_string);
echo $row_num2;
if($row_num2 == 0){
echo "no student";
//$select_result = "<select><option>No lecturer</option></select>";
}	else 	{ 
$value = build_array($row_num2);
$array_container[] = $value;		//push each lecturer info into  the array
}
}
$return = $array_container;


}

*/

///////////////////////////////////////////////////////
//function to provide multiple extentions for playing media
function multi_source($file_source)	{
$ext_array = ["mvc", "ogg", "3gp", "mp4", "avi" ];
$ext_size = sizeof($ext_array);
$dot_pos = strpos($file_source, ".");
$sources = "";
for($i = 0; $i < $ext_size; $i++) {
$source = substr_replace($file_source, $ext_array[$i], $dot_pos + 1);
$sources .= "<source src = \"$source\" />";
}		//end for block
return $sources;
}	//end multi_source


//////////////////////////////////////////////////////////
function array_to_string ($data, $delimiter) {
//data is an array that need to be converted to string with space between
// initialize a string to hold the answers
$answers = "";
foreach ($data as $answer){
$answers .= "$answer$delimiter";		//we explode the string later with " " delimiter
return $answers;
}
}

////////////////////////////////////////////////////////////////////////////////
//function to select the course_ids of students from registered_courses
function registered_course_ids($S_id, $lecturer_db){
global $row_num2;
$query_string = "select course_id from registered_courses where student_id = \"$S_id\"";
run_query($query_string, $lecturer_db);
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



/////////////////////////////////////////////////////////////////////////////////////
//function for selecting course_code and/or course_id from db
// function get_course_code($course_id, $query_to_run = 1, $lecturer_db, $query_string = "default"){
function get_course_code($course_id, $query_to_run = 1, $lecturer_db, $query_string = "default"){
	global $row_num2;
	if($query_string === "default"){
		$query_string = "select course_code from courses where course_id = \"$course_id\"";
		if($query_to_run === 2){
			$query_string = "select course_id, course_code from courses where course_id = \"$course_id\"";
		}  else if($query_to_run === 3){
			$query_string = "select course_id, course_code, course_title, course_description, unit from courses where course_id = \"$course_id\"";
		}
	}	
	run_query($query_string, $lecturer_db);
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




//////////////////////////////////////////////////////////////////////////////////////////
//function for iterating an array to query db
function foreach_iterator2($function_name, $to_iterate, $extra_condition = "no", $lecturer_db = "no" ){
	$new_array = [];
	foreach($to_iterate as $iterate){
		if( $extra_condition !== "no" && $lecturer_db !== "no" ){
			$value = $function_name($iterate, $extra_condition, $lecturer_db );
		}	else if( $extra_condition === "no" && $lecturer_db !== "no"  ) {
			$value = $function_name($iterate, $lecturer_db );
		}	else if( $extra_condition !== "no" && $lecturer_db == "no" ) {
			$value = $function_name($iterate, $extra_condition);
		}	else if ( $extra_condition === "no" && $lecturer_db === "no"   ) {
			$value = $function_name($iterate);
		} 
		if(empty($value)){
		//no action
		}	else	{
			if (is_array($value[0])){
				foreach($value as $value){
					$new_array[] = $value;
				}
			}	else	{
				$new_array[] = $value;
			}
		}
	}		//end foreach
	return $new_array;
}		//end foreach_iterator





//////////////////////////////////////////////////////////////////////////////////////////
//function for iterating an array to query db
function foreach_iterator( $to_iterate, $extra_condition = "no", $lecturer_db = "no"  ){
	$new_array = [];
		foreach($to_iterate as $iterate){
		if( $extra_condition !== "no" && $lecturer_db !== "no" ){
			$value = get_course_code($iterate, $extra_condition, $lecturer_db );
		}	else if( $extra_condition === "no" && $lecturer_db !== "no"  ) {
			$value = get_course_code($iterate, $lecturer_db );
		}	else if( $extra_condition !== "no" && $lecturer_db == "no" ) {
			$value = get_course_code($iterate, $extra_condition);
		}	else if ( $extra_condition === "no" && $lecturer_db === "no"   ) {
			$value = get_course_code($iterate);
		} 
		if(empty($value)){
		}	else	{
			$new_array[] = $value;
		}
	}		//end foreach
	return $new_array;
}		//end foreach_iterator





/*
//////////////////////////////////////////////////////////////////////////////////////////
//function for iterating an array to query db
function foreach_iterator( $lecturer_db, $to_iterate, $and_id = "no"){
$new_array = [];
foreach($to_iterate as $iterate){
if(strtolower($and_id) === "yes"){
$value = get_course_code($lecturer_db, $iterate, "yes");

}	else	{
$value = get_course_code($lecturer_db, $iterate);
}
$new_array[] = $value;
}		//end foreach
return $new_array;
}		//end foreach_iterator

*/

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
function get_videos($course_id, $query_to_run = 1, $lecturer_db) {
	global $row_num2;
	if($query_to_run == 1) {
		$query_string = "select id, video_url, video_name, video_caption, course_id from videos where course_id =\"$course_id\"";
	}
if($query_to_run === 2) {
		$query_string = "select id, video_url, video_name, video_caption, course_id from videos";
	}
	run_query($query_string, $lecturer_db);
	if($row_num2 == 0 ){
	return;
	}	else 	{
	$video_details = build_array($row_num2);
	if($row_num2 == 1){
	$video_details = [$video_details];
	}
	$videos = [];
	foreach($video_details as $video){
	$video[4] = get_course_code($video[4], 1, $lecturer_db);
	$videos[] = $video;
	}
	return $videos;
	}
}

/*
class student {
public $student_id;
//$this -> student_id = $student_id;
function __construct($student_id){
echo "i have a new student with id ".$student_id;
}
function get_course_code(){
$this -> student_id = $student_id;
echo "is your id $student_id";
}
}
$me = new student(5);
$me -> get_course_code();
*/

?>