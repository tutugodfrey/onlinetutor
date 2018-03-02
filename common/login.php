<?php
//include functions to connect to database and run queries
include "./../includes/db_connect.php";
include "./../includes/functions.php";

if(!isset($_POST["login"])){

if(isset($_POST["log_out"])){
session_start();
session_destroy();
//header("Location:/mylecturerapp/login.php");
}

$display = <<<end
  <div id = "login-page">
    <div class = "container">
      <div class "row">
        <div class = "card col-12 col-sm-12 col-md-4 col-lg-4"
          <div id = "login">
            <h3 class = "form-heading card-title">Log in</h3>
            <fieldset>
              <form class = "form-group" method = "POST" action = "login.php" >
              <p id = "validation-notice">Fields mark below are required</p>
              <label for = "username" class = "sr-only">Username</label>
              <input type = "text" class = 'requiredFields form-control' placeholder = "username" name = "username" id = 'username'  size = "30" /><br />
              <label for = "password" class = "sr-only" >Password</label>
              <input type = "password" id = 'password' placeholder = "password" class = 'requiredFields form-control'  name = "password" /><br />
              <input type = "submit" value = "Log-In" class = "btn btn-success form-control" name = "login" /><br />
              </form>
            </fieldset>
            <div>
              <p> Don't have an Account? <a href = "signup.php" >Sign Up</a></p>
            </div>
            <div>
            <fieldset>
              <p>I<a href = "reset_password.php?forget_password"> forgot my password?</a>   I want to <a href = "reset_password.php?change">Change password</a></p>
            </fieldset>
            </div>
          </div>
        </div>
      </div>
      </div>
  </div>
end;
} elseif(isset($_POST["login"])) {
  $username = trim($_POST["username"]);
  $password = $_POST["password"];
  if($username == "" || $password == "password" || $password == ""){
    $display = "<p>Please Enter your username and password to login<a href = \"login.php\">&lt;&lt; Back</a></p>";
  } else  {
    //process log in for students
    //authenticate the use
    $query_string = "select id, username, user_type, lastname, firstname, picture from registered_users where username = \"$username\" and password = sha1($password)";

    run_query($query_string);   //use registration database. if access is denied the user authentication fails
    if($row_num2 == 0){
      //login not successful
      $display = "<p>Your username or password is not con correct. please ensure you are duly registered to use this app</P>";
    } else if($row_num2 == 1){

      $user_info = build_array($row_num2);
      $user_id = $user_info["id"];
      $user_type = $user_info["user_type"];
      $display = json_encode($user_info);
      //$display = json_encode($user_info);
      session_start();
      $_SESSION["names"] = $user_info["lastname"]." ".$user_info["firstname"];
      $_SESSION["user_image_url"] = $user_info["picture"];

      if($user_type == "student"){
        $_SESSION["class"] = "student";
        $_SESSION["owner_id"] = $user_id;
      }

      if($user_type == "lecturer"){
        $_SESSION["class"] = "lecturer";
        $_SESSION["owner_id"] = $user_id;
      }
    }
  }
}
?>

<?php echo $display; ?>
