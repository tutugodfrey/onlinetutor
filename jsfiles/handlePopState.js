import DisplayContent from './DisplayContent';
import AjaxCall from './AjaxCall';
const ajaxCall = new AjaxCall()
const displayContent = new DisplayContent();

const handlePopState = function(popHistortData) {
		// popHistortData is the latest popstate
  const lengthOfString = popHistortData.length;
  const methodIndex = popHistortData.lastIndexOf("=") - 1;
  const checkFileAvailable = popHistortData.lastIndexOf("&");
  const urlString = popHistortData.substring(0, methodIndex);
  const methodIndicator = popHistortData.substring(methodIndex, lengthOfString);
  const fileAvailable = popHistortData.substring(checkFileAvailable, lengthOfString);
  if(methodIndicator.indexOf("post") > 1){
    const urlDelimiter = urlString.indexOf("?");
    const url = urlString.substring(0, urlDelimiter + 1);
    const msg = urlString.substring(urlDelimiter + 1, urlString.length);
    // will need to further modulate handling true false case for file
    ajaxCall.postMethod(url, msg,  displayContent.display, popHistortData, fileAvailable);
  } else if(methodIndicator.indexOf("get") > 1 ){
    ajaxCall.getMethod(urlString, displayContent.display, popHistortData);
  }
}

export default handlePopState;