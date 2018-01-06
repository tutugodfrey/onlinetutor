import DisplayContent from './DisplayContent';
import GetElementValue from './GetElementValue';
import AjaxCall from './AjaxCall';
const getElementValue = new GetElementValue();
const ajaxCall = new AjaxCall();
const displayContent = new DisplayContent();

const Request = class {
  loadHeader(hrefEle){
    const url = '/onlinetutor/common/header1.html';
   setTimeout(ajaxCall.getMethod, 500, url, displayContent.header)
  }

  loadDefault(hrefEle){
    const url = '/onlinetutor/common/default.html';
    setTimeout(ajaxCall.getMethod, 1000, url, displayContent.display)
  }

  loadFooter(hrefEle){
    const url = '/onlinetutor/common/footer1.html';
    setTimeout(ajaxCall.getMethod, 1500, url, displayContent.footer)
  }

  hrefRequest(hrefEle){
    const data = getElementValue.linkValue(hrefEle);
    ajaxCall.getMethod(data[0], displayContent.display)
  } 
}

export default Request;
