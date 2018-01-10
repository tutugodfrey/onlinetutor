import HandleContent from './HandleContent';
import GetElementValue from './GetElementValue';
import DataStorage from './DataStorage'
import AjaxCall from './AjaxCall';
import LocalStorage from './LocalStorage';
const dataStorage = new DataStorage()
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
      if(formInfo.indexOf('login') >= 0){
        const indexOfEqualSign = formInfo.lastIndexOf('=');
        const newUsername = formInfo.substring(indexOfEqualSign + 1, formInfo.length);
        if(storeData.getData('userData')) {
          const userData = storeData.getData('userData');
         // storeData.deleteData('userData')
          const username = JSON.parse(userData).username;
          const userType = JSON.parse(userData).user_type;
          if (username !== newUsername) {
            ajaxCall.postMethod(url, formInfo, dataStorage.storeUserData, false, true, false);
          } else {
            // userData already in localStorage; use userData to make request and display appropriate content
            console.log('username already in store')
            if(userType === "student") {
              // '../students/dashboard.php?dashboard' '/onlinetutor/students/dashboard.php?dashboard'
              ajaxCall.getMethod('/onlinetutor/students/dashboard.php?dashboard', handleContent.header, true)
            } else if (userType === "lecturer") {
              ajaxCall.getMethod('./../instructors/dashboard.php?dashboard', handleContent.header, true)
            }
          }
        } else {
          ajaxCall.postMethod(url, formInfo, dataStorage.storeUserData, false, true, false);
        }
      } else {  // include more if else block if need be to do something other than display with post result
        ajaxCall.postMethod(url, formInfo, handleContent.display, true, true, false);
      }
    } else if (method === 'get' || "GET") {
      if(fullUrl.indexOf("select=Select Lecturer") > 0) {
        // student select a lecturer
        ajaxCall.getMethod(fullUrl, dataStorage.storeInsturctorData, false);
      } else {
        ajaxCall.getMethod(fullUrl, handleContent.display, false);
      }
    }
  }
    
}

export default Request;
