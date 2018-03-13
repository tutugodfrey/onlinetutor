<?php
//include include required files
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

		if (isset ($_GET["view_videos"])) {
			//select the course the student is offers
			$course_ids = registered_course_ids($owner_id, $lec_id);
			if(empty($course_ids)){
				$display = "<p> You have not registered any course with this lecturer</p>";
			}	else	{
				$videos = foreach_iterator2("get_videos", $course_ids, $lec_id, 1);
				if(empty($videos)){
					$display = "<p>No videos have be uploaded for the courses you are offering</p>";
				}	else	{
					$display = "";
					foreach($videos as $video){
						$sources = multi_source($video[1]);
						$display .=  <<<block
						<h1>video for $video[4] </h1>
						<h1>$video[3]</h1>
						<caption>$video[2]</caption>
						<video class = "video$video[0]" width ="350" height = "300" controls= "true">
						$sources
						</video><br/>
						<a href = "$_SERVER[PHP_SELF]?download=yes&path=$video[1]"> Download Video</a>
block;
					}	//foreach
				}
			}
		}

		if(isset($_GET["download"])) {
			//file_download($path);
			$path = $_GET["path"];
			$path_array = explode ("/", $path);
			$filename =  $path_array[7];
			file_download($path, $filename);
		}

	} else {
		$display = "Please Select a lecturer";
	}
	
}	else {
header("Location:/login.php");  		//user do not have an active session
exit();
}
?>


<?php echo $display; ?>
