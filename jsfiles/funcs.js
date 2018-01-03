 

xmlHttp = createXmlHttpRequest();
var calculator_id;
var href_class = "";
var new_chat_id = "";
var submit_id = "";
var image_ele;
//var close_chat;
var chat_msg_div;	
var chat_div;
var file_available = false;
var storedHistoryData;
var textResponse;
/*
if(typeof localStorage === undefined) {	//browser does not support localstorage

alert("opp sorry your browser does not support localstorage");
} 	else 	{

//attempt to use local storage
//first check if user is already using localstorage
if(localStorage.getItem("username") === null) {		//user have not previously allow localstorage

//wantStorage = confirm("would you like the browser to store the data user for this application");

if (wantStorage === true) {	//implement localstorage
//alert("we will implement localstorage for you");
 }	else if (wantStorage === false) {
//newEvent("lecturers_profile", "click", form_element);
//alert("we will not implement localstorage for you");
}

}	else 	{	//username is already stored

//username = localStorage.getItem("username");
//alert("username =" + username);

}

}
*/


dom_notifier();

function dom_notifier() {

if(document.getElementById("test_duration")){
test_duration_id = document.getElementById("test_duration");
test_duration = test_duration_id.value;

timer(test_duration, "time_left", submit_test);

}

if(document.getElementsByTagName("a")){
link_eles = document.getElementsByTagName("a");
for(i = 0; i < link_eles.length; i++) {
newEvent(link_eles[i], "click", linkValue, link_eles[i] );
}
}



if(document.getElementsByClassName("nojs_show")) {		//anything that need to be hidden for js users
ele_object = document.getElementsByClassName("nojs_show");
for(i = 0; i < ele_object.length; i++) {
data_array = [ele_object[i], "class", "js_hide", "no"];
changeAttribute(data_array);
}
}
/*
if(document.getElementById("logout")){
log_out  = document.getElementById("logout");
log_out.removeEventListener("click", submit_ele, log_out);

}*/


if(document.getElementsByClassName("submit_buttons")){
submit_btn = document.getElementsByClassName("submit_buttons");
for(i = 0; i < submit_btn.length; i++ ) {
newEvent(submit_btn[i], "click", submit_ele, submit_btn[i]);
}
}


if(document.getElementsByClassName("inner_btns")){

submit_ibtn = document.getElementsByClassName("inner_btns");

for(i = 0; i < submit_ibtn.length; i++ ) {
newEvent(submit_ibtn[i], "click", submit_el, submit_ibtn[i]);
}
}

/*
if(document.getElementsByTagName("form")) {
data = ["tagname", "form"];
form_elements = ele_getter(data);
for(i = 0; i < form_elements.length; i++) {
newEvent(form_elements[i], "submit", form_element, form_elements[i]);
}
}
*/


if(document.getElementsByTagName("select")) {
select_el = document.getElementsByTagName("select");
for(i = 0; i < select_el.length; i++){
newEvent(select_el[i], "change", select_val, select_el[i]);
}
}


if(document.getElementsByTagName("img")) {
image_ele = document.getElementsByTagName("img");

image_size = image_ele.length;
for(imgSize = 0;  imgSize < image_size; imgSize++) {
//call event listener function
data_array = [image_ele[imgSize], "class", "large_img", "yes"];
//new_image = image_ele[imgSize];
newEvent (image_ele[imgSize], "click", changeAttribute, data_array);
}
} 

if(document.getElementById("enter_lecturer_name")){
enter_lec_id = document.getElementById("enter_lecturer_name");
newEvent(enter_lec_id, "input", search_names, enter_lec_id);
newEvent(enter_lec_id, "blur", blurer, enter_lec_id);
}

if(document.getElementById("new_friend_name")){
enter_friend_id = document.getElementById("new_friend_name");
newEvent(enter_friend_id, "input", search_friends, enter_friend_id);
}



if(document.getElementById("use_calculator")){
calcEle = document.getElementById("use_calculator");
calculator();
//alert(calcEle);
newEvent(calcEle, "mousedown", move_ele, calcEle);
}

if(document.getElementById("chat_div")){	
chat_div = document.getElementById("chat_div");

close_chat = document.getElementById("close_chat");
	//a div that contain chat form and chat_messages
newEvent(chat_div, "dblclick", move_ele, chat_div);

data_array = [chat_div, "class", "js_hide", "no"];
newEvent(close_chat, "click", changeAttribute, data_array);
newEvent(close_chat, "click", bringback, "create_ele");
}

if(document.getElementById("displayChat")){
displayChat = document.getElementById("displayChat");
newEvent(displayChat, "click", bringback, "display_chat");
}

if(document.getElementById("chat_msg_div")) {
//alert("work on updating top  post chat");
chat_msg_div = document.getElementById("chat_msg_div");
newEvent(chat_msg_div, "scroll", earlier_chat, chat_msg_div);
} 




////////////////////////////////////
//feature testing
if("ontouchstart" in window) {

//alert("you have a touch device");
} 	else {
//alert("Your does not have touch capabilities");
}		//end touch capability testing


if(navigator.vibrate){

//alert("your browser support vibration");
navigator.vibrate([1000, 500, 2000]);
//alert("vibration over");

}	else 	{
//alert("your browser does not support vibration");
}

if(navigator.connection){
//alert("what is the network condition");
} 	else {

//alert("your system does not support connection");
}

if(navigator.battery) {
//alert("your browswer support battery api");
//alert(navigator.battery.level);
} 	else {
//alert("your browser does not support battery api");
}

}	//end dom_notifier



