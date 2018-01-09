
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
