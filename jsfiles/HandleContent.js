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

	storeUserData(content) {
		// don't just store the content verify that login is successful before storing
		try {
			const userData = JSON.parse(content);
			if (userData["username"]) {
				// login successful
				try {
					storeData.setUserData('userData', content);
					const userType = userData["user_type"];
					console.log(userType)
          if(userType === 'lecturer') {
          	console.log(`you are a ${userType}`);
          } else if(userType === 'student') {
          	console.log(`you are a ${userType}`);
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
	// dom_notifier();	//when elements becomes officially available call function to get their id's
}

export default HandleContent