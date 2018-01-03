<?php
//include required files
include  "db_connect2.php";
include "function2.php";

session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$friends_table = "user".$owner_id."_friends";
$my_chat_table = "user".$owner_id."_chat";
$owner_class  = $_SESSION["class"]; ;


if($owner_id == ""){
$display = "<p>An error occur. Please go back and try again</p>";
} 	else		{ 

if(isset($_GET["friends"])){
$display = "<h1>Your friends</h1>";

$query_string = "select friend_id from $friends_table where confirm = 'no' and requestor_id != \"$owner_id\"  and user_type is null";
run_query($query_string);
if($row_num2 == 0){
$display .= "<p>you do not have new friend request. <a id = \"search_friend\" href = \"/mylecturerapp/common/search_names.php?new_friends=yes\" > search new friends</a></p>";
}	else	{
$friends_id = build_array($row_num2);
if($row_num2 == 1){
$friends_id = [$friends_id];
}

$friends_array = array ();
foreach($friends_id as $friend_id){
$query_string = "select id, picture, lastname, firstname from registered_users where id = \"$friend_id\"";
run_query($query_string);
if($row_num2 == 0) {
$display .= "<p>Detail of requestor could not be fetched</p>";
}	else 	{
$friend_details = build_array($row_num2);
array_push($friends_array, $friend_details);
}
}	// end foreach
$display .= "<a id = \"search_friend\" href = \"/mylecturerapp/common/search_names.php?new_friends=yes\" > search new friends</a></p>";
$display .= "<div id = \"friend_requests\"><p>You have pending friend request </p>";





foreach($friends_array as $new_friend){

$display .= <<<block
<p class = "requests"><br /><img src = "$new_friend[1]" alt = "Photo" />$new_friend[2] $new_friend[3]<a class ="confirm" id ="conf$new_friend[0]" href = "/mylectuererapp/common/profile.php?confirm=yes&friend_id=$new_friend[0]" >Confirm</a><a class = "decline" id ="decl$new_friend[0]" href = "mylectuererapp/common/profile.php?confirm=no&friend_id=$new_friend[0]" >Decline</a><a class = "profile" id ="prof$new_friend[0]" href ="/mylecturerapp/common/profile.php?profile=yes&&user_id=$new_friend[0]">Profile</a></p></div>
block;
}		//end foreach

}	//end select new friend
/*
$query_string = "select registered_users.id, registered_users.picture, registered_users.firstname, registered_users.lastname where registered_users.id = \"$friends_table.friend_id\" and $friends_table.confirm = \"yes\"" ; */

$query_string = "select friend_id from $friends_table where confirm = \"yes\"";
run_query($query_string);
if($row_num2 == 0){
//$display = "<p>you have not added any friends. <a href = \" \">add friends</a></p>";
}	else	{
$friends_id  = build_array($row_num2);
if($row_num2 == 1){
$friends_id = [$friends_id];
}


$old_friends = [];
foreach($friends_id as $friend_id){

$query_string = "select id, picture, lastname, firstname from registered_users where id = \"$friend_id\"";


run_query($query_string);
if($row_num2 === 0){
$display .= "<p>details about a friend could not be fetched</p>";
}	else 	{
$friend_detail = build_array($row_num2);
array_push($old_friends, $friend_detail);
}
}		//end foreach

$display .= "<p>select a friend and to chat with</p>";
foreach($old_friends as $friends){
$display .= <<<block
<p class = "friends">
<img src = "$friends[1]" alt = "Photo" /><a class = "friend" id = "$friends[0]" href = "$_SERVER[PHP_SELF]?choose_friend=yes&friend_id=$friends[0]" >$friends[2] $friends[3]</a></p>
block;
}	//end foreach



}		//end else for selecting existing friends

}	//end friend


if(isset($_GET["choose_friend"])){
$friend_id = trim($_GET["friend_id"]);
if($friend_id == ""){
$display = "<p>An error has occured. Go back and try again</p>";
}	else	{
$query_string = "select picture, lastname, firstname from registered_users where id = \"$friend_id\"";
$friend_type = "student";

run_query($query_string);
if($row_num2 == 0){
$display = "<p>The information about this person could not be fetched</p>";
}	else	{
$friend_detail = build_array($row_num2);
//display friend image and will be used to get the friend profile
$display = "<p class = \"friends\" ><img src = \"$friend_detail[0]\" alt = \"image\" /><a   id = \"choosen\" href = \"/mylecturerapp/common/profile.php?profile=yes&user_id=$friend_id\">$friend_detail[1] $friend_detail[2]</a></p>";

}		//end query to select a friend info

$sender = $owner_id;
$receiver = $friend_id;
$query_string = "select sender, post, media_url, post_date from $my_chat_table where (sender = \"$sender\" and receiver = \"$receiver\" ) or (sender = \"$receiver\" and receiver =\"$sender\") order by post_date desc limit 20";
run_query($query_string);
if($row_num2 == 0 ){
$chats = "<p>No Posted Messages. Send you friend a message</p>";
$top_post_time = "";
}	else 	{
$chat_messages = build_array($row_num2);
if($row_num2 == 1){
$chat_messages = [$chat_messages];
}

$chat_messages = array_reverse($chat_messages);
$chats = get_chats($chat_messages, $sender, $receiver);
	//end query to select post
}
$display .= <<<block
<div id = "chat_msg_div" >   $chats   </div><br />
<form  id = "chat_form" name = "chat_form" method = "POST" action = "$_SERVER[PHP_SELF]" enctype = "multipart/form-data">
<!-- a hook to fetch older post messages -->
<input type = "hidden" id = "topValue" name = "top_post_time" value = "$top_post_time" />
<input type = "hidden" id = "my_friend" name = "friend_id" value = "$friend_id" />
<div id = "server_reply"></div>
<textarea id = "chat_text_field" rows = "1" cols = "30" name = "post_text"></textarea><br />
<label for = "chatMediaFile">Uploads</label>
<input type = "file" id = "chatMediaFile" name = "media_file"  multiple = "multiple"/><br />
<input type = "submit" class = "inner_btns" id = "send_chat" value = "send" name = "send_post" />
<input type = "submit" class = "nojs_show inner_btns" id = "oldPost" value = "Old messages" name = "old_posts" />
</form>
block;
}
}		//end choose friend

if(isset($_POST["send_post"])){
$friend_id = trim($_POST["friend_id"]);

$post_text = trim($_POST["post_text"]);
if(isset($_POST["last_post_time"])){
$last_post_time = trim($_POST["last_post_time"]);
}	else 	{
$last_post_time = "";
}

if($friend_id == ""){
$display = "<p>A Error occur. Please go back and try again</p>";
} 	else	{
if($post_text == ""){
$display = "<p>Please type a message to post</p>";
}	else	{

$image_type = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/bmp"];
$video_type = ["video/mp4", "audio/mp3", "audio/mpeg"];
$media_url = "";

if(is_uploaded_file($_FILES["media_file"]["tmp_name"])){

//handle media file if any
$media_type = $_FILES["media_file"]["type"];
if(in_array($media_type, $image_type)){
$file_type_indicator = "image";
$store = "C:/xampp/htdocs/mylecturerapp/personal_data/user$owner_id/images/";
$type = "images";
}
if(in_array($media_type, $video_type)){
if(strstr($media_type, "audio")){
$file_type_indicator = "-audio";
}	else if (strstr($media_type, "video")){
$file_type_indicator = "-video";
}
$store = "C:/xampp/htdocs/mylecturerapp/personal_data/user$owner_id/videos/";
$type = "videos";
}
echo $media_type;

move_uploaded_file($_FILES["media_file"]["tmp_name"], $store.$_FILES["media_file"]["name"]) or die("file could not be uploaded");

$media_url .= "\t/mylecturerapp/personal_data/user$owner_id/$type/".$_FILES["media_file"]["name"]. "$file_type_indicator";
}	else {		//end foreach
$media_url = '';
}		//end uploaded file

admin_connect();
$post_text = mysqli_real_escape_string($mysqli, $post_text);

$friend_chat = "user".$friend_id."_chat";


$sender = $owner_id;
$receiver = $friend_id;
$query_string = "select * from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post =  \"$post_text\"";

run_query($query_string);
if($row_num2 == 1){
$display = "<p>The massage has already been posted</p>";
}	else	{
$query_string = array("insert into $my_chat_table values(null, \"$sender\", \"$receiver\", \"$post_text\", \"$media_url\", now())", 
		"insert into $friend_chat values(null, \"$sender\", \"$receiver\", \"$post_text\", \"$media_url\", now())");
run_query($query_string);
if($row_num2 == 0 && $row_num3 == 0){
$display = "<p>There a problem posting your request. it might be due to network connection</p>";
}	else	{
if($last_post_time == ""){
$query_string = "select sender, post, media_url, post_date from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post =  \"$post_text\"";
}	else	{
$query_string = "select sender, post, media_url, post_date from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post_date > \"$last_post_time\" or sender = \"$receiver\" and receiver =  \"$sender\" and post_date > \"$last_post_time\"";
}

run_query($query_string);
if($row_num2 == 0) {
$display = "<p>No new chat messages</p>";
}	else	{
$chats = build_array($row_num2);
if($row_num2 == 1){
$chats = [$chats];
}
$display = get_chats($chats, $sender, $receiver);

}
}
}
}		//post_text exception
}	//end friend_id exception
}		//end send_post



if(isset($_GET["get_recent_post"])){
$friend_id = trim($_GET["friend_id"]);

$last_post_time = trim($_GET["last_post_time"]);
if($friend_id == "" || $last_post_time == ""){
$display ="<p>An error occur in the script. most recent chats could not be display</p>";
}	else	{
$sender = $owner_id;
$receiver = $friend_id;

$query_string = "select sender, post, media_url, post_date from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post_date > \"$last_post_time\" or sender = \"$receiver\" and receiver =  \"$sender\" and post_date > \"$last_post_time\"";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>No new chats messages</p>";
$display = "";
}	else	{
$chats = build_array($row_num2);
if($row_num2 == 1){
$chats = [$chats];
}
$display = get_chats($chats, $sender, $receiver);
}
}
}	//end get_recent_post


if (isset ($_POST["old_posts"])) {
$top_post_time = $_POST["top_post_time"];
$friend_id = trim($_POST["friend_id"]);

if($friend_id == "" || $top_post_time == ""){
$display = "<p>Old post chats could not be fetched. Friend id not set </p>";
}	else 	{
$sender = $owner_id;
$receiver = $friend_id;
$query_string = "select sender, post, media_url, post_date from $my_chat_table where( (sender = \"$sender\" and receiver = \"$receiver\") or (sender = \"$receiver\" and receiver =\"$sender\") ) and (post_date < \"$top_post_time\") order by post_date asc limit 20";

run_query($query_string);
if($row_num2 == 0 ){
$display = "<p>No more post</p>";
}	else 	{
$chats = build_array($row_num2);
if($row_num2 == 1){
$chats = [$chats];
}
header("Content-Type:text/xml");
$display = get_chat_xml($chats, $sender, $receiver);
}

}
}	//end old_post




}
}	else {
header("Location:/mylecturerapp/login.php");  		//user do not have an active session
exit();
}

?>




<?php 
echo $display;
?>