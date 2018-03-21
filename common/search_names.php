<?php
//include function to connect to db
include "./../includes/db_connect.php";
include "./../includes/functions.php";
include "./../includes/server-funcs.php";
include "./../includes/views.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$owner_friends = "user".$owner_id."_friends";



if (isset($_GET["new_friends"])) {
//html for searching for friends
$display  = <<<block
<div class = "form-group">
<form name = "new_friend" method = "GET" action = "$_SERVER[PHP_SELF]" >
<label for = "name_field">Enter name</label>
<input type = "text" id = "new_friend_name" name = "name_like" placeholder = "Search friends" />
<input type = "submit" class = "btn btn-primary" id = "search_frnd" name = "search_friends" value = "Search Friend"/>
</form> 
</div><br />
<div id = "matched_friend_list"></div>
block;
}	//end new_friends






if(isset($_GET["search_friends"]) || isset($_GET["search_lecturers"])){
	if(isset($_GET["name_like"])) {
		$name = trim($_GET["name_like"]);
		if($name == ""){
			//$display = "<p>please type a name to find people</p>";
			$display = "p";
		}	else	{
			if(isset($_GET["search_friends"])){
				$query_string = "select id, lastname, firstname from registered_users 
						where firstname like \"$name%\" or lastname like \"$name%\" or username like \"$name%\"";
				$register = "<input type = \"submit\" id = \"add_friend\" class = \"btn btn-success btn-sm\" name = \"register_friend\" value = \"Register\" />";
			}	else if(isset($_GET["search_lecturers"])){
				$query_string = "select id, lastname, firstname from registered_users 
						where (firstname like \"$name%\" or lastname like \"$name%\" or username like \"$name%\") and user_type = \"lecturer\"";
				$register = "<input type = \"submit\" id = \"reg_lec\" class = \"btn btn-success btn-sm\" name = \"register_lecturer\" value = \"Register\" />";
			//	$register = "";
			}
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<select><option>Not Found</option></select>";
			} 	else 	{
				$values = build_array($row_num2);
				if($row_num2 == 1){
					$values = [$values];
				}
				$select_default = [0, "select", "one"];
				array_unshift($values, $select_default);
				$display = select_option($values, "friend_id", "user_id", "form-control form-control-sm", "sr-only");
				$display .= $register;
				$display = <<<block
				<form name = "reg_lecturer" id = "reg_lec_form" class= "form-inline" method = "POST" action = "/common/profile.php" >
					$display
				</form>
block;
			}
		}
	}
}

} 	else	{		//if no active session
	$display =  "<p>You do not have an active user session. Please go back and login in</p>";
}

?>


<?php echo $display; ?>