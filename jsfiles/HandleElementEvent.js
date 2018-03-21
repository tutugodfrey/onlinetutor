import formValueCollector from "./formValueCollector";
import changeAttribute from "./changeAttribute";
// import domNotifier from "./domNotifier";

const HandleElementEvent = class {
	selectLecturer(eleType){
		console.log(eleType)
	}

	modifyInputClass(inputEle){
		let classValue = inputEle.getAttribute("class");
		const eleClasses = classValue.split(" ");
		let newClassValue = "";
		for(let eleClass of eleClasses) {
			if(eleClass === "fail-validation"){
				eleClass = "requiredFields"; 
				newClassValue = `${newClassValue}${eleClass} `;
			} else {
				newClassValue = `${newClassValue}${eleClass} `;
			}
		} 
			newClassValue = newClassValue.trim()
		 	inputEle.setAttribute("class", newClassValue);
		 	const failedInputs = document.getElementsByClassName("fail-validation");
		 	if(failedInputs.length === 0) {
		 		const pElement = document.getElementById('validation-notice');
   			pElement.style.visibility = 'hidden';
		 	}
		 	// domNotifier();
	}
}

function timer(timeDuration, displayEle, timeupFunc){
		// func_timeup is function to call when timeup
	const timerNotice = document.getElementById(displayEle);
	let duration = parseInt(timeDuration);
	let secCount = 0;
	duration = 1;
  let durationSec = duration * 60;
	
	//milisecond = duration * 60 * 1000;
	const timeUp = setInterval(countDown,  1000);
	function countDown(){
		if (duration >= 0 && secCount === 0) {
		duration = duration - 1;
		secCount = 60;
		} 
		secCount--;
		timerNotice.innerHTML = duration + ":" + secCount;

		if (duration === 0 && secCount === 0 ){
			clearInterval(timeUp);
			if(typeof timeupFunc != undefined){
				const testSubmitId = "submitTest";
				const testSubmitBtn = document.getElementById("submitTest");
				timeupFunc(testSubmitBtn);
			} else {
				console.log("no time up function");
			}
		}
	}
}

//when search for lecturer result returns
function returnedLecturers (returnedLecturers) {
	const newLec = document.getElementById("lecturers-name");
	const registerBtn = document.getElementById("register-lecturer");
	console.log("returned", returnedLecturers);
	console.log(typeof returnedLecturers);
	if((returnedLecturers.trim() === "p") || (returnedLecturers.indexOf("Not Found") >= 0 )) {
		console.log("No lecturer returned");
		console.log(returnedLecturers);
		changeAttribute(registerBtn, "class", "hide-item", "no");
	} else {
		newLec.innerHTML =  returnedLecturers;
		changeAttribute(newLec, "class", "show-item", "no");
		changeAttribute(registerBtn, "class", "btn btn-success btn-sm show-item", "no");
	}
}

function clearRegBtn (eleObj) {
	const eleValue = eleObj.value.trim();
	const newLec = document.getElementById("lecturers-name");
	const registerBtn = document.getElementById("register-lecturer");
	if(eleValue === "" ){
		changeAttribute(newLec, "class", "hide-item", "no");
		changeAttribute(registerBtn, "class", "hide-item", "no");
	}	else	{
		console.log(eleValue);
	}
}




export default HandleElementEvent;
export {
	timer,
	returnedLecturers,
	clearRegBtn
}