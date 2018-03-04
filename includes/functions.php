<?php 
////////////////////////////////////////////////////
function run_query($query_string, $database = "vl25t93kin6m2cdr"){
	// $database = "vl25t93kin6m2cdr";
	// $database = "onlinetutor"
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









//////////////////////////////////////////////////////////////////////////////////////////
//function for iterating an array to query db
function foreach_iterator2($function_name, $to_iterate, $lec_id = "no", $extra_condition = "no" ){
	$new_array = [];
	foreach($to_iterate as $iterate){
		if( $extra_condition !== "no" && $lec_id !== "no" ){
			$value = $function_name($iterate, $lec_id, $extra_condition);
		}	else if( $extra_condition === "no" && $lec_id !== "no") {
			$value = $function_name($iterate, $lec_id );
		}	else if( $extra_condition !== "no" && $lec_id == "no") {
			$value = $function_name($iterate, $extra_condition);
		}	else if ( $extra_condition === "no" && $lec_id === "no" ) {
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

?>