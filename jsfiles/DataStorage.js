import LocalStorage from "./LocalStorage"
import AjaxCall from './AjaxCall';
const storeData = new LocalStorage();
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
	        if(userType === 'lecturer') {
	        	console.log(`you are a ${userType}`);
	        	  ajaxCall.getMethod('/instructors/dashboard.php?dashboard', handleContent.display, true)
	        } else if(userType === 'student') {
	        	console.log(`you are a ${userType}`);
	        	  ajaxCall.getMethod('/students/dashboard.php?dashboard', handleContent.header, true)
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
	        if(userType === 'lecturer') {
	        	console.log(`you selected a ${userType}`);
	        	  // ajaxCall.getMethod('/instructors/dashboard.php?dashboard', handleContent.display, true)
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