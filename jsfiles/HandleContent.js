import domNotifier from './domNotifier';
import LocalStorage from './LocalStorage';
const storeData = new LocalStorage();




const HandleContent = class {

	display(content) {
		if(document.getElementById("main-content")) {
			const mainContentDiv = document.getElementById("main-content");
		  mainContentDiv.innerHTML = content;
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

	notice(content) {
		console.log(content)
		
	}

	// dom_notifier();	//when elements becomes officially available call function to get their id's
}

export default HandleContent