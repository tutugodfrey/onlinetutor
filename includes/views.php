<?php
	function date_field($label, $dday = 31, $dmon= 12, $start_year = "start", $end_year = "end" ){
		//if date is not the range of years is not specified the function count down from the current year
		//define global function
		global $date_fields;
		global $day;		//maybe developer will just want only one or any other combination of 
		global $month;		//day, month, year for thier program. they will need to only add the <select> portion of the program
		global $year;
		if($start_year == "start" && $end_year == "end"){
			$start_year = date("Y"); 	//php date function to get the current date
			$end_year = 1955;
		}
		$day = "<option value = \"day\" selected = \"selected\">Day</option>";
		for ($i = 1; $i<= $dday; $i++){
			$day .= "<option value = \"$i\">$i</option>";
		}
		$mon = "<option value = \"month\" selected = \"selected\">Month</option>";
		for ($i = 1; $i<= $dmon; $i++){
			$mon .= "<option value = \"$i\">$i</option>";
		}
		$year = "<option value = \"year\" selected = \"selected\">Year</option>";
		for ($i = $start_year; $i>= $end_year; $i--){
			$year .= "<option value = \"$i\">$i</option>";
		}
		$show_label = ucwords($label);
		$date_fields = <<<block
		<fieldset>
		<label for = "$label" > $show_label   </label>
		<select name = "day">$day</select>
		<select name = "mon">$mon</select>
		<select name = "year">$year</select>
		</fieldset><br />
block;
	}

//////////////////////////////////////////////////
function mytable($values, $checkbox = "no", $display_col1 = "yes", $grade = "no"){
	//declare global variables
	$fields = array_shift($values); //fields in the table in array format
	global $table_values;

	$checkbox = trim(strtolower($checkbox));
	$display_col1 = trim(strtolower($display_col1));
	$grade = trim(strtolower($grade));

	if($grade == "no"){
		$grade = "";
		$graded = "";
	}

	if($grade == "yes"){
		$graded = "<tr><th></th><td colspan = \"2\">
		<input type = \"radio\" name = \"grade\" value = \"1\" />
		<input type = \"radio\" name = \"grade\" value = \"2\" />
		<input type = \"radio\" name = \"grade\" value = \"3\" />
		<input type = \"radio\" name = \"grade\" value = \"4\" />
		<input type = \"radio\" name = \"grade\" value = \"5\" />
		</td></tr>";
		//a checkbox to indicate that a student has been graded or not
		//$graded = "<input type = \"checkbox\" class = \"graded\" value = \"no\" name = \"graded[]\" checked = \"checked\" />";
		//$graded = "<input type = \"checkbox\" class = \"graded\" value = \"yes\" name = \"graded[]\" checked = \"checked\" />";
	}

	//check to know how many field are in the table and build the table headings
	if($checkbox == "yes"){
		$table_values = "<table><tr><th> </th>";
	} 	elseif($checkbox == "no"){
		$table_values = "<table><tr>";		//in this case we do not need the empty colomn
	}
	if($display_col1 == "yes") {
		for($i = 0; $i < sizeof($fields); $i++){
			$table_values .= "<th>".ucwords($fields[$i])."</th>";
		}
	}	elseif($display_col1 == "no") {
		for($i = 1; $i < sizeof($fields); $i++){
			$table_values .= "<th>".ucwords($fields[$i])."</th>";
		}
	}
	$table_values .= "</tr>";

	if($checkbox == "yes"){
		if($display_col1 == "yes"){
			foreach($values as $value){
				$identity = strtolower($fields[0]);
				$table_values .= "<tr><td><input type = \"checkbox\" class = \"$identity\"  name = \"$identity"."[]\" value = \"$value[0]\" /></td>";
				for($i = 0; $i < sizeof($fields); ++$i){
					$table_values .= "<td>$value[$i]</td>";
				}
				$table_values .= "</tr>";
			}
		} elseif($display_col1 == "no") {
			foreach($values as $value){
				$identity = strtolower($fields[0]);
				$table_values .= "<tr><td><input type = \"checkbox\" class = \"$identity\" name = \"$fields[0][]\" value = \"$value[0]\" /></td>";

				if($grade == "yes" ) {
					$length_of_field = sizeof($fields) - 1;
				} 	else  {
					$length_of_field = sizeof($fields);
				}
				for($i = 1; $i < $length_of_field; ++$i){
					$table_values .= "<td>$value[$i]</td>";
				}
				if($grade == "yes") {			//this field is check by default but it will be hidden with css and revealed only if student have been graded
					$table_values .= "<td><input type = \"checkbox\" class = \"graded\" id = \"grade$value[0]\" value = \"$value[4]\" name = \"graded[]\" checked = \"checked\" disabled = \"disabled\"/></td>";
				}
				$table_values .= "</tr>";
				$table_values .= $graded;
			}
		}
	}
	if($checkbox ===  "no"){
		foreach($values as $value){
			$table_values .= "<tr>";
			for($i = 0; $i < sizeof($fields); ++$i){
				$table_values .= "<td>$value[$i]</td>";
			}
			$table_values .= "</tr>";
		}
	}
	$table_values .= "</table><br />";

	return $table_values;
}



