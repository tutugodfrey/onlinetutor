
const ajaxBusy = function(xmlHttp) {
	let indicator;
	if(document.getElementById("ajax_neutral")){
			if(xmlHttp.readyState !== 4){
				indicator = document.getElementById("ajax_neutral");
				indicator.setAttribute("id", "ajax_busy");
			}
	} else if (document.getElementById("ajax_busy")) {
		if (xmlHttp.readyState === 4) {
			indicator = document.getElementById("ajax_busy");
			indicator.setAttribute("id", "ajax_neutral");
		}
	} else {
		console.log('element with id ajax_neutral not found')
	}
 }

export default ajaxBusy;