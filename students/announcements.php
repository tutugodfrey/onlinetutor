<?php 
//include required files
include "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];
$heading = "";
if(isset($_GET["announcements"])){
	$query_string = "select post_message, post_date from announcement";
	run_query($query_string, $lecturer_db);
	if($row_num2 == 0){
		$display = "<p>No Announcement Now. Keep Checking</p>";
	}	else	{
		$announcements = build_array($row_num2);
		if($row_num2 == 1){
			$announcements = [$announcements];
			$heading = "<h1>Announcement</h1>";
		}
		if($row_num2 > 1){
			$heading = "<h1>Announcements</h1>";
		}
		$fields = array ("Announcement", "Post Date");
		array_unshift($announcements, $fields);
		$table_values = mytable($announcements);
		$display = $table_values;
	}
}

}	else {			
header("Location:/onlinetutor/common/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $heading; ?>
<?php echo $display; ?>