/////////////////////////////////////////////////////
//function to output result in a select option field
function select_option($values, $label, $name_of_field, $select_class = "", $lable_class = ""){
	$rows = sizeof($values);
	global $select_result;
	global $L;
	//  echo $values;
	$L = sizeof($values[0]);
	// echo $L;
	//if($rows == 1){
	/**
		it appear that when i select a select colomn and call mysqli_fetch_array(), the column returned is two
	  likewise selected two column return 4 and selected 3 column return 6 ... so i have to device this
	  this walk_around to get the appropriate column i am expecting from the result. since this happens
	  when the rows returned i 1 i have to streamline it to only 1 rows return
	*/
	if($L == 2){		
		$L = 1;			
	} 			
	if($L == 4){		
		$L = 2;
	}
	if($L == 6){
		$L = 3;
	}
	//}
	//$rows = sizeof($values);
	$select_result = "<label for = \"$label\" class = \"$lable_class\" > ".ucwords($label)." </label><select id = \"$label\" name = \"$name_of_field\" class = \"$select_class\">";
	if($L == 1) {	//it a one column one or more rows array
		$L = $L- 1;
		for($i = 0; $i < $rows; $i++) {
			$select_result .= "<option value =  \"".$values[$i][$L]."\">".$values[$i][$L]."</option>";
		}
	}  elseif($L == 2) {
		$index0 = $L - 2; 	// == 0
		$index1 = $L - 1; 	// ==1
		for($i = 0; $i < $rows; $i++) {
			$select_result .= "<option value =  \"".$values[$i][$index0]."\">".$values[$i][$index1]."</option>";
		}
	} elseif($L == 3) {
		$index0 = $L -3;  	// ==0
		$index1 = $L - 2; 	// == 1
		$index2 = $L - 1; 	// ==2
		for($i = 0; $i < $rows; $i++) {
			$select_result .= "<option value =  \"".$values[$i][$index0]."\">".$values[$i][$index1]." ".$values[$i][$index2]."</option>";
		}
	}
	$select_result .= "</select>";
	return $select_result;
}

