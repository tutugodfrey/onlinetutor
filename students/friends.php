<?php
//include required files
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["privileged_user"])){
$S_id = $_SESSION["S_id"];
$L_id = $_SESSION["L_id"];



if($S_id == ""){
$display = "<p>An error occur. Please go back and try again</p>";

} 	else		{
if(isset($_GET["friends"])){
$friends_table = "std".$S_id."_friends";
$display = "<h1>Your friends</h1>";
//this section will retrieve username waiting for confirmation from the friends_table
$query_string = "select friend_id, user_type from $friends_table where confirm = 'no' and requestor_id != \"$S_id\"";
run_query($query_string);
if($row_num2 == 0){
$display .= "<p>you do not have new friend request. <a id = \"search_friend\" href = \"$_SERVER[PHP_SELF]?new_friends=yes\" > search new friends</a></p>";
}	else	{
$newfriend_ids = build_array($row_num2);
if($row_num2 == 1){
$newfriend_ids = [$newfriend_ids];
}

$new_friends = array ();
foreach($newfriend_ids as $new){
$query_string = "select id, picture, lastname, firstname from registered_students where id = \"$new\"";
run_query($query_string);
if($row_num2 == 0) {
$display .= "<p>Detail of requestor could not be fetched</p>";
}	else 	{
$friend_details = build_array($row_num2);
array_push($new_friends, $friend_details);
}
}		//end foreach
$display .= "<a id = \"search_friend\" href = \"$_SERVER[PHP_SELF]?new_friends=yes\" > search new friends</a></p>";
$display .= "<div id = \"friend_requests\"><p>You have pending friend request </p>";

foreach($new_friends as $new_friend){
$display .= <<<block
<p class = "requests"><br /><img src = "$new_friend[1]" alt = "Photo" />$new_friend[2] $new_friend[3]<a class ="confirm" id ="conf$new_friend[0]" href = "$_SERVER[PHP_SELF]?confirm=yes&coursemate_id=$new_friend[0]" >Confirm</a><a class = "decline" id ="decl$new_friend[0]" href = "$_SERVER[PHP_SELF]?confirm=no&coursemate_id=$new_friend[0]" >Decline</a><a class = "profile" id ="prof$new_friend[0]" href ="/mylecturerapp/common/profile.php?profile=student&user=$new_friend[0]">Profile</a></p></div>
block;
}		//end foreach
}		//end else

$query_string = "select friend_id, user_type from $friends_table where confirm = \"yes\"";
run_query($query_string);
if($row_num2 == 0){
//$display = "<p>you have not added any friends. <a href = \" \">add friends</a></p>";
}	else	{
$friends  = build_array($row_num2);
if($row_num2 == 1){
$friends = [$friends];
}
$old_friends = [];
foreach($friends as $friend){
if($friend[1] === "s"){
$query_string = "select id, picture, lastname, firstname from registered_students where id = \"$friend[0]\"";
$user_type = "s";
}
if($friend[1] === "l"){
$query_string = "select id, picture, lastname, firstname from registered_lecturers where id = \"$friend[0]\"";
$user_type = "l";
}
run_query($query_string);
if($row_num2 === 0){
$display .= "<p>details about a friend could not be fetched</p>";
}	else 	{
$friend_detail = build_array($row_num2);
array_push($friend_detail, $user_type);
array_push($old_friends, $friend_detail);
}
}		//end foreach
$display .= "<p>select a friend and to chat with</p>";
foreach($old_friends as $friends){
$display .= <<<block
<p class = "friends">
<img src = "$friends[1]" alt = "Photo" /><a class = "friend" id = "$friends[0]" href = "$_SERVER[PHP_SELF]?choose_friend=yes&friend_id=$friends[0]&user_type=$friends[4]" >$friends[2] $friends[3]</a></p>
block;
}	//end foreach
}		//end else block
}		//end $_GET["friends"];

if(isset($_GET["confirm"])){
$coursemate_id = trim($_GET["coursemate_id"]);
$confirm = trim($_GET["confirm"]);
if($coursemate_id == "" || $confirm == ""){
$display = "<p>There is an error in the request. please go back and try again</p>";
}	else	{
//process the request to confirm or not
$friends_table = "std".$S_id."_friends";
$coursemate_friends_table = "std".$coursemate_id."_friends";
$my_chat_table = "std".$S_id."_chat";
$coursemate_chat_table = "std".$coursemate_id."_chat";
if($confirm == "no"){
$query_string = array ("delete from $friends_table where friend_id = \"$coursemate_id\"", "delete from $coursemate_friends_table where friend_id = \"$S_id\"");
}
if($confirm == "yes"){
//insert yes in the two tables since it will be the basis for display of names
$query_string = array ("update $friends_table set confirm = \"yes\" where friend_id = \"$coursemate_id\"",
			 "update $coursemate_friends_table set confirm = \"yes\" where friend_id = \"$S_id\"");
}
run_query($query_string);
if($row_num2 == 0 && $row_num3 == 0){
$display = "<p>Your request could not be process now please try again late</p>";
}	else	{
$display = "<p>Your request is process successfully</p>";
}
}
}		//end confirm


if(isset($_GET["choose_friend"])){
$friend_id = trim($_GET["friend_id"]);
$user_type = trim($_GET["user_type"]);
$display = "";
if($friend_id == ""){
$display = "<p>An error has occured. Go back and try again</p>";
}	else	{
if($user_type === "s"){
$query_string = "select picture, lastname, firstname from registered_students where id = \"$friend_id\"";
}
if($user_type === "l"){
$query_string = "select picture, lastname, firstname from registered_lecturers where id = \"$friend_id\"";
}
run_query($query_string);
if($row_num2 == 0){
$display = "<p>The information about this person could not be fetched</p>";
}	else	{
$friend_detail = build_array($row_num2);
//display friend image and will be used to get the friend profile
$display .= "<p class = \"friends\" ><img src = \"$friend_detail[0]\" alt = \"image\" /><a   id = \"choosen\" href = \"/mylecturerapp/common/profile.php?profile=student&user=$friend_id\">$friend_detail[1] $friend_detail[2]</a></p>";

$my_chat_table = "std".$S_id."_chat";
$sender = $S_id;
$receiver = $friend_id;
$query_string = "select sender, post, post_date from $my_chat_table where (sender = \"$sender\" and receiver = \"$receiver\" and user_type = \"$user_type\") or (sender = \"$receiver\" and receiver =\"$sender\" and user_type = \"s\") order by post_date asc limit 20";
run_query($query_string);
if($row_num2 == 0 ){
$chats = "<p>No Posted Messages. Send you friend a message</p>";
$top_post_time = "";
}	else 	{
$chat_messages = build_array($row_num2);
if($row_num2 == 1){
$chat_messages = [$chat_messages];
}
$chats = get_chats($chat_messages, $sender, $receiver);

}
$display .= <<<block
<h1>Chats</h1>
<div id = "chat_div" >   $chats   </div><br />
<form  id = "chat_form" name = "chat_form" method = "POST" action = "$_SERVER[PHP_SELF]" enctype = "multipart/form-data">
<!-- a hook to fetch older post messages -->
<input type = "hidden" id = "topValue" name = "top_post_time" value = "$top_post_time" />
<input type = "hidden" id = "my_friend" name = "friend_id" value = "$friend_id" />
<input type = "hidden" name = "user_type" value = \"$user_type\" />
<div id = "server_reply"></div>
<textarea id = "chat_text_field" rows = "1" cols = "30" name = "post_text"></textarea><br />
<label for = "mediaFile">Photo/Video</label>
<input type = "file" id = "mediaFile" name = "media_file" /><br />
<input type = "submit" id = "send_button" value = "send" name = "send_post" />
<input type = "submit" id = "oldPost" value = "Old messages" name = "old_posts" />
</form>
block;
}
}
}	//end choose friend

if(isset($_POST["send_post"])){
$friend_id = trim($_POST["friend_id"]);
$user_type = $_POST["user_type"];
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
//handle media file if any
if(is_uploaded_file($_FILES["media_file"]["tmp_name"])){
$store = "C:/xampp/htdocs/mylecturerapp/personal_data/lec$user_id/images";

move_uploaded_file($_FILES["media_file"]["tmp_name"], $store.$_FILES["media_file"]["name"]) or die("file could not be uploaded");

$media_url = "/mylecturerapp/personal_data/lec$user_id/images".$_FILES["media_file"]["name"];
}	//end uploaded file


admin_connect();
$post_text = mysqli_real_escape_string($mysqli, $post_text);
//construct the tables names for you and your friend
$my_chat_table = "std".$S_id."_chat";
if($user_type === "l"){
$friend_table = "lec".$friend_id."_chat";
}	else	{
$friend_table = "std".$friend_id."_chat";
}
$sender = $S_id;
$receiver = $friend_id;
//verify the post does not already exist
//$query_string = "select * from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post =  \"$post_text\" and post_date = //date_format(post_date, '%Y %M %D %H %i')";
$query_string = "select * from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post =  \"$post_text\"";
run_query($query_string);
if($row_num2 == 1){
$display = "<p>The massage has already been posted</p>";
}	else	{
$query_string = array("insert into $my_chat_table values(null, \"$sender\", \"$receiver\", \"$post_text\", now()), \"$user_type\"",
			"insert into $friend_table values(null, \"$sender\", \"$receiver\", \"$post_text\", now())", "\s\");
run_query($query_string);
if($row_num2 == 0 && $row_num3 == 0){
$display = "<p>There is a problem posting your request. it might be due to network connection</p>";
}	else	{		//attempt to fetch any lastest post
if($last_post_time == ""){
$query_string = "select sender, post, post_date from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post =  \"$post_text\"";
}	else	{
$query_string = "select sender, post, post_date from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and user_type = \"$user_type\" and post_date > \"$last_post_time\" or sender = \"$receiver\" and receiver =  \"$sender\" and user_type = \"$user_type\" and post_date > \"$last_post_time\"";
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
}
}
}		//end send_post


if(isset($_GET["get_recent_post"])){
$friend_id = trim($_GET["friend_id"]);
$user_type = $_GET["user_type"];
$last_post_time = trim($_GET["last_post_time"]);
if($friend_id == ""){
$display ="<p>most recent chats could not be display</p>";
}	else	{
if($last_post_time == ""){
$display = "<p>Last post time is not set. can't fetch recent chats messages</p>";
}	else	{
//construct the tables names for you and your friend
$my_chat_table = "std".$S_id."_chat";
$friend_table = "std".$friend_id."_chat";
$sender = $S_id;
$receiver = $friend_id;
$query_string = "select sender, post, post_date from  $my_chat_table where sender = \"$sender\" and receiver =  \"$receiver\" and post_date > \"$last_post_time\" or sender = \"$receiver\" and receiver =  \"$sender\" and post_date > \"$last_post_time\"";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>No new chats messages</p>";
}	else	{
$chats = build_array($row_num2);
if($row_num2 == 1){
$chats = [$chats];
}
$display = get_chats($chats, $sender, $receiver);
}
}
}
}	//end get_recent_post


if (isset ($_POST["old_posts"])) {
$top_post_time = $_POST["top_post_time"];
$friend_id = trim($_POST["friend_id"]);
if($friend_id == ""){
$display = "<p>Old post chats could not be fetched. Friend id not set </p>";
}	else 	{
if($top_post_time == ""){
$display = "<p>Old post could not be fetched. Top post time not set</p>";
}	else 	{
$my_chat_table = "std".$S_id."_chat";
$sender = $S_id;
$receiver = $friend_id;
$query_string = "select sender, post, post_date from $my_chat_table where( (sender = \"$sender\" and receiver = \"$receiver\") or (sender = \"$receiver\" and receiver =\"$sender\") ) and (post_date < \"$top_post_time\") order by post_date asc limit 20";
run_query($query_string);
if($row_num2 == 0 ){
$display = "<p>No more post</p>";
}	else 	{
$chats = build_array($row_num2);
if($row_num2 == 1){
$chats = [$chats];
}
$display = get_chats($chats, $sender, $receiver);
}
}
}
}




if (isset($_GET["new_friends"])) {
//html for searching for friends
$display  = <<<block
<form name = "new_friend" method = "POST" action = "$_SERVER[PHP_SELF]" >
<label for = "name_field">Enter name</label>
<input type = "text" id = "name_field" name = "name" />
<input type = "submit" id = "search_button" name = "search_friend" value = "search_friend"/>
</form>
<div id = "matched_friend_list"></div>
block;
}	//end new_friends


if (isset($_POST["search_friend"])) {
$name =  trim($_POST["name"]);
$user_type = $_POST["user_type"];
if($user_type == "s"){
$query_string = array "select id, lastname, firstname from registered_students
		where (firstname like \"$name%\" or lastname like \"$name%\" or username like \"$name%\") and (id != \"$user_id\")";
}

/*i  will need to format the request so tha existing friends won't return    */
run_query($query_string);
if($row_num2 == 0){
$result = "<select><option>Not Found</option></select>";
} 	else 	{
$friends = build_array($row_num2);
if($row_num2 == 1){
$friends = [$friends];
}
$result = select_option($friends, "Names", "coursemates");
}
$display = <<<block
<p> Matching list</p>
<form name = "new_friend" method = "POST" action = "/onlinetutor/student_interface/coursemates.php" >
$result
<input type = "submit" id = "addFriend" name = "add_friend" value = "Add" />
</form>
block;
}



}		//end $S_id else block
}	else {
header("Location:/onlinetutor/login.php");  		//user do not have an active session
exit();
}
?>





<?php echo $display; ?>
