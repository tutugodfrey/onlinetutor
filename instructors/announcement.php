<?php
include  "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];



if(isset($_POST["announcement"]) || isset($_GET["announcement"])){
if($owner_id == ""){
$display = "<p>you cannot continue you username is not set</p>";
}	else	{

$heading = "<h1> Make Announcement </h1>";
$display = "<p>want to make an announcement?</p>";
$display .= <<<block
<form name = "announcementForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
<label for = "announcement">Type your message here</label><br/>
<textarea cols = "50" rows = "7" name = "message"></textarea><br />
<input type = "submit" class = "inner_btns" id = "makeAnnouncement" name = "make_announcement" value = "Submit Announcement" />
</form>
<a id = "viewAnnouncements" href = "$_SERVER[PHP_SELF]?view_announcement=yes">View Announcement</a>
block;
}
}


if(isset($_POST["make_announcement"])){
$message = trim($_POST["message"]);

if($message == ""){
$heading = "";
$display = "<p>Please type your message to make announcement</p>";
}	else	{
$query_string  = "insert into announcement value (null, \"$message\", now())";
run_query($query_string, $lecturer_db);
if($row_num2 == 0){
$heading = "";
$display = "<p>you message could not be posted</p>";
}	else	{
$heading = "<h1>Announcement Posting Result</h1>";
$display = "<p>Your message has been posted</p>";
$display .= "<a id = \"viewAnnouncements\" href = \"$_SERVER[PHP_SELF]?view_announcement=yes\">View Announcement</a>";
}
}
}

if(isset($_GET["view_announcement"])){
$fields = array ("announcement_id", "Announcement", "Post Date");
$query_string = "select id, post_message, post_date from announcement";
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
<form name = "announcementForm" method = "POST" action = "$_SERVER[PHP_SELF]" >
<h3>Select a checkbox to delete announcement</h3>
$table_values
<input type = "submit" class = "inner_btns" id = "deleteAnnouncement" name = "delete" value = "Delete announcemet" />
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
$query_string = "delete from announcement where id = \"$announcement_id\"";
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
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $heading; ?>
<?php echo $display; ?>
