//script for outputing quote to the screen each time the window is loaded and the choice to get more quotes if the user
// so desire

const quotes = new Array();		//each element in this array is an array of quotes by specific individaul
const quoteOwner = new Array();		//quote_owner match the quotes array

const albertEinstein = new Array("A man should look for what is is, and not for what he thinks should be",
	"A person who never made a mistake never tried anything new", "A table, a chair, a bowl of fruit and violin; what" +
	" else does a man need to be happy?", "An empty stomach is not a good political adviser", "A man who read to much" +
	" and uses is own brain to little falls into the lazy habit of thinking");
quoteCollector("Albelt Einstein", albertEinstein);

const audreyHepburn = new Array("As you grow older, you will discover that you have two hands, one for helping yourself, the" +
	" other for helping others", "For beautiful yes, look for the good in others; for beautiful lips, speak only words of" +
	" kindness; and for poise, walk with the knowledge that you are never alone,", "I don't want to be alone, I want to be" +
	" left alone.", "I was born with enormous need for affection, a terrible need to give it", "Nothing is impossible the" +
	" word itself 'i'm possible'!",  "The best thing to hold onto in life is each other.");
quoteCollector("Audrey Hepburn", audreyHepburn);

const aynRand = new Array("A creative man is motivated by the desire to achieve, not by the desire to beat others.", "A desire" +
	" presupposes the possibility of action to achieve it; action presupposes a goal which is worth achieving.", "Achieving" +
	" life is not the equivalent of avoiding death.", "Every man builds his world in his own image. He has the power to choo" +
	"se but no power to escape the necessity of choice.", "Force and mind are opposites; moralilty ends where a gun begins",
	"Happiness is that state of consciouseness which proceeds from the achievement of one's values.");
quoteCollector("Ayn Rand", aynRand);

///////////////////////
//collect quotes and quote_owner into arrays
function quoteCollector(name, quote_array){
	quotes.push(quote_array);
	quoteOwner.push(name);
	return quoteOwner;
}

///////////////////////////
//for generating quotes randomly
function randomQuote () {
	const sizeOfQuotes = quotes.length;		//get the size of the element in the quotes array
	const randomNumber = Math.floor(Math.random() * sizeOfQuotes);		//generate a random number for accessing the quotes and quote_owner
	// var sizeOfEach = quotes[randomNumber].length;
	const randomQuote = Math.floor(Math.random() * sizeOfQuotes);	//generate a random number for accessing each element inside the array
	const quote = quotes[randomNumber][randomQuote];
	const quoteBy = quoteOwner[randomNumber];
	return [quote, quoteBy];
}

const getQuotes = function() { //function to out the quote as html
	const quoteArray = randomQuote();
	const quotes = quoteArray[0];
	const quote_by = quoteArray[1];
	//the quote will be displayed on the side-bar div 
	var content_area = document.getElementById("side-bar");
	const quoteDiv = document.createElement("div");
	quoteDiv.id = "quote-div";
	const quoteHeading = document.createElement("h1");
	quoteHeading.id = "quotes_heading";
	const quoteHeadingText = document.createTextNode("World Great Speeches");
	let smallHeading = document.createTextNode("Get inspired");
	quoteHeading.appendChild(quoteHeadingText);
	const quote = document.createElement("p");
	quote.id = "quote-id";
	const quoteText = document.createTextNode(quotes);
	quote.appendChild(quoteText);
	smallHeading = quote.appendChild(smallHeading);
	const lineBreak = document.createElement("br");
	const quoteOwner = document.createElement("p");
	quoteOwner.id = "owner-id";
	const quoteOwnerText = document.createTextNode(quote_by);
	quoteOwner.appendChild(quoteOwnerText);
	const getQuote = document.createElement("button");
	const buttonText = document.createTextNode("Get More Quote");
	getQuote.appendChild(buttonText);
	getQuote.id = "get-more-quote";
	getQuote.className = "btn btn-primary";
	quoteDiv.appendChild(quoteHeading);
	quoteDiv.appendChild(smallHeading);
	quoteDiv.appendChild(quote);
	quoteDiv.appendChild(lineBreak);
	quoteDiv.appendChild(quoteOwner);
	quoteDiv.appendChild(getQuote);
	content_area.appendChild(quoteDiv);
}

//////////////////////
function moreQuote ( ) {
	const quoteOwner  = document.getElementById("owner-id");
	const quoteId = document.getElementById("quote-id");
	const quoteArray = randomQuote();
	quoteId.innerHTML = quoteArray[0];
	quoteOwner.innerHTML = quoteArray[1];
}

export {
	getQuotes,
	moreQuote
} 