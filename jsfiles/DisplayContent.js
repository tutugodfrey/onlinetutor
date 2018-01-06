import domNotifier from './domNotifier';
const DisplayContent = class {
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
		console.log('footer')
		if(document.getElementById("footer-div")) {
			const footerDiv = document.getElementById("footer-div");
		  footerDiv.innerHTML = content;
		  domNotifier();
		}
	}
	// dom_notifier();	//when elements becomes officially available call function to get their id's
}

export default DisplayContent