//////////////////////////////////////////
// holds an instance of XMLHttpRequest
var xmlHttp = createXmlHttpRequest();

//create answer XMLHttpRequest instance
function createXmlHttpRequest(){
//will store the reference to the XMLHttpRequest object
var xmlHttp;
// this should work for all browsers except IE6 and older 
try{
//try to create XMLHttpRequest object
xmlHttp = new XMLHttpRequest();
} catch(e) {
var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.5.0",
				"MSXML2.XMLHTTP", "Microsoft.XMLHTTP");
//try every prog id until one works
for(var i = 0; i < XmlHttpVersions.length && !xmlHttp; i++){
try {
xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
} catch(e){}
}
}
if(!xmlHttp){
alert("Error creating the XMLHttpRequest object.");
} else {
return xmlHttp;
}
}



function changeAttribute(data_array) {
// data_array = [ele_object, att_to_change, new_att, remove_attr]
var ele_object = data_array[0];
var attr_to_change = data_array[1];
var new_attr = data_array[2];
var remove_attr = data_array[3];		// a string yes/no/change_attr_to if new_attr already exist

var ele_attr = ele_object.getAttribute(attr_to_change);
if (ele_attr === null || ele_attr !== new_attr) 	{
//set the attribute and the image become large
ele_object.setAttribute(attr_to_change, new_attr);
} else
 if (ele_attr === new_attr && remove_attr === "yes") {
//remove the attribute. the image become small
ele_object.removeAttribute(attr_to_change);
} 	
else if (ele_attr === new_attr && remove_attr === "no") {
//no action taken
}
else if(ele_attr === new_attr && (remove_attr !== "yes" || remove_attr !== "no" || remove_attr !== "undefined")){
var change_to = remove_attr;
ele_object.setAttribute(attr_to_change, remove_attr);
} 

}		//end changeClass





/////////////////////////////////////////////////
//when search for lecturer result returns
function calc_display () {
calcEle.innerHTML =  textResponse;
}

function move_ele(ele) {
//alert("you mouse down");
ele_to_move = ele;
addEventListener("mousemove", move_handler);
//alert(ele_to_move);
}

function buttonPress(event) {
    if(event.buttons == null){
        return event.buttons !== 0;
    }	else 	{
        return event.buttons !== 0;
    }
}

function move_handler(event){
if(!buttonPress(event)){
removeEventListener("mousemove", move_handler);

}	else	{ 

//var yOffset = event.pageY;
//var xOffset = event.pageX;
ele_to_move.style.left = event.pageX; //xOffset;
ele_to_move.style.top = event.pageY; //yOffset;

//alert("x = " + xOffset + "y = " + yOffset);
}
}











