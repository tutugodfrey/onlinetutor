
const storeHistory = function(url, method, storeUrl = true, poppedHistortData, info) {
		console.log('poppedHistoryData', poppedHistortData);
		if(storeUrl === false) {
			return
		} else {
			let historyData;
		  if(method === 'post') {
		  	const msg = info[0];
		  	const fileAvailable = info[1];
		  	historyData = `${url}${msg}m=post&${fileAvailable}`;
		  } else if (method === 'get') {
		  	historyData = `${url}m=get`;
		  }
			if (typeof poppedHistortData === "undefined") {
				console.log('no pop data')
			  history.pushState(historyData, "newurl", url);
			}	else	{
				if(poppedHistortData === historyData) {
					console.log('right here')
					//do nothing ie do not store the same historyData
				}	else {
				  history.pushState(historyData, "newurl", url);
				}
		  }
		}
	}

export default storeHistory;