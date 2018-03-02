<?php
//include all required files
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lec_id = $_SESSION["lec_id"];
$note_table = "user".$owner_id."_note";

if($owner_id == ""){
$display = "<p>An error occur it seem you did not select any lectuerer</p>";
}	else		{
$heading = "";
if(isset($_GET["mynote"]) || isset($_POST["edit"]) ){
  //offset all initial values
  $post_text = "";   $note_id = "";  $note_course_code = "";  $registered_course_code = "";  $title = ""; $saved_note_heading = "";
  $new_note_heading = ""; $heading = ""; $view_note_button = ""; $note_id = ""; $note_courses = "";  $registered_courses = ""; $topic = ""; $save_button = ""; $update_button = "";	$display = ""; $text_area = ""; $edit_note_heading = ""; $hidden_course_id = "";
  $course_ids = registered_course_ids($owner_id, $lec_id);
  if($course_ids == ""){
    $display = "<p>You have not registered any course with this lecturer</p>";
  }	else 	{
    $courses = foreach_iterator2("get_course_code", $course_ids, 2);
    if($courses == ""){
      $display = "<p>information about your registered courses could not be fetched</p>";
    }	else	{
      $registered_courses = select_option($courses, "course code", "course_id");
    }
  }

  if( isset($_GET["mynote"])){
    $query_string = "select id, course_id, title from $note_table";
    run_query($query_string);
    if($row_num2 == 0 ){
      $note_courses = "<p>You have no saved notes</p>";
    }	else	{
      $notes_info = build_array($row_num2);
      if($row_num2 == 1){
        $notes_info = [$notes_info];
      }
      $notes = [];
      foreach($notes_info as $note_info){
        $query_string = "select course_code from courses where course_id = \"$note_info[1]\"";
        run_query($query_string, $lecturer_db);
        if($row_num2 == 0){
          $course_code = "";
        }	else {
          $course_code = build_array($row_num2);
        }
        $note_info[1] = $course_code;
        $notes[] = $note_info;
      }		//end foreach
      $fields = array ("note_id", "course code", "title");
      array_unshift($notes, $fields);
      $saved_note_heading = "<h1>Select a note to read</h1>";
      $note_courses = mytable($notes, "yes", "no");
      $view_note_button = "<input type = \"submit\" class = \"inner_btns\" id = \"viewNote\" name = \"view_note\" value = \"Read Note\" />
      <input type = \"submit\" class = \"btn btn-success\" id = \"editNote\" name = \"edit_note\" value = \"Edit\" />
      <br /><br />";
    }
    $new_note_heading = "<h1>Take note</h1>";
    $save_button = "<input type = \"submit\" class = \"inner_btns\" id = \"saveNote\" name = \"save_note\" value = \"SAVE\" />";
    $topic = "<label for = \"topic\">Title</label><input type = \"text\" name = \"topic\" value = \"$title\" size = \"50\" /><br />";
    $text_area = "<label for = \"note_text\">Write your Note</label><br /><textarea rows = \"7\" cols = \"50\" name = \"post_text\" >$post_text</textarea><br />";
  }

  if(isset($_POST["edit"])){
    $title = trim($_POST["topic"]);
    $course_id = trim($_POST["course_id"]);
    $post_text = trim($_POST["post_text"]);
    if(!isset($_POST["note_id"])){
      $display = "<p>Please the checkbox to edit the note</p>";
    }	else	{
      $edit_note_heading = "<h1>Edit note</h1>";
      $note_id = $_POST["note_id"][0];
      $note_id = "<input type = \"hidden\" name = \"note_id\" value = \"$note_id\" />";
      $hidden_course_id = "<input type = \"hidden\" name = \"hcourse_id\" value = \"$course_id\" />";
      $text_area = "<label for = \"note_text\">Write your Note</label><br /><textarea rows = \"7\" cols = \"50\" name = \"post_text\" >$post_text</textarea><br />";
      $topic = "<label for = \"topic\">Title</label><input type = \"text\" name = \"topic\" value = \"$title\" size = \"50\" /><br />";
      //$registered_courses = "<label for = \"course_code\">Course Code</label><input type = \"text\" name = \"course_\" value = \"$note_course\"/><br />";
      $update_button = "<input type = \"submit\" class = \"btn btn-success\" id = \"updateNote\" name = \"update_note\" value =  \"Update Note\" />";
    }
  }		//end edit

  $display .= <<<block
  <form name = "noteForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
  <!-- field to view note -->
  $saved_note_heading
  $edit_note_heading
  $note_courses
  $view_note_button
  $note_id
  $new_note_heading
  $registered_courses <br />
  $topic
  $text_area
  $save_button
  $hidden_course_id
  $update_button
  </form>
block;
}


if(isset($_POST["save_note"]) || isset($_POST["update_note"]) || isset($_POST["change_course"])){
  $course_id = trim($_POST["course_id"]);
  $post_text = trim($_POST["post_text"]);
  $title = trim($_POST["topic"]);
  if($title == ""){
    $topic = "untitled";
  }
  if($course_id == "" || $post_text == ""){
    $display = "<p>Please fill out the required fields to save your note</p>";
  }	else 	{
    //common for no_change_course, change_course, save_course(when the course already exists)
    $attachment = <<<block
    <input type = "hidden" name = "topic" value = "$title" />
    <input type = "hidden" name = "post_text" value = "$post_text" />
block;

      //verify the selected course is same with the old course
    if(isset($_POST["update_note"])){
      $hcourse_id = $_POST["hcourse_id"];
      $course_code = get_course_code($course_id);
      $course_code2 = get_course_code($hcourse_id);
      $note_id = $_POST["note_id"];
      $note_id = "<input type = \"hidden\" name = \"note_id\" value = \"$note_id\" />";

      if($hcourse_id !== $course_id){
      echo  <<<block
        <p>You are about to change the course code for this note from $course_code2 to $course_code</p>
        <form name = "verify_course_change" method = "POST" action = "$_SERVER[PHP_SELF]" >
        $attachment
        $note_id
        <input type = "hidden" name = "course_id" value = "$course_id" />
        <input type = "submit" class = "inner_btns" value = "Continue" name = "change_course" />
        </form>
        <form name = "verify_course_change" method = "POST" action = "$_SERVER[PHP_SELF]" >
        $attachment
        $note_id
        <input type = "hidden" name = "course_id" value = "$hcourse_id" />
        <input type = "submit" class = "bt btn-success" id = "editNote" name = "edit" value = "Back" />
        </p>
        </form>
block;
      exit();
      }
    }

    if(isset($_POST["save_note"])){
      $query_string = "select id from $note_table where course_id = \"$course_id\" and note = \"$post_text\"";
    }
    if(isset($_POST["update_note"]) || isset($_POST["change_course"])){
      $note_id = $_POST["note_id"];
      $query_string = "update $note_table set note = \"$post_text\", title = \"$title\", course_id = \"$course_id\" where id = \"$note_id\"";
    }
    run_query($query_string);
    if($row_num2 == 1){		//the update is successful  or the note already exist
      if(isset($_POST["save_note"])){
        $note_id = build_array($row_num2);
        $display = <<<block
        <p>This note already exists. if you will want to edit it click the button below</p>
        <form name = "noteForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
        $attachment
        <input type = "hidden" name = "course_id" value = "$course_id" />
        <input type = "hidden" name = "note_id" value = "$note_id" />
        <input type = "submit" class = "btn btn-primary" id = "editNote" name = "edit" value = "Edit" />
        </form>
block;
      }	elseif(isset($_POST["update_note"]) || isset($_POST["change_course"]))	{
       $display = "<p>Your note have been updated</p>";
      }
    }	elseif($row_num2 == 0){		//the note could not be updated or the note does not already exists
      if(isset($_POST["save_note"])){		//continue if the not does not already exists
        $query_string = "insert into $note_table values (null, \"$course_id\", \"$title\", \"$post_text\", now())";
        run_query($query_string);
        if($row_num2 == 0){
          $display = "<p>Your note could note be save now. please check your network connection</p>";
        }	else	{
          $display = "<p>Your note have been save</p>";
        }
      }	elseif(isset($_POST["update_note"]) || isset($_POST["change_course"]))	{		//if the not could not be saved
        $display = "<p>The update could not be save now, please check your network connection and try again</P>";
      }
    }
  }
}		//end save note

  if(isset($_POST["view_note"])){
    if(empty($_POST["note_id"])){
      $display = "<p>Please use the checkbox to select a saved note to read</p>";
    }	else	{
      $note_id = trim($_POST["note_id"][0]);
      $query_string = "select id,  course_id, title, note, note_date from $note_table where id = \"$note_id\"";
      run_query($query_string);
      if($row_num2 == 0){
        $display = "<p>Your note could not be fetch now. please check your network connectivity and try again</p>";
      }	else 	{
        $note = build_array($row_num2);
        $post_text = trim($note["note"]);
        $post_date = $note["note_date"];
        $title = trim($note["title"]);
        $course_id = trim($note["course_id"]);
        $course_code = get_course_code($course_id, $lec_id);
        $note_id = $note["id"];
        $display = <<<block
        <div>
        <h1>$course_code: $title </h1><p>posted on $post_date</p>
        <br />
        $post_text
        </div>
        <form name = "noteForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
        <input type = "hidden" name = "post_text" value = "$post_text" />
        <input type = "hidden" name = "topic" value = "$title" />
        <input type = "hidden" name = "note_id" value = "$note_id" />
        <input type = "hidden" name = "course_id" value = "$course_id" />
        <input type = "submit" class = "btn btn-warn" id = "editNote" name = "edit" value = "EDIT" />
        <input type = "submit" class = "btn btn-danger" id = "deleteNote" name = "delete" value = "Delete" />
        </form>
block;
      }
    }
  }

  if(isset($_POST["edit_note"])){
    if(empty($_POST["note_id"])){
      $display = "<p>Please select a note to edit</p>";
    }	else	{
      $note_id = $_POST["note_id"][0];
      $query_string = "select course_id, title, note from $note_table where id = \"$note_id\"";
      run_query($query_string);
      if($row_num2 == 0){
        $display = "<p>The selected note could not be fetched</p>";
      }	else	{
        $note = build_array($row_num2);
        $course_id = $note[0];
        $title = $note[1];
        $post_text = $note[2];
        $display = <<<block
        <p>You want to edit the note $title</p>
        <form name = "noteForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
        <input type = "hidden" name = "post_text" value = "$post_text" />
        <input type = "hidden" name = "topic" value = "$title" />
        <input type = "hidden" name = "note_id" value = "$note_id" />
        <input type = "hidden" name = "course_id" value = "$course_id" />
        <input type = "submit" class = "btn btn-swarn" id = "continueEdit" name = "edit" value = "Continue" />
        </form>
block;
      }
    }
  }


  if(isset($_POST["delete"])){
    if(empty($_POST["note_id"])){
      $display = "<p>Please select the checkbox to delete the note</p>";
    } 	else 	{
      $note_id = trim($_POST["note_id"][0]);
      $query_string = "delete from $note_table where id = \"$note_id\"";
      run_query($query_string);
      if($row_num2 == 0){
        $display = "<p>The note could note be deleted now please try again later</p>";
      }	else {
        $display = "<p>The note has been deleted</p>";
      }
    }
  }

  }	//end verify owner_id
}	else {
  header("Location:/onlinetutor/login.php");  		//user do not have an active session
  exit();
}

?>

<?php echo $heading; ?>
<?php echo $display; ?>