/*

	//////////////////////////////////////////////////
function mytable($values, $checkbox = "no", $display_col1 = "yes", $grade = "no", $add_form = "no"){
	//declare global variables
	$fields = array_shift($values); //fields in the table in array format
	global $table_values;

	$checkbox = trim(strtolower($checkbox));
	$display_col1 = trim(strtolower($display_col1));
	$grade = trim(strtolower($grade));

	if($grade == "no"){
		$grade = "";
		$graded = "";
	}

	if($grade == "yes"){
		$graded = "<tr><th></th><td colspan = \"2\">
		<input type = \"radio\" name = \"grade\" value = \"1\" />
		<input type = \"radio\" name = \"grade\" value = \"2\" />
		<input type = \"radio\" name = \"grade\" value = \"3\" />
		<input type = \"radio\" name = \"grade\" value = \"4\" />
		<input type = \"radio\" name = \"grade\" value = \"5\" />
		</td></tr>";
		//a checkbox to indicate that a student has been graded or not
		//$graded = "<input type = \"checkbox\" class = \"graded\" value = \"no\" name = \"graded[]\" checked = \"checked\" />";
		//$graded = "<input type = \"checkbox\" class = \"graded\" value = \"yes\" name = \"graded[]\" checked = \"checked\" />";
	}

	//check to know how many field are in the table and build the table headings
	if($checkbox == "yes"){
		$table_values = "<table><tr><th> </th>";
	} 	elseif($checkbox == "no"){
		$table_values = "<table><tr>";		//in this case we do not need the empty colomn
	}
	if($display_col1 == "yes") {
		for($i = 0; $i < sizeof($fields); $i++){
			$table_values .= "<th>".ucwords($fields[$i])."</th>";
		}
	}	elseif($display_col1 == "no") {
		for($i = 1; $i < sizeof($fields); $i++){
			$table_values .= "<th>".ucwords($fields[$i])."</th>";
		}
	}
	$table_values .= "</tr>";

	if($checkbox == "yes"){
		if($display_col1 == "yes"){
			foreach($values as $value){
				$identity = strtolower($fields[0]);
				$table_values .= "<tr><td><input type = \"checkbox\" class = \"$identity\"  name = \"$identity"."[]\" value = \"$value[0]\" /></td>";
				for($i = 0; $i < sizeof($fields); ++$i){
					$table_values .= "<td>$value[$i]</td>";
				}
				$table_values .= "</tr>";
			}
		} elseif($display_col1 == "no") {
			foreach($values as $value){
				$identity = strtolower($fields[0]);
				$table_values .= "<tr><td><input type = \"checkbox\" class = \"$identity\" name = \"$fields[0][]\" value = \"$value[0]\" /></td>";

				if($grade == "yes" ) {
					$length_of_field = sizeof($fields) - 1;
				} 	else  {
					$length_of_field = sizeof($fields);
				}
				for($i = 1; $i < $length_of_field; ++$i){
					$table_values .= "<td>$value[$i]</td>";
				}
				if($grade == "yes") {			//this field is check by default but it will be hidden by css and revealed only if student have been graded
					$table_values .= "<td><input type = \"checkbox\" class = \"graded\" id = \"grade$value[0]\" value = \"$value[4]\" name = \"graded[]\" checked = \"checked\" disabled = \"disabled\"/></td>";
				}
				$table_values .= "</tr>";
				$table_values .= $graded;
			}
		}
	}
	if($checkbox ===  "no" && $add_form === "no"){
		foreach($values as $value){
			$table_values .= "<tr>";
			for($i = 0; $i < sizeof($fields); ++$i){
				$table_values .= "<td>$value[$i]</td>";
			}
			$table_values .= "</tr>";
		}
	}

	// build inline-form for each collection
	if($checkbox ===  "no" && $add_form !== "no" ){
		// echo $add_form;
		foreach($values as $value){
			$table_value = "<tr>";
			$table_info = "";
			for($i = 0; $i < sizeof($fields); ++$i){
				$table_info .= "<td>$value[$i]</td>";
			}
			$add_form = str_replace( "info_placeholder", $table_info, $add_form);

			$table_value .= $add_form;
			$table_value .= "</tr>";
			echo $table_value;
			$table_values .= $table_value;
		}
	}
	$table_values .= "</table><br />";

	return $table_values;
}

*/

function create_form($form_body, $form_fields, $extra) {

}



?>