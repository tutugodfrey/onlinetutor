<?php
//include function to connect to db
include "./../includes/db_connect.php";
include "./../includes/functions.php";


session_start();
if(isset($_SESSION["owner_id"])){
$owner_id = $_SESSION["owner_id"];
$owner_friends = "user".$owner_id."_friends";



if (isset($_GET["new_friends"])) {
//html for searching for friends
$display  = <<<block
<form name = "new_friend" method = "GET" action = "$_SERVER[PHP_SELF]" >
<label for = "name_field">Enter name</label>
<input type = "text" id = "new_friend_name" name = "name_like_nojs" placeholder = "Search friends" />
<input type = "submit" class = "nojs_show" id = "search_frnd" name = "search_friends" value = "search_friend"/>
</form> <br />
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
				$register = "<input type = \"submit\" id = \"reg_lec\" class = \"btn btn-success btn-sm\" name = \"register_friend\" value = \"Register\" />";
			}		//end search_friends
			if(isset($_GET["search_lecturers"])){
				$query_string = "select id, lastname, firstname from registered_users 
						where (firstname like \"$name%\" or lastname like \"$name%\" or username like \"$name%\") and user_type = \"lecturer\"";
				$register = "<input type = \"submit\" id = \"reg_lec\" class = \"btn btn-success btn-sm\" name = \"register_lecturer\" value = \"Register\" />";
			}
			run_query($query_string);
			if($row_num2 == 0){
				$display = "<select><option>Not Found</option></select>";
			} 	else 	{
				$values = build_array($row_num2);
				if($row_num2 == 1){
					$values = [$values];
				}
				$display = select_option($values, "", "user_id", "form-control form-control-sm");
			}
		}
	}
}

} 	else	{		//if no active session
echo  "<p>You do not have an active user session. Please go back and login in</p>";
}

?>


<?php echo $display; ?>