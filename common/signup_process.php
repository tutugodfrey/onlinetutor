<?php
//include database connection file
include "./../includes/db_connect.php";
include "./../includes/functions.php";
$doc_root = $_SERVER["DOCUMENT_ROOT"];

if(empty($_POST["agreement"])){
	$display = "<p>Please agree to our term of use and privacy policy<a href = \"signup.php\"> &lt;&lt; back</a></p>";
}	else {
	$title_display = "registration";
	$success_string = "<p>Your rigistration is successful <a href =\"login.php\" >Log In</a>";

	//gather the require data from user
	//clean up the user input
	if(isset($_POST["user_type"])){
		$user_type = $_POST["user_type"];
	} else {
		$user_type = "student";
	}

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
	if($lastname == "" || $firstname == "" || $gender == "select gender" || $gender == "" ||
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
						//handle uploaded photo
						mkdir($doc_root."/personal_data/user$user_id"); //create a directory to store the url to the picture
						mkdir($doc_root."/personal_data/user$user_id/images"); //create a directory to store other images that user will use for other purposes
						mkdir($doc_root."/personal_data/user$user_id/videos"); //create a directory to store the images that the user will use for other purposes
						mkdir($doc_root."/personal_data/user$user_id/profile_pix"); // dirctory for user's profile-pix
						$store = $doc_root."/personal_data/user$user_id/profile_pix";
						if (is_uploaded_file($_FILES["photo"]['tmp_name'])){
								//move the file from the tempatory folder to a local directory
							move_uploaded_file($_FILES["photo"]['tmp_name'],      
							"$store/".$_FILES["photo"]['name']) or die("Couldn't move file"); //can also be $store."/".
							$picture = "/personal_data/user".$user_id."/profile_pix/".$_FILES["photo"]['name'];	//save the url to the database
							//echo "file was moved!";
						}  	else	 {
							//the user does not upload a photo so use the default photo
							$picture = "/personal_data/defaultpix.png";
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
				}	else	{
					//display message that the database already exist
					$display = "A record with this already exist with $firstname $lastname $phone_number! Confirm is you and <a href =\"login.php\" >Log In</a>";
				}
			}
		}
	}
}

?>

<?php echo $display; ?>

