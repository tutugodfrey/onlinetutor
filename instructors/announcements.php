<?php
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];

if(isset($_GET["announcements"])){
	if($owner_id == ""){
		$display = "<p>you cannot continue you username is not set</p>";
	}	else	{
		$heading = "<h1> Make Announcement </h1>";
		$display = "<p>want to make an announcement?</p>";
		$display .= <<<block
		<form name = "announcementForm" class = "form-group" method = "POST" action = "$_SERVER[PHP_SELF]" >
		<label for = "announcement">Type your message here</label><br/>
		<textarea cols = "50" rows = "7" name = "message"></textarea><br />
		<input type = "submit" class = "btn btn-success" id = "makeAnnouncement" name = "make_announcement" value = "Submit Announcement" />
		</form>
		<a id = "viewAnnouncements" class = "btn btn-primary" href = "$_SERVER[PHP_SELF]?view_announcement=yes">View Announcement</a>
block;
	}
}

if(isset($_POST["make_announcement"])){
	$message = trim($_POST["message"]);
	if($message == ""){
		$heading = "";
		$display = "<p>Please type your message to make announcement</p>";
	}	else	{
	$query_string  = "insert into announcements value (null, \"$message\", now()) where lec_id = \"$owner_id\"";
		run_query($query_string, $lecturer_db);
		if($row_num2 == 0){
			$heading = "";
			$display = "<p>you message could not be posted</p>";
		}	else	{
			$heading = "<h1>Announcement Posting Result</h1>";
			$display = "<p>Your message has been posted</p>";
			$display .= "<a id = \"viewAnnouncements\" class = \"btn btn-primary\" href = \"$_SERVER[PHP_SELF]?view_announcement\">View Announcement</a>";
		}
	}
}

if(isset($_GET["view_announcement"])){
	$fields = array ("announcement_id", "Announcement", "Post Date");
	$query_string = "select announcement_id, post_message, post_date from announcements";
	run_query($query_string, $lecturer_db);
	if($row_num2 == 0){
		$heading = "";
		$display = "<p>You have not posted any announcement</p>";
	}	else	{
		$values = build_array($row_num2);
		if($row_num2 == 1){
			$heading = "<h1>Announcement</h1>";
			$values = [$values];
		}
		if($row_num2 > 1){
			$heading = "<h1>Announcements</h1>";
		}
		array_unshift($values, $fields);
		$table_values = mytable($values, "yes", "no");
		$display = <<<block
		<form class = "form-group" name = "announcementForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
		<h3>Select a checkbox to delete announcement</h3>
		$table_values
		<input type = "submit" class = "btn btn-success" id = "deleteAnnouncement" name = "delete" value = "Delete announcemet" />
		</form>
block;
	}
}

if(isset($_POST["delete"])){
	if(trim($_POST["announcement_id"][0]) == ""){
		$heading = "";
		$display = "<p>Action could not be completed please select an announce to delete</p>";
	}	else	{
		$announcement_id = trim($_POST["announcement_id"][0]);
		$query_string = "delete from announcements where announcement_id = \"$announcement_id\"";
		run_query($L_id);
		if($row_num2 == 0){
			$heading = "";
			$display = "<p>No Announcement Now. Keep Checking</p>";
		}	else	{
			if($row_num2 == 1){
				$heading =  "<h3>Announcement Deletion Result</h3>";
				$display = "<p>Announcement deleted</p>";
			}
		}
	}
}

}	else {
header("Location:/common/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $heading; ?>
<?php echo $display; ?>
