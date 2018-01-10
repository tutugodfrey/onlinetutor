
const HandleElementEvent = class {
	selectLecturer(eleType){
		console.log(eleType)
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


export default HandleElementEvent;
export {
	timer
}