
const storeHistory = function(url, method, popHistortData, info) {
    let historyData;
	  if(method === 'post') {
	  	const msg = info[0];
	  	const fileAvailable = info[1];
	  	historyData = `${url}${msg}m=post&${fileAvialable}`;
	  } else if (method === 'get') {
	  	historyData = `${url}m=get`;
	  }
		if (typeof popHistortDataa === null) {
		  history.pushState(historyData, "newurl", url);
		}	else	{
			if(popHistortData === historyData) {
				console.log('right here')
				//do nothing ie do not store the same historyData
			}	else {
			  history.pushState(historyData, "newurl", url);
			}
	  }
	}

export default storeHistory;