import EventListener from './EventListener';
import GetElementValue from './GetElementValue';
import HandleElementEvent from './HandleElementEvent'
import formValueCollector from './formValueCollector';
import handlePopState from './handlePopState';
import Request from './Request';
const listener = new EventListener();
const getElementValue = new GetElementValue();
const handleElementEvent = new HandleElementEvent()
const request = new Request();

listener.popState(handlePopState);

const domNotifier = function() {
// listener.popState(handlePopState);

if(document.getElementById('header-div')) {
	const headerDiv = document.getElementById('header-div');
	listener.newEvent(window, "load", request.loadHeader, headerDiv );
}

if(document.getElementById('main-content')) {
	const mainContentDiv = document.getElementById('main-content');
	listener.newEvent(window, "load", request.loadDefault, mainContentDiv );
}

if(document.getElementById('footer-div')) {
	const footerDiv = document.getElementById('footer-div');
	listener.newEvent(window, "load", request.loadFooter, footerDiv );
}

if(document.getElementsByTagName("a")){
const linkEles = document.getElementsByTagName("a");
	for(let i = 0; i < linkEles.length; i++) {
	listener.newEvent(linkEles[i], "click", request.hrefRequest, linkEles[i] );
	}
}

// handle all form submission; forms button class include submit-buttons
if(document.getElementsByClassName("submit-buttons")){
	const submitBtn = document.getElementsByClassName("submit-buttons");
	for(let i = 0; i < submitBtn.length; i++ ) {
	listener.newEvent(submitBtn[i], "click", formValueCollector, submitBtn[i]);
	}
}

//list for event
// getElementValue.elementEvents("lecturers", "change", handleElementEvent.selectLecturer)
getElementValue.elementEvents("lecturers", "change", formValueCollector)

}	//end dom_notifier

export default domNotifier;