//////////////////////////////////////////////////
function getMethod (data, callback) {

var url = data[0];
var method = data[1];
var store_history = url + "m=GET";
if(new_chat_id === "new_chat" || submit_id === "search_lec" || submit_id === "logout"){
//do not push the new chat message into the history object
}	else	{
if (typeof storedHistoryData === "undefined") {
history.pushState(store_history, "newurl", url);	//the url+msg is the entire data that is send to the server to fetch data

}	else	{
if(storedHistoryData === store_history) {
//do not push  to history object
}	else {
history.pushState(store_history, "newurl", url);	//the url+msg is the entire data that is send to the server to fetch data
}
}
}

if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
xmlHttp.open("GET", url, true);
xmlHttp.onreadystatechange = function()	{
//processing indicator
ajx_busy();

if(xmlHttp.readyState == 4){
if(xmlHttp.status == 200){
textResponse = xmlHttp.responseText;

callback();

} else {
alert("There was a problem accessing the server: " + xmlHttp.statusText);
}

}
}

xmlHttp.send(null);
}
}



////////////////////////////////////////////////////
function postMethod (data, callback) {
var url = data[0];
var msg = data[1];
var method = data[2];

var store_history = url + msg + "m=POST";
if(submit_id === "send_chat" || submit_id === "oldPost" )	{
//will not add to  the history data
}	else	{

if (typeof storedHistoryData === "undefined") {
history.pushState(store_history, "newurl", url);	//the url+msg is the entire data that is send to the server to fetch data

}	else	{
if(storedHistoryData === store_history) {

}	else {
history.pushState(store_history, "newurl", url);	//the url+msg is the entire data that is send to the server to fetch data
}
}

}

if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
xmlHttp.open(method, url, true);		//now set request headers for the post method

if(file_available === false) {	//form does not have a file input  else form will be submitted with FormData()

xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
}	
xmlHttp.setRequestHeader("Content-length", msg.length);
xmlHttp.setRequestHeader("Connection", "close");
xmlHttp.onreadystatechange = function()	{

//processsing indicator
ajx_busy();

if(xmlHttp.readyState == 4){
if(xmlHttp.status == 200){
dheader = xmlHttp.getAllResponseHeaders();
content_type  = xmlHttp.getResponseHeader("content-type");
if(content_type === "text/xml") {
textResponse = xmlHttp.responseXML;
}	else {
textResponse = xmlHttp.responseText;
}

callback();
} else {
alert("There was a problem accessing the server: " + xmlHttp.statusText);
}
}
}
xmlHttp.send(msg);

}
}



//////////////////////////////////
//use popstate to update the content area
window.addEventListener("popstate", 
function (e) {
storedHistoryData = e.state;		//keep this variable global;
//alert(storedHistoryData);
var string_length = storedHistoryData.length;
var method_ind = storedHistoryData.lastIndexOf("=") - 1;
var url_string = storedHistoryData.substring(0, method_ind);

var method_ind = storedHistoryData.substring(method_ind, string_length);

if(method_ind.indexOf("POST") > 1){
var url_delimiter = url_string.indexOf("?");
var url = url_string.substring(0, url_delimiter + 1);
var msg = url_string.substring(url_delimiter + 1, url_string.length);

var data = [url, msg, "POST"];
postMethod(data, displayContent);
}

if(method_ind.indexOf("GET") > 1 ){

var data = [url_string, "GET"];
getMethod(data, displayContent);
}

} );		//end popstate




function newEvent(element_object,  event_type, callback, func_argument){
if(element_object.addEventListener){
element_object.addEventListener(event_type, function(event) {

event.preventDefault();	
callback(func_argument);

},
false );
}	else if (element_object.attachEvent) {
element_object.attachEvent("on"+ event_type,
function(event){
event.preventDefault();	
callback(func_argument);
} );
}
}		//close the function block




//////////////////////////////////////
//ajx busy indicator
function ajx_busy () {
if(xmlHttp.readyState !== 4){
indicator = document.getElementById("ajax_neutral");
indicator.setAttribute("id", "ajax_busy");
}
if (xmlHttp.readyState === 4) {
indicator = document.getElementById("ajax_busy");
indicator.setAttribute("id", "ajax_neutral");
}
}


