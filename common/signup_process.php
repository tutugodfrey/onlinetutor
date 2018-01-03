<?php
//include database connection file
include "db_connect2.php";
include "function2.php";


if(empty($_POST["agreement"])){
$display = "<p>Please agree to our term of use and privacy policy<a href = \"signup.php\"> &lt;&lt; back</a></p>";
}	else {
$title_display = "registration";
$success_string = "<p>Your rigistration is successful <a href =\"login.php\" >Log In</a>";

//gather the require data from user
//clean up the user input
$user_type = $_POST["user_type"];

admin_connect();
$lastname = mysqli_real_escape_string($mysqli, trim($_POST["lastname"]));
$firstname = mysqli_real_escape_string($mysqli, trim($_POST["firstname"]));
$institution = mysqli_real_escape_string($mysqli, trim($_POST["institution"]));
$faculty = mysqli_real_escape_string($mysqli, trim($_POST["faculty"]));
$department = mysqli_real_escape_string($mysqli, trim($_POST["department"]));
$discipline = mysqli_real_escape_string($mysqli, trim($_POST["discipline"]));
$matric_no = mysqli_real_escape_string($mysqli, trim($_POST["matric_no"]));
$title = mysqli_real_escape_string($mysqli, trim($_POST["title"]));
$email = mysqli_real_escape_string($mysqli, trim($_POST["email"]));
$security_question = mysqli_real_escape_string($mysqli, trim($_POST["security_question"]));
$security_answer = mysqli_real_escape_string($mysqli, trim($_POST["security_answer"]));
$phone_number = mysqli_real_escape_string($mysqli, trim($_POST["phone_number"]));
$gender = trim($_POST["gender"]);
$bday = $_POST["day"];
$bmon = $_POST["mon"];
$byear = $_POST["year"];
$username = trim($_POST["username"]);
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];
$agreement_status = strtolower($_POST["agreement"]);
$date_of_birth = "$byear-$bmon-$bday";


