
const DisplayContent = class {
	display(content) {
		if(document.getElementById("main_content")) {
			const myDiv = document.getElementById("main_content");
		  myDiv.innerHTML = content;
		}
	}
	// dom_notifier();	//when elements becomes officially available call function to get their id's
}

export default DisplayContent