////////////////////////////////////////////////
//this function decide how content/textResponse from ajax call is handle and display
function displayContent () {

if(href_class === "friend" || (document.getElementById("chat_msg_div") && href_class === "friend") ) {
chating();

}
else if (document.getElementById("chat_msg_div") && submit_id === "send_chat"){	//check that the id of the submit is not the same for send chat button
//alert("formating the chat display");
chat_display (); 	//display chat messages

			//listening after every 5 seconds

}	else if (submit_id === "calc") {

calc_display();

}	else if (textResponse.indexOf("The grade has been recorded") > 1)	 {
var idOfGrade = "grade" + post_id;		//formate the id of the checkbox that indicate a graded post
var gradeCheckbox = document.getElementById(idOfGrade);
var changeAttribute = gradeCheckbox.setAttribute("value", "YES");
} 	else if(textResponse.indexOf("saddFriend") > 1)  	{
myDiv = document.getElementById("main_content");
myDiv.innerHTML += textResponse;
} 	else    {  //content that does note meet a particular spec will be displayed in the main_content div
myDiv = document.getElementById("main_content");
myDiv.innerHTML = textResponse;
}
dom_notifier();	//when elements becomes officially available call function to get their id's


href_class = "";		//offset this so others can display normally
}


////////////////////////////////////////////////////
function form_element(funct_arg) {
//alert(submit_id);

// furn_argument is array that compose [form_ele, function] function pass to post/getMethod for hander ajax response
var form_ele = funct_arg[0];
var ele_method = form_ele.getAttribute("method");
var ele_action = form_ele.getAttribute("action");
var form_name = form_ele.getAttribute("name");
var ele_controls = form_ele.elements;
//alert(form_ele);
var url = "";
var msg = ""; 
var form_values = [];
var submit_buttons = [];




for(j = 0; j < ele_controls.length; j++){
var ele_name = ele_controls[j].getAttribute("name");
var ele_type = ele_controls[j].type;
//alert(ele_type);
if(ele_type === "submit"){
var ele_id = ele_controls[j].getAttribute("id");
ele_name = ele_controls[j].getAttribute("name");
ele_value = ele_controls[j].getAttribute("value");
var submit_ele = [ele_name, ele_value];
var values = [ele_id, submit_ele];
submit_buttons.push(values);
} 	else if (ele_type === "select-one") {

form_values.push(select_value(ele_name));  
 }
	
else if (ele_type === "checkbox" || ele_type === "radio") {
checked_value =  getCheckboxValue(ele_controls[j]);
//alert(checked_value[0] + checked_value[1]);

if(checked_value !== undefined){
form_values.push(checked_value);
} 
}			
else if ( ele_type === "text"){
var ele_id = ele_controls[j].getAttribute("id");
form_values.push(input_value(ele_id));
} 	else if (ele_type === "textarea") {
form_values.push([ele_controls[j].name, ele_controls[j].value]);
} 	else if (ele_type === "number") {
ele_name = ele_controls[j].name;
ele_value = ele_controls[j].value;
form_values.push([ele_name, ele_value]);
}
 else if (ele_type === "file") {
file_available = true;
form_values.push(file_upload(ele_controls[j]));

} else if (ele_type === "fieldset"){
//no action taken
}
else 	{
ele_type = ele_controls[j].getAttribute("type");
ele_name = ele_controls[j].getAttribute("name");
ele_value = ele_controls[j].getAttribute("value");
form_values.push([ele_name, ele_value]);
}

}		//end for 
for(i = 0; i < submit_buttons.length; i++) {
if(submit_buttons[i][0] === "updateProfile" || submit_buttons[i][0] === "search_frnd") {
form_values.push(submit_buttons[i][1]);
}	else if(submit_buttons[i][0] === submit_id){		//submit_id defined as global in submit_ele
form_values.push(submit_buttons[i][1]);
}		//end if
}

var values_length = form_values.length;

if(file_available === true) {
form_data = new FormData();

for(i = 0; i < values_length; i++){
form_data.append(form_values[i][0], form_values[i][1]);
}		//end for
msg = form_data;

} 	else if (file_available === false){
for(i = 0; i < values_length; i++){
msg += form_values[i][0] + "=" + form_values[i][1] + "&";

}		//end for
msg = msg.substring(0, msg.length -1);		//strip off the last &
}

if(ele_method === "POST"){
url = ele_action;
alert(msg);
data  = [ele_action, msg, "POST"];
postMethod(data, funct_arg[1]);
}


if(ele_method === "GET"){
data  = [ele_action + "?" + msg, "GET"];
getMethod(data, funct_arg[1]);
}

///use this section to define input type text and textarea that will be offset after form submission
//add the id of the element to the if condition 
if(submit_id === "send_chat" ) {
alert(submit_id);
document.getElementById("chat_text_field").value = "";

}


}		//form_element function
 

