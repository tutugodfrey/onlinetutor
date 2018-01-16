import domNotifier from './domNotifier';
import LocalStorage from './LocalStorage';
import { returnedLecturers } from "./HandleElementEvent";
const storeData = new LocalStorage();




const HandleContent = class {

	display(content) {
		if(document.getElementById("content-area")) {
			const contentAreaDiv = document.getElementById("content-area");
		  contentAreaDiv.innerHTML = content;
		  domNotifier();
		}
	}

	mainContent(content) {
		if(document.getElementById("main-content-area")) {
			const mainContentArea = document.getElementById("main-content-area");
		  mainContentArea.innerHTML = content;
		  domNotifier();
		}
	}

	header(content) {
		if(document.getElementById("header-div")) {
			const headerDiv = document.getElementById("header-div");
		  headerDiv.innerHTML = content;
		   domNotifier();
		}	
	}

	footer(content) {
		if(document.getElementById("footer-div")) {
			const footerDiv = document.getElementById("footer-div");
		  footerDiv.innerHTML = content;
		  domNotifier();
		}
	}

	chatMessage(content) {
		if(document.getElementById("chat-msg-div")) {
			const chatMsgDiv = document.getElementById("chat-msg-div");
			// check the status of the returned content
			if(content.indexOf("chat-post-succeed=true") > 0){
			  chatMsgDiv.innerHTML += content;
			  const chatTextField = document.getElementById("chat-text-field");
			  chatTextField.value = "";
			} else {
				console.log(content)
			}
		  domNotifier();
		}
	}

	showLecturers(content) {
		returnedLecturers(content);
	}

	notice(content) {
		console.log(content)
	}

	// dom_notifier();	//when elements becomes officially available call function to get their id's
}

export default HandleContent