//verify that all required field are entered corrected
if($lastname == "" || $firstname == "" || $gender == "select gender" || $gender == "" || $user_type == "" ||
	$institution == "" || $faculty == "" || $department == "" || $title == "" || $bday == "bday" || $phone_number == "" ||
	$bmon == "bmon" || $byear == "byear" || $email == "" || $username == "" || $password1 == "" || 
	$password1 == "password1" || $password2 == "" || $password2 == "password2" || $agreement_status == ""){
$display = "<p>Please fill out all required field to sign up<a href = \"signup.php\" > &lt;&lt;Back</a></p>";
} 	else 	{

if($password1 != $password2){
$display = "<p>The password you enter does not match! <a href = \"signup.php\">&lt;&lt; Return</a> 
		and enter a matching password</p>";
}	else	{
if($agreement_status == "disagree"){
$display = "<p>Oh! we can not proceed with the processing your registration because you have not agree to your term
		of use and privcy policy</p>";
}	else	{
if($user_type === "student" && ($discipline === "" || $matric_no === "")){
echo "<p>Your matriculation number or discipline is not set! <a href = \"signup.php\">&lt;&lt; Please return</a> 
		and fill all required fields</p>";
exit;
}	elseif($user_type === "lecturer") {
$discipline = ""; $matric_no = "";
}


//check the user does not already exist
$query_string = "select * from registered_users where firstname = '$firstname' and lastname = '$lastname'";


run_query($query_string);
if($row_num2 == 0){





$query_string = "insert into registered_users values (null, \"$matric_no\", \"$lastname\", \"$firstname\", \"$gender\", date(\"$date_of_birth\"),
 		\"$title\", \"$institution\", \"$faculty\", \"$department\", \"$discipline\", null, \"$agreement_status\", \"$email\", \"$phone_number\", \"$username\", sha1(\"$password1\"), \"$security_question\", \"$security_answer\", \"$user_type\")";

run_query($query_string);
if($row_num2 == 0 ) {
$display = "<p>Your registration could not be completed. Please try again later</p>";
}	else		{
$user_id = mysqli_insert_id($mysqli);
$user_folder = "user".$user_id;
$chat_table = "user".$user_id."_chat";
$friends_table = "user".$user_id."_friends";

if($user_type == "student"){
$note_table = "user".$user_id."_note";
}

if($user_type == "lecturer"){
$lecturer_db = "lec".$user_id;



$query_string = array (
"CREATE TABLE $chat_table (post_id int not null primary key auto_increment, sender int not null, receiver int not null, post text not null, media_url varchar(120), post_date datetime not null)",
"CREATE TABLE $friends_table (id int not null primary key auto_increment, friend_id int not null, confirm enum('yes', 'no'), requestor_id int not null, user_type varchar(10))",
"CREATE DATABASE $lecturer_db",
"USE $lecturer_db",
"CREATE TABLE COURSES(COURSE_ID TINYINT NOT NULL PRIMARY KEY AUTO_INCREMENT, COURSE_CODE VARCHAR(15) NOT NULL, COURSE_TITLE VARCHAR(100) NOT NULL, COURSE_DESCRIPTION TEXT, UNIT TINYINT NOT NULL)",
"CREATE TABLE REGISTERED_COURSES (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT, STUDENT_ID INT NOT NULL,  COURSE_ID TINYINT NOT NULL, COURSE_STATUS VARCHAR (15) NOT NULL)",
"CREATE TABLE TEST (ID TINYINT NOT NULL PRIMARY KEY AUTO_INCREMENT, TEST_ID TINYINT, COURSE_ID TINYINT NOT NULL, DURATION TIME NOT NULL, DEADLINE DATETIME,  MARK TINYINT NOT NULL, NO_OF_QUESTIONS TINYINT NOT NULL, TEST_TYPE ENUM('TEST', 'EXAM') NOT NULL, TEST_STATUS ENUM('OPENED', 'CLOSED') NOT NULL)",
"CREATE TABLE QUESTIONS ( QUESTION_ID TINYINT NOT NULL PRIMARY KEY AUTO_INCREMENT, COURSE_ID TINYINT NOT NULL, TEST_ID TINYINT NOT NULL, QUESTIONS TEXT NOT NULL, OPTION_A VARCHAR (100), OPTION_B VARCHAR (100), OPTION_C VARCHAR (100), OPTION_D VARCHAR (100), CORRECT_OPTION ENUM('A', 'B', 'C', 'D') )",
"CREATE TABLE SCORE_BOARD (SCORE_ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,  COURSE_ID TINYINT NOT NULL, STUDENT_ID INT NOT NULL, TEST_ID TINYINT, DISCUSSION_ID TINYINT, SCORE TINYINT NOT NULL, SCORE_TYPE ENUM('TEST', 'EXAM', 'DISCUSSION') NOT NULL)",
"CREATE TABLE DISCUSSION ( DISCUSSION_ID TINYINT NOT NULL PRIMARY KEY AUTO_INCREMENT,  COURSE_ID TINYINT NOT NULL, DISCUSSION_TOPIC TEXT NOT NULL, POST_DATE DATETIME NOT NULL, TYPE ENUM('OPEN', 'CLOSE') NOT NULL )",
"CREATE TABLE POST ( POST_ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT, STUDENT_ID INT NOT NULL, DISCUSSION_ID TINYINT NOT NULL, COURSE_ID TINYINT NOT NULL, POST_DATE DATETIME NOT NULL, POST_TEXT TEXT NOT NULL, TYPE ENUM('OPEN', 'CLOSE') NOT NULL, GRADED ENUM('YES', 'NO') NOT NULL )",
"CREATE TABLE ANNOUNCEMENT( ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT, POST_MESSAGE TEXT NOT NULL, POST_DATE DATETIME NOT NULL)",
"CREATE TABLE NOTE  (id int not null primary key auto_increment, course_id tinyint not null, title varchar (100), note text not null, note_date datetime not null)",
"CREATE TABLE VIDEOS (ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT, COURSE_ID TINYINT NOT NULL, VIDEO_URL VARCHAR (150) NOT NULL, VIDEO_NAME VARCHAR  (100) NOT NULL, VIDEO_CAPTION VARCHAR (100) NOT NULL)"
);
}
if($user_type == "student"){
$query_string = array (
"CREATE TABLE $chat_table (post_id int not null primary key auto_increment, sender int not null, receiver int not null, post text not null, media_url varchar(120), post_date datetime not null)",
"CREATE TABLE $friends_table (id int not null primary key auto_increment, friend_id int not null, confirm enum('yes', 'no'), requestor_id int not null, user_type varchar(10))",
"CREATE TABLE $note_table  (id int not null primary key auto_increment, course_id tinyint not null, title varchar (100), note text not null, note_date datetime not null)"
);
}

//run the queries
run_query($query_string);
if($row_num2 === 0 ) {
$display = "<p>An error has occured please try again</p>";
}	else {

//handle uploaded photo
mkdir("C:/xampp/htdocs/mylecturerapp/personal_data/user$user_id"); //create a directory to store the url to the picture
mkdir("C:/xampp/htdocs/mylecturerapp/personal_data/user$user_id/images"); //create a directory to store other images that user will use for other purposes
mkdir("C:/xampp/htdocs/mylecturerapp/personal_data/user$user_id/videos"); //create a directory to store the images that the user will use for other purposes
mkdir("C:/xampp/htdocs/mylecturerapp/personal_data/user$user_id/profile_pix"); //create a directory to store the images that the user will use for other purposes
$store = "C:/xampp/htdocs/mylecturerapp/personal_data/user$user_id/profile_pix";



if (is_uploaded_file($_FILES["photo"]['tmp_name'])){
	//move the file from the tempatory folder to a local directory
move_uploaded_file($_FILES["photo"]['tmp_name'],      
"$store/".$_FILES["photo"]['name']) or die("Couldn't move file"); //can also be $store."/".
$picture = "/mylecturerapp/personal_data/".$user_id."/profile_pix/".$_FILES["photo"]['name'];	//save the url to the database
//echo "file was moved!";
}  	else	 {
//the user does not upload a photo so use the default photo
$picture = "/mylecturerapp/personal_data/defaultpix.png";
}

$query_string = "update registered_users set picture = \"$picture\" where id = \"$user_id\"";
run_query($query_string);
if($row_num2 == 0) {
$display = "<p>The registration is not complete</p>";
}	else {
$display = $success_string;
//write an email confirmation script to confirm registration and grant necessary access to the database to the lecturer
}
}
}
}	else	{
//display message that the database already exist
$display = "A record with this already exist with $firstname $lastname $phone_number! Confirm is you and <a href =\"login.php\" >Log In</a>";
}
}
}
}
}

?>

<!DOCTYPE html>
<html>
<head>
<title><?php $title_display ?></title>
</head>
<body>
<?php echo $display; ?>
</body>
</html>
