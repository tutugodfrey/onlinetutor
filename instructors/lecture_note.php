<?php
//include all required files
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";
$doc_root = $_SERVER["DOCUMENT_ROOT"];


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];

if(isset($_GET["lecture_note"]) || isset($_POST["edit"])){
	$heading = ""; $view_old_note = ""; $view_note_button = ""; $saved_courses = ""; $course_note = ""; $topic = ""; $save_button = ""; $post_text = "";
	$display = ""; $update_button = ""; $text_area = ""; $note_id = ""; $take_new_note = ""; $upload_note = ""; $upload_image = ""; $title = "";
	if(isset($_GET["lecture_note"])){
		//get the course lecturer is taking table
		$query_string = "select course_id, course_code from courses where lec_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>You have not saved any course <a href = \"/instructors/save_course.php?save_courses=yes\" id = \"saveCourse\" class = \"btn btn-primary\" >add a course now!</a></p>";
		}		else		{
		$course_details = build_array($row_num2);
		if($row_num2 == 1) {
			$course_details = [$course_details];
		}
		//echo $course_details[1][1];
		//$course_details = [$course_details];
		$saved_courses = select_option($course_details, "course code", "course_id", "form-control");
		mysqli_free_result($run);
		$heading = "<h1>Take note</h1>";
		$text_area = "<label for = \"note_text\">Write your Note</label><br /><textarea id = \"note_text\" class = \"form-control\" rows = \"7\" cols = \"50\" name = \"post_text\" ></textarea><br />";
		$take_new_note = "<h3>Take new note</h3>";
		$topic = "<label for = \"topic\">Title</label><input type = \"text\" id = \"topic\" class = \"form-control\"  name = \"topic\"  size = \"50\" /><br />";
		$upload_note = "<label for = \"textFile\">Upload Note</label><input type = \"file\" id = \"textFile\" class = \"form-control\" name = \"text_file\" />";
		$upload_image = "<label for = \"upload_iamge\">Upload Image</label><input type = \"file\" id = \"imageFile\" class = \"form-control\" accept = \"image/*\" multiple name = \"image_file[]\" />";
		$save_button = "<input type = \"submit\" class = \"btn btn-success\" id = \"saveNote\" name = \"save_note\" value = \"SAVE\" />";
		//work with already save notes
		$query_string = "select note_id, course_id, title from notes where user_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>You have no saved notes</p>";
		}	else 	{
			$note_details  = build_array($row_num2);
			if($row_num2 == 1){
				$note_details = [$note_details];
			}
			$saved_note_info = [];
			foreach($note_details as $note_detail) {
				$course_id = $note_detail[1];
				$query_string = "select course_code from courses where course_id = \"$course_id\" and lec_id = \"$owner_id\"";
				run_query($query_string);
				if($row_num2 == 0) {
					$display = "<p>Course detail could note be fetch</p>";
				}	else 	{
					$course_code = build_array($row_num2);
					//replay course_id with course_code in note_detail
					$note_detail[1] = $course_code;
				}
				$saved_note_info[] = $note_detail;
			} 	//end foreach
			$fields = array("note_id", "course code", "title");
			array_unshift($saved_note_info, $fields);
			//$course_note = select_option($saved_note_info, "course code", "note_id");	//course code will be displayed and id hidden
			$course_note = mytable($saved_note_info, "yes", "no");
			$view_old_note = "<h3>View your saved notes</h3>";
			$view_note_button = "<input type = \"submit\" class = \"btn btn-success\" id = \"viewNote\" name = \"view_note\" value = \"View Note\" /><br /><br />";
			}
		}
	}

	if(isset($_POST["edit"])){
		$title = trim($_POST["topic"]);
		$course_id = trim($_POST["course_id"]);
		$post_text = trim($_POST["post_text"]);
		if(!isset($_POST["note_id"])){
			$display = "<p>Please the checkbox to edit the note</p>";
		}	else	{
			$note_id = $_POST["note_id"][0];
			$query_string = "select course_id, course_code from courses where lec_id = \"$owner_id\"";
			$course_details = get_course_code($course_id, $owner_id, 1, $query_string);
			$note_id = "<input type = \"hidden\" name = \"note_id\" value = \"$note_id\" />";
			$text_area = "<label for = \"note_text\">Write your Note</label><br /><textarea rows = \"7\" cols = \"50\" class = \"form_control\" name = \"post_text\" >$post_text</textarea><br />";
			$topic = "<label for = \"topic\">Title</label><input type = \"text\" class = \"form-control\" name = \"topic\" value = \"$title\" size = \"50\" /><br />";
			$course_note = select_option($course_details, "course code", "course_id", "form-control");
		}
		$heading = "<h1>Edit Note</h1>";
		$display = "<p>use the input fields below to edit you note, then click the update button</p>"; 
		$update_button = "<input type = \"submit\" class = \"btn btn-success\" id = \"updateNote\" name = \"update_note\" value =  \"Update Note\" />";
	}

	$display .= <<<block
	<form name = "lecture_note" class = "form-group" method = "POST" action = "$_SERVER[PHP_SELF]" enctype = "multipart/form-data" >
	<!-- field to view note -->
	$view_old_note
	$course_note
	$view_note_button
	$note_id
	$take_new_note
	$saved_courses
	<br/>
	$topic
	$text_area
	$upload_note <br />
	$upload_image <br />
	$save_button
	$update_button
	</form>
