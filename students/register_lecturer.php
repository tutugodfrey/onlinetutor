<?php
//include database connection function
include "./../includes/db_connect.php";
include "./../includes/functions.php";

session_start();
if(isset($_SESSION["privileged_user"])){
$S_id = $_SESSION["S_id"];
$L_id = $_SESSION["L_id"];

//searching for a lecturers
if(isset($_GET["name"])) {
if(isset($_GET["name_like_nojs"])) {
$name = $_GET["name_like_nojs"];
}
if(isset($_GET["name_like_js"])) {
$name = $_GET["name_like_js"];
}
$query_string = "select id, lastname, firstname from registered_lecturers
		where firstname like \"$name%\" or lastname like \"$name%\" or username like \"$name%\"";

run_query($query_string);
if($row_num2 == 0){
$select_result = "<select><option>Not Found</option></select>";
} 	else 	{
$values = build_array($row_num2);
if($row_num2 == 1){
$values = [$values];
}
$select_result = select_option($values, "", "L_id");

}

if(isset($_GET["name_like_nojs"])) {
$display = <<<block
<form id = "reg_lec_form" method = "GET" action = "register_lecturer.php" >
$select_result
<input type = "submit" id = "viewProfile" name = "view_profile" value = "Profile" />
<input type = "submit" id = "reg_lec" class = "submit_buttons" name = "register" value = "Register" />
</form>
block;
}	else 	{
$display =  $select_result;
}
}





if(isset($_GET["register"])){
$L_id = $_GET["L_id"];
if($L_id == ""){
$display = "<p>Please enter or choose the username of the lecturer to proceed</p>";
}	else		{
//still using the registration database
$query_string = "select id from registered_lecturers where id = \"$L_id\"";
run_query($query_string);
if($row_num2 == 0){
$display = "<p>This Lecturer does not exist in our record</p>";
}	else		{

$value = build_array($row_num2);
$friends_table = "std".$S_id."_friends";
$lecturer_friend = "lec".$L_id."_friends";
$query_string = "select * from $friends_table where friend_id = '$L_id' and user_type = 'l'";
run_query($query_string);
if($row_num2 == 0){
//say this lecturer in the student's table
$query_string = array ("insert into $friends_table values(null, \"$L_id\", \"no\", \"$S_id\", \"l\")", "insert into $lecturer_friend values (null, \"$S_id\", \"no\", \"$S_id\", \"s\")");
run_query($query_string);
if($row_num2 == 0 && $row_num3 == 0){
$display = "<p>the registration could not be process</p>";
}	else	{
$display = "<p>your registration is successful</p>";
}
}	else {
$display  = "<p>your are already registered with this lecturer</p>";
}
}
}
}



}	else {
header("Location:/onlinetutor/login.php");  		//user do not have an active session
exit();
}
?>

<?php echo $display; ?>
