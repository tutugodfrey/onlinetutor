
<div id = "main-page">
		<div id = "reg-lecturer-form-group">
	    <form id = "search_lec_form" class = "form-inline my-2 my-lg-0 mr-auto" method = "GET" action = "/onlinetutor/common/search_names.php" >
	      <input type = "search" class = "link_buttons form-control mr-sm-2 form-control-sm" id = "search-lecturers" name = "name_like" placeholder = "search for lecturer"/>
	    <input type = "submit" id = "search_lec" class = "btn btn-success my-2 my-sm-0 hide-item" name = "search_lecturers" value = "Search" />
	    </form>
	    <form name = "reg_lecturer" id = "reg_lec_form" class="form-inline my-2 my-lg-0" method = "POST" action = "/onlinetutor/common/profile.php" >
	      <div id = "lecturers-name" class = "link_buttons"></div>
	      <input type = "submit"  id = "register-lecturer" class = "btn btn-success hide-item" name = "register_lecturer" value = "Register" />
	    </form>
    </div>
	<div class="row">
		<div id = "main-content-area" class = "col-lg-8 col-sm-12">
			<h1 id = "welcome-note">
		  	Welcome to Online tutor
			</h1>
		</div>
		<div id = "side-bar" class = "col-lg-4 col-sm-12">
		</div>
	</div>
</div>