
import EventListener from './EventListener';
import handlePopState from './handlePopState';
import Request from './Request';
const listener = new EventListener();
const request = new Request();

listener.popState(handlePopState);

const domNotifier = function() {

if(document.getElementsByTagName("a")){
const linkEles = document.getElementsByTagName("a");
	for(let i = 0; i < linkEles.length; i++) {
	listener.newEvent(linkEles[i], "click", request.hrefRequest, linkEles[i] );
	}
}

}	//end dom_notifier

domNotifier();