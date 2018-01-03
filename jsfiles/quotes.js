//script for outputing quote to the screen each time the window is loaded and the choice to get more quotes if the user
// so desire

var quotes = new Array();		//each element in this array is an array of quotes by specific individaul
var quote_owner = new Array();		//quote_owner match the quotes array


var albert_einstein = new Array("A man should look for what is is, and not for what he thinks should be",
	"A person who never made a mistake never tried anything new", "A table, a chair, a bowl of fruit and violin; what" +
	" else does a man need to be happy?", "An empty stomach is not a good political adviser", "A man who read to much" +
	" and uses is own brain to little falls into the lazy habit of thinking");
quote_collector("Albelt Einstein", albert_einstein);


var audrey_hepburn = new Array("As you grow older, you will discover that you have two hands, one for helping yourself, the" +
	" other for helping others", "For beautiful yes, look for the good in others; for beautiful lips, speak only words of" +
	" kindness; and for poise, walk with the knowledge that you are never alone,", "I don't want to be alone, I want to be" +
	" left alone.", "I was born with enormous need for affection, a terrible need to give it", "Nothing is impossible the" +
	" word itself 'i'm possible'!",  "The best thing to hold onto in life is each other.");
quote_collector("Audrey Hepburn", audrey_hepburn);

var ayn_rand = new Array("A creative man is motivated by the desire to achieve, not by the desire to beat others.", "A desire" +
	" presupposes the possibility of action to achieve it; action presupposes a goal which is worth achieving.", "Achieving" +
	" life is not the equivalent of avoiding death.", "Every man builds his world in his own image. He has the power to choo" +
	"se but no power to escape the necessity of choice.", "Force and mind are opposites; moralilty ends where a gun begins",
	"Happiness is that state of consciouseness which proceeds from the achievement of one's values.");
quote_collector("Ayn Rand", ayn_rand);


///////////////////////
//collect quotes and quote_owner into arrays
function quote_collector(name, quote_array){
quotes.push(quote_array);
quote_owner.push(name);
return quote_owner;
}

///////////////////////////
//for generating quotes randomly
function randomQuote () {
var size_of_quotes = quotes.length;		//get the size of the element in the quotes array
var random_number = Math.floor(Math.random() * size_of_quotes);		//generate a random number for accessing the quotes and quote_owner

var size_of_each = quotes[random_number].length;
var random_quote = Math.floor(Math.random() * size_of_quotes);	//generate a random number for accessing each element inside the array

var quote = quotes[random_number][random_quote];
var quote_by = quote_owner[random_number];


return [quote, quote_by];
}


var output_html = function(){		//function to out the quote as html

var quoteArray = randomQuote();
var quotes = quoteArray[0];
var quote_by = quoteArray[1];
//the quote will be displayed on the side content div 
var content_area = document.getElementById("side_content");

quote_div = document.createElement("div");
quote_div.id = "quote_div";

quote_heading = document.createElement("h1");
quote_heading.id = "quotes_heading";
quote_heading_text = document.createTextNode("World Great Speeches");
small_heading = document.createTextNode("Get inspired");
quote_heading.appendChild(quote_heading_text);

var quote = document.createElement("p");
quote.id = "quote_id";
var text = document.createTextNode(quotes);
quote.appendChild(text);
small_heading = quote.appendChild(small_heading);
var line_break = document.createElement("br");

var quote_owner = document.createElement("p");
quote_owner.id = "owner_id";
var quote_owner_text = document.createTextNode(quote_by);
quote_owner.appendChild(quote_owner_text);


var get_quote = document.createElement("button");
var button_text = document.createTextNode("Get More Quote");
get_quote.appendChild(button_text);
get_quote.id = "get_more_quote";

quote_div.appendChild(quote_heading);
quote_div.appendChild(small_heading);
quote_div.appendChild(quote);
quote_div.appendChild(line_break);
quote_div.appendChild(quote_owner);
quote_div.appendChild(get_quote);
content_area.appendChild(quote_div);
get_more_quote();
}


if(addEventListener){
addEventListener("load",
output_html,
false);
}	else if(attachEvent){
attachEvent("onload",
output_html);
}	






//////////////////////
function more_quote ( ) {
var quote_owner  = document.getElementById("owner_id");
var quote_id = document.getElementById("quote_id");
var quoteArray = randomQuote();
quote_id.innerHTML = quoteArray[0];
quote_owner.innerHTML = quoteArray[1];

}

///////////////////
function get_more_quote() {
//function will be called when the get_more_quote button becomes available
//get more quote
if (document.getElementById("get_more_quote")) {

var more_quote_button = document.getElementById("get_more_quote");
if(more_quote_button.addEventListener){
more_quote_button.addEventListener("click", 
function(){
more_quote();		//call function that build arrays and random numbers
}, 
false);
}	else if(more_quote_button.attachEvent){
more_quote_button.attachEvent("onclick", 
function(){
more_quote();		//call function that build arrays and random numbers
});
}

}
}