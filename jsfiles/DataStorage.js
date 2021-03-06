import LocalStorage from "./LocalStorage"
import HandleContent from "./HandleContent"
import AjaxCall from './AjaxCall';
import changeAttribute from "./changeAttribute";
const storeData = new LocalStorage();
const handleContent = new HandleContent();
const ajaxCall = new AjaxCall(); 

const DataStorage = class {
	storeUserData(content) {
		// don't just store the content verify that login is successful before storing
		try {
			const userData = JSON.parse(content);
			if (userData["username"]) {
				// login successful
				try {
					storeData.setUserData('userData', content);
					const userType = userData["user_type"];
	        if(userType.toLowerCase() === 'lecturer') {
	        	console.log(`you are a ${userType}`);
	        	ajaxCall.getMethod('./../instructors/dashboard.php?dashboard', handleContent.header, true);
	        	setTimeout(ajaxCall.getMethod, 5000, './../instructors/home.php', handleContent.display, true);
	        } else if(userType.toLowerCase() === 'student') {
	        	console.log(`you are a ${userType}`);
	        	ajaxCall.getMethod('./../students/dashboard.php?dashboard', handleContent.header, true);
	        	setTimeout(ajaxCall.getMethod, 1000, './../students/home.php', handleContent.display, true);
	        } else {
	        	console.log('Unknown user_type');
	        }	
				} catch (error) {
					console.log(error)
				}		
		  }
		} catch(error) {
			console.log(error);
			console.log(content)
		}
	}

	storeInsturctorData(content) {
		try {
			const instructorData = JSON.parse(content);
			if (instructorData["user_type"] === "lecturer") {
				// student select a lecturer
				try {
					storeData.setUserData('instructorData', content);
					const userType = instructorData["user_type"];
					const instructorId = instructorData["id"];
					const instructorPhoto = instructorData["picture"];
					console.log(userType);
					console.log(instructorId);
	        if(userType === 'lecturer') {
	        	console.log(`you selected a ${userType}`);
	        	if(document.getElementById("profile-link")) {
	        		const hrefItem = document.getElementById("profile-link");
	        		let hrefValue = hrefItem.getAttribute("href");
	        		hrefValue = hrefValue.substring(0, hrefValue.lastIndexOf("=") + 1);
	        		hrefValue = `${hrefValue}${instructorId}`;
	        		hrefItem.setAttribute("href", hrefValue);
	        	}
	        	if(document.getElementById("instructor-profile-pix")) {
	        		console.log(instructorPhoto);
	        		const imgElem = document.getElementById("instructor-profile-pix");
	        		changeAttribute(imgElem, "src", instructorPhoto);
	        	}
	        } else {
	        	console.log('Unknown user_type');
	        }	
				} catch (error) {
					console.log(error)
				}		
		  }
		} catch(error) {
			console.log(error);
			console.log(content)
		}
	}
}


export default DataStorage;