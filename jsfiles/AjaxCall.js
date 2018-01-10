import createXmlHttpRequest from './xmlHttp';
import storeHistory from './storeHistory';
import ajaxBusy from './ajaxBusy';

const xmlHttp = createXmlHttpRequest();


const AjaxCall = class {
	getMethod (url, callback, storeUrl, poppedHistortData) {
		 console.log("url", url)
		if(xmlHttp.readyState === 0 || xmlHttp.readyState === 4){
			xmlHttp.open("GET", url, true);
			xmlHttp.onreadystatechange = function()	{
				//processing indicator
				ajaxBusy(xmlHttp);
				if(xmlHttp.readyState === 4){
					if(xmlHttp.status === 200){
						const headers = xmlHttp.getAllResponseHeaders();
						const contentType  = xmlHttp.getResponseHeader("content-type");
						storeHistory(url, 'get', storeUrl, poppedHistortData);
						let textResponse;
						if(contentType === "text/xml") {
							textResponse = xmlHttp.responseXML;
						}	else {
							textResponse = xmlHttp.responseText;
						}
						callback(textResponse);
					} else {
					console.log(`There was a problem accessing the server:  ${ xmlHttp.statusText }`);
					}
				}
			}
			xmlHttp.send(null);
		}
	}

	postMethod (url, msg, callback, storeUrl, poppedHistortData, fileAvailable = true) {
		console.log("url", url);
		console.log(msg)
		if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4){
			xmlHttp.open('POST', url, true);		//now set request headers for the post method
			if(fileAvailable === false) {	
				//prevent form submission with FormData()
				xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlHttp.setRequestHeader("Accept", "application/json");
				// xmlHttp.setRequestHeader("Content-type", "application/json");
			}
			// xmlHttp.setRequestHeader("Content-length", msg.length);
			// xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.onreadystatechange = function()	{
			//processsing indicator
			ajaxBusy(xmlHttp);
			if(xmlHttp.readyState == 4){
				if(xmlHttp.status == 200){
					const headers = xmlHttp.getAllResponseHeaders();
					const contentType  = xmlHttp.getResponseHeader("content-type");
					// storeHistory(url, 'post', storeUrl, poppedHistortData, [msg, fileAvailable]);
					let textResponse;
					if(contentType === "text/xml") {
						textResponse = xmlHttp.responseXML;
					}	else {
						textResponse = xmlHttp.responseText;
					}
					// console.log(JSON.parse(textResponse))
					console.log(content)
					callback(textResponse);
					} else {
						console.log(`There was a problem accessing the server: ${xmlHttp.statusText}`);
					}
				}
			}
			xmlHttp.send(msg);
		}
	}

}

    
export default AjaxCall
 