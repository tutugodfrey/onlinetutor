import DisplayContent from './DisplayContent';
import GetElementValue from './GetElementValue';
import AjaxCall from './AjaxCall';
const getElementValue = new GetElementValue();
const ajaxCall = new AjaxCall();
const displayContent = new DisplayContent();

const Request = class {
  hrefRequest(hrefEle){
    const data = getElementValue.linkValue(hrefEle);
    ajaxCall.getMethod(data[0], displayContent.display)
  } 
}

export default Request;
