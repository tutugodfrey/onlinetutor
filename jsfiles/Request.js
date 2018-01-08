import HandleContent from './HandleContent';
import GetElementValue from './GetElementValue';
import AjaxCall from './AjaxCall';
import LocalStorage from './LocalStorage';
const storeData = new LocalStorage();
const getElementValue = new GetElementValue();
const ajaxCall = new AjaxCall();
const handleContent = new HandleContent();

const Request = class {
  loadHeader(hrefEle){
    const url = '/onlinetutor/common/header1.html';
   setTimeout(ajaxCall.getMethod, 100, url, handleContent.header, false)
  }

  loadDefault(hrefEle){
    const url = '/onlinetutor/common/default.html';
    setTimeout(ajaxCall.getMethod, 300, url, handleContent.display)
  }

  loadFooter(hrefEle){
    const url = '/onlinetutor/common/footer1.html';
    setTimeout(ajaxCall.getMethod, 500, url, handleContent.footer, false)
  }

  hrefRequest(hrefEle){
    const data = getElementValue.linkValue(hrefEle);
    ajaxCall.getMethod(data[0], handleContent.display)
  } 

  formRequest(method, url, formInfo) {
    if(method === 'post') {
       console.log(formInfo)
      if(formInfo.indexOf('login') >= 0){
        const indexOfEqualSign = formInfo.lastIndexOf('=');
        const newUsername = formInfo.substring(indexOfEqualSign + 1, formInfo.length - 1);
        console.log(newUsername);
        if(storeData.getData('userData')) {
          const userData = storeData.getData('userData');
         // storeData.deleteData('userData')
          const username = JSON.parse(userData).username;
          if (username !== newUsername) {
            ajaxCall.postMethod(url, formInfo, handleContent.storeUserData, false, true, false);
          } else {
            // userData already in localStorage; use userData to make request and display appropriate content
            console.log('username already in store')
          }
        } else {
          ajaxCall.postMethod(url, formInfo, handleContent.storeUserData, false, true, false);
        }
      } else {  // include more if else block if need be to do something other than display with post result
        ajaxCall.postMethod(url, formInfo, handleContent.display, true, true, false);
      }
    } else if (method === 'get') {
      ajaxCall.getMethod();
    }
  }
    
}

export default Request;
