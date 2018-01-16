<?php 
//include include required files
include "./../includes/db_connect.php";
include "./../includes/functions.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$lecturer_db = $_SESSION["lecturer_db"];



if (isset($_GET["add_video"])) {

$query_string = "select course_id, course_code from courses";
run_query($query_string, $lecturer_db);
if ($row_num2 == 0 ) {
$display = "<p>You have not saved any course</p>";
}	else {
$courses = build_array($row_num2);
if($row_num2 == 1){
$courses = [$courses];
}
$select_result = select_option ($courses, "course code", "course_id", "form-control");
//write html for video upload
$display = <<< block
<h1>Video Upload</h1>
<p> Use the form below to uplaod a video </p>
<form name = "videoForm" class = "form-group" method = "POST" action = "$_SERVER[PHP_SELF]" enctype = "multipart/form-data">
$select_result <br />
<label for = "video_caption" >Video Caption</label>
<input type = "text" id = "videoCaption" class = "form-control" name = "video_caption" size = "40" /> <br />
<label for = "vidoe">Add Video</label>
<input type = "file" id = "videoFile" class = "form-control" multiple = multiple name = "video_file"  /> <br />
<!-- multiple videos can be uploaded to the server -->
<input type = "submit" class = "btn btn-success" id = "uploadVideo" name = "upload_video" value = "Upload Video" />
<input type = "submit" class = "btn btn-success" id = "viewUploadedVideos" name = "uploaded_videos" value = "View Uploaded Videos" />
</form>
block;
}
}


if (isset($_POST["upload_video"])) {
//foreach($_FILES["video_file"]){
if (is_uploaded_file($_FILES["video_file"]['tmp_name'])){
$store = "C:/xampp/htdocs/mylecturerapp/personal_data/user$owner_id/videos/".$_FILES["video_file"]["name"]; 	//directory to store the file

move_uploaded_file($_FILES["video_file"]['tmp_name'], $store) or die("Couldn't move file"); //can also be $store."/".
echo "file was moved!";
$video_url = "/mylecturerapp/personal_data/user$owner_id/videos/".$_FILES["video_file"]["name"]; 	//relative url for the file to save in db
$video_name =  $_FILES["video_file"]['name'];

//the file is upload fine! get other data
$course_id = $_POST["course_id"];
admin_connect();
$video_caption = mysqli_real_escape_string($mysqli, trim($_POST["video_caption"]));	//clean the data
	//write query_string to databas
$query_string = "insert into videos values(null, \"$course_id\", \"$video_url\", \"$video_name\", \"$video_caption\" )";
run_query($query_string, $lecturer_db);
if ($row_num2 == 1) {
$display =  "<p>Video successfully uploaded</p>";
}	else	{
$display =  "<p>The video could not be saved. Please try again later</p>";
}
} else {
$display =  "<p>No file found.</p>";
}
//}
}


if (isset ($_POST["uploaded_videos"])) {
$videos = get_videos(" ", 2, $lecturer_db);
if(empty($videos)){
$display = "<p>You have not uploaded any video</p>";
}	else {
$display = "";
foreach ($videos as $video) { //value return by build_array()
//get  the fill extention
$sources = multi_source($video[1]);

$display .= <<<block
<h1>video for $video[4] </h1>
<h1>$video[3]</h1>
<caption>$video[2]</caption>
<video class = "videos video$video[0]" width ="350" height = "300" controls= "true">
 $sources
</video><br/>
block;
}		//end foreach
}
}



}	else {			
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}
?>

<?php 
echo $display;
?>