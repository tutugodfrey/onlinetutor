import EventListener from './EventListener';
import GetElementValue from './GetElementValue';
import HandleElementEvent from './HandleElementEvent';
import { timer, clearRegBtn } from "./HandleElementEvent";
import formValueCollector from './formValueCollector';
import handlePopState from './handlePopState';
import {getQuotes, moreQuote} from "./quotes";
import Request from './Request';
import Chats from "./Chats";

const listener = new EventListener();
const getElementValue = new GetElementValue();
const handleElementEvent = new HandleElementEvent()
const request = new Request();
const chats = new Chats();

listener.popState(handlePopState);
let quoteDisplayed = false;
const domNotifier = function() {
	// console.log("dom notifier called");
	// listener.popState(handlePopState);
	if(document.getElementById('side-bar')) {
		if(!quoteDisplayed){
			quoteDisplayed = true;
			getQuotes();
		}
	}  

	if(document.getElementById('get-more-quote')) {
		const moreQuoteButton = document.getElementById("get-more-quote");
		listener.newEvent(moreQuoteButton, "click", moreQuote, moreQuoteButton );
	}  

	if(document.getElementById('header-div')) {
		const headerDiv = document.getElementById('header-div');
		listener.newEvent(window, "load", request.loadHeader, headerDiv );
	}

	if(document.getElementById('content-area')) {
		const contentAreaDiv = document.getElementById('content-area');
		listener.newEvent(window, "load", request.loadDefault, contentAreaDiv );
	}

	if(document.getElementById('footer-div')) {
		const footerDiv = document.getElementById('footer-div');
		listener.newEvent(window, "load", request.loadFooter, footerDiv );
	}

	if(document.getElementsByTagName("a")) {
	const linkEles = document.getElementsByTagName("a");
		for(let i = 0; i < linkEles.length; i++) {
		listener.newEvent(linkEles[i], "click", request.hrefRequest, linkEles[i] );
		}
	}

	// handle all form submission; forms button class include submit-buttons
	if(document.getElementsByClassName("btn")) {
		const submitBtn = document.getElementsByClassName("btn");
		for(let i = 0; i < submitBtn.length; i++ ) {
		listener.newEvent(submitBtn[i], "click", formValueCollector, submitBtn[i]);
		}
	}

	// when some input field fail validation
	if(document.getElementsByClassName("fail-validation")) {
		const inputEle = document.getElementsByClassName("fail-validation");
		for(let i = 0; i < inputEle.length; i++ ) {
		listener.newEvent(inputEle[i], "focus", handleElementEvent.modifyInputClass, inputEle[i]);
		}
	}


	if(document.getElementById("test_duration")) {
		const test_duration_id = document.getElementById("test_duration");
		const test_duration = test_duration_id.value;
		timer(test_duration, "time_left", formValueCollector);
	}

	if(document.getElementById('send_chat')) {
		const submitBtn = document.getElementById('send_chat');
		listener.newEvent(submitBtn, "click", formValueCollector, submitBtn);
		// check for update to chats
		// will work on updating chat uing web worker
		setInterval(chats.updateChat, 10000);
	}
  
  // when a user want to select a lecturer
	getElementValue.elementEvents("lecturers", "change", formValueCollector);

	// when a user want to add a new friend
	getElementValue.elementEvents("friend_id", "change", formValueCollector);

	if(document.getElementById("search-lecturers")){
		const textField = document.getElementById("search-lecturers");
		listener.newEvent(textField, "input", formValueCollector, textField);
		listener.newEvent(textField, "blur", clearRegBtn, textField);
	}

}	//end dom_notifier

export default domNotifier;