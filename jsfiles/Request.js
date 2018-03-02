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
    // const url = '/common/header1.html';
    const url = 'common/header1.html';
   setTimeout(ajaxCall.getMethod, 100, url, handleContent.header, false)
  }

  loadDefault(hrefEle){
    // const url = '/common/default.html';
    const url = 'common/default.html';
    setTimeout(ajaxCall.getMethod, 500, url, handleContent.display)
  }

  loadFooter(hrefEle){
    const url = './footer1.html';
    setTimeout(ajaxCall.getMethod, 1000, url, handleContent.footer, false)
  }

  hrefRequest(hrefEle){
    const data = getElementValue.linkValue(hrefEle);
    const href = data[0];
    if(href.substring(href.length - 1) === "#") {
      // ignore the link
    } else if(href.indexOf("signup") >= 0 || href.indexOf("login") >= 0 || href.indexOf("default") >= 0 || href.indexOf("home") > 0  
      || href.indexOf("forget_password") >= 0 || href.indexOf("change") >= 0 ) {
      ajaxCall.getMethod(href, handleContent.display)
    } else {
      ajaxCall.getMethod(href, handleContent.mainContent, true)
   }
  } 

  formRequest(method, url, formInfo) {
    // console.log(url, formInfo)
    if(method === 'post') {
      console.log(formInfo)
      if(formInfo.indexOf('login') >= 0){
        const indexOfEqualSign = formInfo.lastIndexOf('=');
        const newUsername = formInfo.substring(indexOfEqualSign + 1, formInfo.length);
        if(storeData.getData('userData')) {
          const userData = storeData.getData('userData');
         // storeData.deleteData('userData')
          const username = JSON.parse(userData).username;
          const userType = JSON.parse(userData).user_type;
          const userId = JSON.parse(userData).id;
          if (username !== newUsername) {
            ajaxCall.postMethod(url, formInfo, dataStorage.storeUserData, false, false);
          } else {
            // userData already in localStorage; use userData to make request and display appropriate content
            console.log('username already in store')
            if(userType.toLowerCase() === "student") {
              // '../students/dashboard.php?dashboard' '/onlinetutor/students/dashboard.php?dashboard' ./../students/dashboard.php?dashboard
              ajaxCall.getMethod(`./../students/dashboard.php?dashboard&user_id=${userId}`, handleContent.header, false);
              setTimeout(ajaxCall.getMethod, 5000, './../students/home.php', handleContent.display, true);
            } else if (userType.toLowerCase() === "lecturer") {
              ajaxCall.getMethod(`./../instructors/dashboard.php?dashboard&user_id=${userId}`, handleContent.header, false);
              setTimeout(ajaxCall.getMethod, 5000, './../instructors/home.php', handleContent.display, true);
            }
          }
        } else {
          ajaxCall.postMethod(url, formInfo, dataStorage.storeUserData, false, false);
        }
      } else if (formInfo.indexOf('send_chat_msg') >= 0) {
        ajaxCall.postMethod(url, formInfo, handleContent.chatMessage, false, false);
      } else if(formInfo.indexOf("register_lecturer") >= 0){
        ajaxCall.postMethod(url, formInfo, handleContent.mainContent, false, false);
      } else {  // include more if else block if need be to do something other than display with post result
        ajaxCall.postMethod(url, formInfo, handleContent.display, true, false);
      }
    } else if (method === 'get' || "GET") {
      const fullUrl = `${url}?${formInfo}`;
      // console.log(fullUrl);
      if(fullUrl.indexOf("select=Select Lecturer") > 0) {
        // student select a lecturer
        ajaxCall.getMethod(fullUrl, dataStorage.storeInsturctorData, false);
      }  else if(fullUrl.indexOf("search_lecturers") >= 0) {
        console.log(fullUrl)
        console.log("what to search for lecturers");
        ajaxCall.getMethod(fullUrl, handleContent.showLecturers, false);
      }  else {
        ajaxCall.getMethod(fullUrl, handleContent.display, false);
      }
    }
  }
    
}

export default Request;