function submit_ele(ele) {
var form_ele; 
submit_id = ele.getAttribute("id");

if(submit_id === "calc" && calculator_id === undefined){
calculator_id = submit_id;		//set as global variable
form_ele = ele.parentNode;
form_element([form_ele, displayContent]);
}	else if (submit_id === "calc" && calculator_id === "calc"){

var data_array = [calcEle, "class", "_show", "yes"];
changeAttribute(data_array);
}	else if(submit_id === "del_lec") {

confirm_delete = confirm("do you really want to remove this lecturer form you record");
if(confirm_delete === true){
form_ele = get_form_ele(ele);
form_element([form_ele, displayContent]);

}	else if (confirm_delete === false) {
//no action taken
}

}	else	{
form_ele = get_form_ele(ele);
//form_ele = ele.parentNode;
form_element([form_ele, displayContent]);

}	//end if
return submit_id;
}		//end submit_ele





function submit_el(ele) {
submit_id = ele.getAttribute("id");
var form_ele = ele.parentNode;
form_element([form_ele, displayContent]);
return submit_id;
}		//end submit_ele


function select_val(ele) {
var ele_value = ele.value;
if(ele_value === "photo" || ele_value === "birthday") {
var form_ele = ele.parentNode;
form_element([form_ele, displayContent]);		//call form_element to autosubmit the  form
} 	
}


function select_value(ele_name) {
if(document.getElementsByName(ele_name)){
var select_ele = document.getElementsByName(ele_name);

for(i = 0; i < select_ele.length; i++){
var ele_name = (select_ele[i].name);
var ele_value = (select_ele[i].value);
}
}
return [ele_name, ele_value];
}



//function to get the values of a checkbox and return it value
function getCheckboxValue(check_ele){		//can be used for both checkbox and radio button
if(check_ele.checked){
var ele_value = check_ele.value;
var ele_name = check_ele.name;
return [ele_name, ele_value];
}
}

function radio_value(ele) {
alert("here is the radio function");
return [ele.name, ele.value];
}

function input_value (ele_id) {
if(document.getElementById(ele_id)){
var ele_object = document.getElementById(ele_id);
var ele_name = ele_object.name;
var ele_value = ele_object.value;
}
return [ele_name, ele_value];
}

function file_upload(file_ele) {
//var ele_value = file_ele.files[0];
var file_obj = file_ele.files;
alert(file_obj.length);
var ele_value = file_obj;
file_submit = [];
if(file_obj.length  === 1){
ele_value = file_obj[0];
var ele_name  = file_ele.name;

return [ele_name, ele_value];
}	else if (file_obj.length > 1)	{
for (i = 0; i < file_obj.length; i++){
files_value = file_obj[i];
file_submit.push(files_value);

}
alert(file_ele);
var ele_name = file_ele.name;
alert(ele_name);
alert(file_submit);
return [ ele_name, file_submit ];
alert("i also pass here");
}
if(ele_value === undefined){
ele_value = " ";
alert(file_ele + "= undefined");
return "";
}
alert('i pass here');
//return [ele_name, ele_value];
}		//end file_upload

function linkValue(ele) {
var href = ele.getAttribute("href");
href_class = ele.getAttribute("class");


if(href_class === "friend") {

chat_div_class = chat_div.getAttribute("class");

if(chat_div_class === null || chat_div_class === "_show"){

getMethod([href, "GET"], displayContent); 
}	else if (chat_div_class === "js_hide")	{
getMethod([href, "GET"], displayContent); 
//changeAttribute([chat_div, "class", "_show"]);
}


}	else	{
getMethod([href, "GET"], displayContent); 
}
}