block;
}



if(isset($_POST["save_note"]) || isset($_POST["update_note"])){
	$heading= "";
	/*
	///////////////
	//dealing with uploaded images
	if(is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
	$image_store = "C:/xampp/htdocs/mylecturerapp/personal_data/$lecturer_db/images";

	move_uploaded_file($_FILES['image_file']['tmp_name'],      
	"$image_store/".$_FILES["image_file"]['name']) or die("Couldn't move file");     //can also be $store."/".
	$picture = "C:/xampp/htdocs/mylecturerapp/personal_data/".$lecturer_db."/images".$_FILES["image_file"]['name'];	//save the url to the database

	$image_url  = "/mylecturerapp/personal_data/".$lecturer_db."/images/".$_FILES["image_file"]['name'];
	echo $image_url;
	//$image_html = "<img src = \"\"$image_url\"\" alt = \"\"image\"\" />";
	$image_html = "<img src = '$image_url' alt = 'image' />";

	echo "you just uploaded an image now ";
	//$post_text  = "hello guys". $image_html . "i have an image to share";
	}	else {
	//no image is posted 
	$image_html = "";
	}
	*/

	///////////////////////////
	if(is_uploaded_file($_FILES["text_file"]["tmp_name"])) {
		$store = "$doc_root/personal_data/user".$owner_id;
		move_uploaded_file($_FILES['text_file']['tmp_name'],      
		"$store/".$_FILES["text_file"]['name']) or die("Couldn't move file"); //can also be $store."/".
		$note_url = "$doc_root/personal_data/user".$owner_id."/".$_FILES["text_file"]['name'];	//save the url to the database
		//$content = file_get_contents($picture);
		$post_text = ""; 
		// $content = readfile($picture);
	}	else	{
		$post_text = trim($_POST["post_text"]);
		$post_text = str_replace("insert_image", $image_html, $post_text);
		admin_connect();
		$post_text = mysqli_real_escape_string($mysqli, trim($post_text));
		$note_url = "";
	}

	$course_id = trim($_POST["course_id"]);
	$title = trim($_POST["topic"]);
	if($title == ""){
		$topic = "untitled";
	}

	if($course_id === "" && ($post_text === "" || $note_url === "")) {
		$display = "<p>Please fill out the required fields to save your note</p>";
	}	elseif(isset($_POST["save_note"])){
		$heading = "<h1>Save note result</h1>";
		$query_string = "select note_id from notes where course_id = \"$course_id\" and note = \"$post_text\" and note_url = \"$note_url\" and  user_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 1){
			$note_id = build_array($row_num2);
			$display = <<<block
			<p>This note has already been saved. if you would like to update it click the button below</p>
			<form method = "POST" name = "lecture_note" action = "$_SERVER[PHP_SELF]" >
			<input type = "hidden" name = "note_id" value = "$note_id" />
			<input type = "hidden" name = "topic" value = "$title" />
			<input type = "hidden" name = "post_text" value = '$post_text'/>
			<input type = "hidden" name = "course_code" value = "$course_code" />
			<input type = "submit" class = "btn btn-success" id = "editNote" name = "edit" value = "EDIT" />
			</form>
block;
		}	else	{
			$query_string = "insert into notes values (null,\"$owner_id\", \"$course_id\", \"$title\", \"$post_text\", \"$note_url\", now())";
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<p>Your note could note be save now. please check your network connection</p>";
			}	else	{
				$display = "<p>Your note have been save</p>";
			}
		}
	}	elseif(isset($_POST["update_note"])){
		$note_id = $_POST["note_id"];
		$query_string = "update notes set note = \"$post_text\", title = \"$title\", course_id = \"$course_id\" where note_id = \"$note_id\" and user_id = \"$owner_id\"";
		$heading = "<h1>Note Update result</h1>";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>The update could not be save now, please check your network connection and try again</P>";
		}	else	{
			$display = "<p>Your note have been updated</p>";
		}
	}
}



