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
?>