////////////////////////////////////////////
function get_form_ele(ele){

while(ele.toString() !== "[object HTMLFormElement]") {	//trying to get the form for ele
ele = ele.parentNode;

if(ele.toString()  === "[object HTMLFormElement]"){
form_ele = ele;
break;
}
}

return form_ele;
}



function search_names (ele) {		//to search for lecturer
submit_id = "search_lec";
name_to_search = ele.value;
ele_name = ele.name;
ele_type = ele.type;

form_ele = get_form_ele(ele);	

//form_ele = ele.parentNode.parentNode.parentNode;
//alert(form_ele.type);
form_method = form_ele.method;
form_action = form_ele.action;
submit_ele = document.getElementById("search_lec");
sub_name = submit_ele.name;
sub_value = submit_ele.value;
url = form_action + "?" + sub_name + "=" + sub_value + "&" + ele_name + "=" + name_to_search;
getMethod([url, "GET"], returned_lecturers);
//form_element([form_ele, returned_lecturers]);
}

/////////////////////////////////////////////////
//when search for lecturer result returns
function returned_lecturers () {
var newLec = document.getElementById("lecturers_name");
var registerBtn = document.getElementById("registerLecturer");
newLec.innerHTML =  textResponse;
var data_array = [registerBtn, "class", "submit_buttons nojs_show", "no"];
var data_array2 = [newLec, "class", "nojs_show", "no"];
changeAttribute(data_array);
changeAttribute(data_array2);
}

/////////////////////////
function blurer (ele_obj) {
var ele_value = ele_obj.value;

var newLec = document.getElementById("lecturers_name");
var registerBtn = document.getElementById("registerLecturer");
if(ele_value === ""){

var data_array = [registerBtn, "class", "js_hide", "no"];
var data_array2 = [newLec, "class", "js_hide", "no"];
changeAttribute(data_array);
changeAttribute(data_array2);
}	else	{
alert(ele_value);
}
}


function search_friends(ele) {		//to search for lecturer

submit_id = "search_frnd";
name_to_search = ele.value;
ele_name = ele.name;

//form_ele = ele.parentNode.parentNode.parentNode;
form_ele = ele.parentNode;

//alert(form_ele);
form_method = form_ele.method;
form_action = form_ele.action;
submit_ele = document.getElementById("search_frnd");
sub_name = submit_ele.name;
sub_value = submit_ele.value;
url = form_action + "?" + sub_name + "=" + sub_value + "&" + ele_name + "=" + name_to_search;
getMethod([url, "GET"], returned_friends);
}

/////////////////////////////////////////////
//function to call if the search result for friends is result
function returned_friends () {
var newFriends = document.getElementById("matched_friend_list");
newFriends.innerHTML = textResponse;
//get_ids(displayContent);
dom_notifier();
}

/////////////////////////////////////////////////
//when search for lecturer result returns
function chating () {
//alert(textResponse);
alert("chat_div2" + chat_div.childNodes[2]);
//chat_div.childNodes[1].appendChild(textResponse);
if(chat_div.childNodes[1] === "undefined" ){
chat_div.innerHTML +=  textResponse;
}	else  {
chat_div.innerHTML = "";
chat_div.innerHTML = '<p  id = "close_chat">x</p>';
chat_div.innerHTML += textResponse;
}
changeAttribute([chat_div, "class", "_show"]);
}	//end chating function

///////////////////////////
//when already chat and the close button is press, place an indicator on the window so bring back the chat
function bringback(what_to_do) {
if(what_to_do === "create_ele") {
if(document.getElementById("displayChat")){
//do nothing
}	else 	{
para = document.createElement("button");
para.setAttribute("id", "displayChat");
para_content = document.createTextNode("Chating");
para.appendChild(para_content);
nav_div = document.getElementById("nav_buttons");
nav_div.appendChild(para);
dom_notifier();
}
}
if(what_to_do === "display_chat") {
//chat_msg = document.getElementById("chat_div");		//a div that contain chat form and chat_messages
data_array = [chat_div, "class", "_show",  "js_hide"];	//chat_div is global. chat_div will alternate b/w _show and js_hide
changeAttribute(data_array);
}
}  




