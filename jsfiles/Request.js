import DisplayContent from './DisplayContent';
import GetElementValue from './GetElementValue';
import AjaxCall from './AjaxCall';
const getElementValue = new GetElementValue();
const ajaxCall = new AjaxCall();
const displayContent = new DisplayContent();

const Request = class {
  loadHeader(hrefEle){
    const url = '/onlinetutor/common/header1.html';
   setTimeout(ajaxCall.getMethod, 100, url, displayContent.header, false)
  }

  loadDefault(hrefEle){
    const url = '/onlinetutor/common/default.html';
    setTimeout(ajaxCall.getMethod, 200, url, displayContent.display)
  }

  loadFooter(hrefEle){
    const url = '/onlinetutor/common/footer1.html';
    setTimeout(ajaxCall.getMethod, 300, url, displayContent.footer, false)
  }

  hrefRequest(hrefEle){
    const data = getElementValue.linkValue(hrefEle);
    ajaxCall.getMethod(data[0], displayContent.display)
  } 
}

export default Request;
