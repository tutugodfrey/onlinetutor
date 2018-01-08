
const LocalStorage = class  {
	setUserData(fieldName, data) {
		if(typeof localStorage === undefined) {	//browser does not support localstorage
			console.log("opp sorry your browser does not support localstorage");
			// access the application normally
		} 	else 	{
			localStorage.setItem(fieldName, data)
			console.log(`${fieldName} set`)
		}
	}

	getData(fieldName) {
		if(localStorage.getItem(fieldName)) {
			return localStorage.getItem(fieldName);
		} else {
			console.log(`${fieldName} not set in localStorage`)
		}
	}

	deleteData(fieldName) {
		if(localStorage.getItem(fieldName)) {
			localStorage.removeItem(fieldName);
			console.log('data deleted')
		} else {
			console.log(`${fieldName} not set in localStorage`)
		}
	}
}

export default LocalStorage;

// username = localStorage.getItem("username");

/*
//attempt to use local storage
			//first check if user is already using localstorage
			if(localStorage.getItem("username") === null) {		//user have not previously allow localstorage
			wantStorage = confirm("would you like the browser to store the data user for this application");
			if (wantStorage === true) {	//implement localstorage
			alert("we will implement localstorage for you");
			initiateStorage(path);
			 }	else if (wantStorage === false) {
			alert("we will not implement localstorage for you");
			getAndSend(displayContent, null);	//use normal way of accessing the application
			}
			}	else 	{	//username is already stored
			initiateStorage(path, compareUser);
			
			alert("username =" + username);
			//getAndSend(storeAjaxData);	//went the ajax return return it will be handle by storeajaxdata
			}

*/