/////////////////////////////////////////////
//special formating for displaying chats
function chat_display () {

if(submit_id === "oldPost") {
alert(textResponse);
doc_root = textResponse.documentElement;
alert(doc_root);
paras = doc_root.getElementsByTagName("p");
paras = doc_root.childNodes;
chat_msg_div = document.getElementById("chat_msg_div");
alert(paras.length);
for(i = 0; i < paras.length; i++) {
pos_to_insert = chat_msg_div.childNodes[0];
chat_msg_div.insertBefore(paras[i], pos_to_insert);
}

}	else	{
if (textResponse.indexOf("<p>Your message has been posted</p>") > 1){
var start_pos = textResponse.indexOf("<p>Your message has been posted</p>");
var end_pos = start_pos + 35;		//35 = the length of the string

var server_response = textResponse.substring(start_pos, end_pos);
var returned_message = textResponse.substring(0, start_pos);

var chat_msg_div = document.getElementById("chat_msg_div");
chat_msg_div.innerHTML += returned_message;
var serverReply = document.getElementById("server_reply");
serverReply.innerHTML = server_response;

} else if(textResponse.indexOf("<p>The massage has already been posted</p>") > 1 ||
 textResponse.indexOf("<p>Please type a message to post</p>") > 1 ){

var serverReply = document.getElementById("server_reply");
serverReply.innerHTML = textResponse;

} 	else if(textResponse.indexOf("No new chats messages") > 1){
var server_reply = document.getElementById("server_reply");
server_reply.innerHTML = textResponse;

}	else	{

chat_msg_div = document.getElementById("chat_msg_div");
chat_msg_div.innerHTML += textResponse;
//setTimeout('updateChat()', 5000);			//listening after every 5 seconds
}
setTimeout('updateChat()', 5000);

}
}


////////////////////////////////////////////
//for updating the chats
function updateChat(){
var last_post_time = "";

chats = document.getElementsByClassName("chats");
if(chats.length >= 1){
var length_of_posts = chats.length;
length_of_posts = length_of_posts - 1;
var latest_post = chats[length_of_posts].textContent;
var string_length = latest_post.length;
var start_pos = string_length - 19;		//19 is length of the datatime portion
var last_post_time = latest_post.substring(start_pos, string_length);
}
var friend_id = document.chat_form.friend_id.value;
var msg = "get_recent_post=yes&friend_id=" + friend_id + "&last_post_time=" + last_post_time;

if(document.getElementsByName("chat_form")) {
chat_form = document.getElementsByName("chat_form");
url = chat_form[0].getAttribute("action");
new_chat_id = "new_chat";		//used to avoid pushstate to history object
var data = [url + "?" + msg, "GET"];
getMethod (data, displayContent );
}

}

function earlier_chat(chat_ele) {

//pos = chat_ele.pageYOffset;
pos = chat_ele.scrollHeight;
inner_height = chat_ele.innerHeight;
scrollbar_pos = chat_ele.scrollTop;
//pos = chat_ele.pageYOffset;

if(scrollbar_pos === 0){
chat_form = document.getElementById("chat_form");
var url = chat_form.action;
var method = chat_form.method;
submit_id = "oldPost";
submit_ele = document.getElementById("oldPost");

msg = submit_ele.name + "=" + submit_ele.value + "&";

my_friend = document.getElementById("my_friend");
msg += my_friend.name + "=" + my_friend.value + "&";

top_msg_time = document.getElementById("topValue");
msg += top_msg_time.name + "=" + top_msg_time.value;
if(method === "post") {
postMethod([url, msg, "POST"], chat_display);
alert(url + msg);
}


}	else if(scrollbar_pos == pos) {
alert(scrollbar_pos);
}
}		//end earlier_chat