if(isset($_POST["view_note"])){
	$heading = "";
	$note_id = trim($_POST["note_id"][0]);
	if($note_id == ""){
		$display  = "<p>An error occur. please go back and try again</p>";
	}	else	{
		$query_string = "select note_id, course_id, title, note, note_url, note_date from notes where note_id = \"$note_id\" and user_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>Your note could not be fetch now. please check your network connectivity and try again</p>";
		}	else 	{
			$heading = "<h1>Your note</h1>";
			$result = build_array($row_num2);
			 $course_id = $result[1];
			 $note_url = $result[4];
			 $note = $result[3];
			//get the course code 
			$query_string = "select course_code from courses where course_id = \"$course_id\" and lec_id = \"$owner_id\"";
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<p>The course could not be fetch</p>";
			}	else 	{
				$course_code	= build_array($row_num2);
			}
			$result[1] = $course_code;
			$result = [$result];
					//checkbox is display and id is hidden value of id is in the checkbox
			if($note_url === "") {
				$post_text = trim($result[0]["note"]);		//obtain the note incase user want to edit it
				$title = trim($result[0]["title"]);
				$course_code = trim($result[0]["course_id"]);
				$fields = array ("note_id", "Course code", "Title", "Note", "Post Date");
				array_unshift($result, $fields);
				$table_values = mytable($result, "Yes", "no");
				$display = <<<block
				<form name = "lecture_note" method = "POST" action = "$_SERVER[PHP_SELF]" >
					<input type = "hidden" name = "post_text" value = "$post_text" />
					<input type = "hidden" name = "topic" value = "$title" />
					<input type = "hidden" name = "course_id" value = "$course_code" />
					$table_values
					<input type = "submit" class = "btn btn-success" id = "editNote" name = "edit" value = "EDIT" />
					<input type = "submit" class = "btn btn-danger" id = "deleteNote" name = "delete" value = "Delete" />
				</form>
block;
			} else if ($note === "") {
				$display = "<a href = \"$_SERVER[PHP_SELF]?download_note=yes&path=$note_url\"> Download Note</a>";
			}
		}
	}
}

if(isset($_GET["download_note"])){
	//file_download($path);
	//$heading = "";
	$path = $_GET["path"];
	$path_array = explode ("/", $path);
	$filename =  $path_array[6];
	//echo $filename;
	//$display= $path;
	file_download($path, $filename);
}

if(isset($_POST["delete"])){
	$heading = "<h1>Delete Result</h1>";
	if(empty($_POST["note_id"])){
		$display = "<p>Please select the checkbox to delete the note</p>";
	} 	else 	{
		$note_id = trim($_POST["note_id"][0]);
		$query_string = "delete from notes where note_id = \"$note_id\" and user_id = \"$owner_id\"";
		run_query($query_string);
		if($row_num2 == 0){
			$display = "<p>The note could note be deleted now. please try again</p>";
		}	else {
			$display = "<p>The note has been deleted</p>";
		}
	}
}


}	else {			
header("Location:/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $heading; ?>
<?php echo $display; ?>
