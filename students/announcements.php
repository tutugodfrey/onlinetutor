<?php 
//include required files
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

		if(isset($_GET["announcements"])){
			$query_string = "select post_message, post_date from announcements where lec_id = \"$lec_id\"";
			run_query($query_string);
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

	} else {
		$display = "Please Select a lecturer";
	}

}	else {			
header("Location:/common/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $heading; ?>
<?php echo $display; ?>