/////////////////////////////////////////
//calcular function
function calculator () {
var calcValue1 = "";
var calcValue2 = "";
var calcOperator = "";
var result = 0;

var value1_box = document.getElementById("calcValue1");
var value2_box = document.getElementById("calcValue2");
var operator_box = document.getElementById("calcOperator");

var element_ids = ["11", "12", "13", "14", "15", "1", "2",
 "3", "4", "5", "6", "7", "8", "9", "0", "16"];

if(document.getElementById("16")){
reset_ele = document.getElementById("16");
newEvent(reset_ele, "click", calc, reset_ele);
}

if(document.getElementById("17")){
close_ele = document.getElementById("17");
newEvent(close_ele, "click", calc, close_ele);
}
for(var i in element_ids){
if(document.getElementById(i)){
var ele = document.getElementById(i);

newEvent(ele, "click", calc, ele);
}
}		//end for


function calc(ele){
var ele_value = ele.value; 

if(ele_value === "close") {
calc_div = document.getElementById("use_calculator");
changeAttribute([calc_div, "class", "js_hide"]);
} 	else if(ele_value === "="){
if(calcValue1 === ""){
calcValue1 = "0";
}
if(calcValue2 === ""){
calcValue2 = "0";
}

calcValue1 = parseInt(calcValue1);
calcValue2 = parseInt(calcValue2);

if(calcOperator === "+"){
result = calcValue1 + calcValue2;
}
if(calcOperator === "-"){
result = calcValue1 - calcValue2;
}
if(calcOperator === "x"){
result = calcValue1 * calcValue2;
}
if(calcOperator === "/"){
result = calcValue1 / calcValue2;
}

calcValue1 = result;
calcValue2 = "";

value1_box.innerHTML = calcValue1;
value2_box.innerHTML = calcValue2;

} else if (ele_value === "reset"){
calcValue1 = "";
calcValue2 = "";
calcOperator = "";
operator_box.innerHTML = calcOperator;
value1_box.innerHTML = calcValue1;
value2_box.innerHTML = calcValue2;
}  
else if (ele_value === "+" || ele_value === "-" 
|| ele_value === "/" || ele_value === "x"){
calcOperator = ele_value;
operator_box.innerHTML = calcOperator;
} 
else if (calcOperator === "" && (ele_value !== "+" || ele_value !== "-"
 || ele_value !== "x" || ele_value !== "/" || ele_value !== "=" 
|| ele_value !== "reset")){
calcValue1 += ele_value;
value1_box.innerHTML = calcValue1;
}  			
else if((ele_value === "+" || ele_value === "-" || ele_value === "x" ||
ele_value === "/") && (calcOperator === "+" || calcOperator === "-" 
|| calcOperator === "x" || calcOperator === "/")  ){
calcOperator = ele_value;
calcOperator_box.innerHTML = calcOperator;
}		
else if((ele_value !== "+" || ele_value !== "-" || ele_value !== "x" ||
ele_value !== "/" || ele_value !== "reset") && (calcOperator === "+" ||
 calcOperator === "-" || calcOperator === "x" || calcOperator === "/")){
calcValue2 += ele_value;
value2_box.innerHTML = calcValue2;
}
}	//end calc function


}		//end calculator function




var duration;
var duration_sec;
var show_time;
var sec_count;


function timer(time_duration, displayEle, func_timeup){
	// func_timeup is function to call when timeup
//duration = parseInt(time_duration);
duration = 1;
milisecond = duration * 60 * 1000;
duration_sec = duration * 60;
sec_count = 0;
if(typeof func_timeup != undefined) {
funcTimeup = func_timeup;
}
show_time = document.getElementById(displayEle);

time_up = setInterval(displayTime,  1000); 
 
}


function displayTime(){

if (duration === 0 && sec_count === 0 ){
clearInterval(time_up);
if(typeof funcTimeup != undefined){
alert(typeof funcTimeup);
funcTimeup();
} else {
alert("no time up function");
}
}	else 	{
if (duration >= 0 && sec_count === 0) {
duration = duration - 1;
sec_count = 60;
}	
show_time.innerHTML = duration + ":" + sec_count;
sec_count--;
}
duration_sec--;

}
 

function submit_test(){
submit_id = "submitTest";
testSubmitBtn = document.getElementById("submitTest");
form_ele = get_form_ele(testSubmitBtn);

form_element([form_ele, displayContent]);

}	/////////end submit test
