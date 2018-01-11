import HandleContent from './HandleContent';
import AjaxCall from './AjaxCall';
const ajaxCall = new AjaxCall()
const handleContent = new HandleContent();

const handlePopState = function(poppedHistortData) {
		// poppedHistortData is the latest popstate
  const lengthOfString = poppedHistortData.length;
  const methodIndex = poppedHistortData.lastIndexOf("=") - 1;
  const checkFileAvailable = poppedHistortData.lastIndexOf("&");
  const urlString = poppedHistortData.substring(0, methodIndex);
  const methodIndicator = poppedHistortData.substring(methodIndex, lengthOfString);
  const fileAvailable = poppedHistortData.substring(checkFileAvailable, lengthOfString);
  if(methodIndicator.indexOf("post") > 1){
    const urlDelimiter = urlString.indexOf("?");
    const url = urlString.substring(0, urlDelimiter + 1);
    const msg = urlString.substring(urlDelimiter + 1, urlString.length);
    // will need to further modulate handling true false case for file
    ajaxCall.postMethod(url, msg,  handleContent.display, true, fileAvailable,  poppedHistortData);
  } else if(methodIndicator.indexOf("get") > 1 ){
    ajaxCall.getMethod(urlString, handleContent.display, true, poppedHistortData);
  }
}

export default handlePopState;