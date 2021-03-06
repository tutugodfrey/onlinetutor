import EventListener from "./EventListener";
const listener = new EventListener();
const GetElementValue = class {
	
	getFormElement(ele){
    let formElement;
    while(ele.toString() !== "[object HTMLFormElement]") {	
      //trying to get the form containing ele
      ele = ele.parentNode;
      if(ele.toString()  === "[object HTMLFormElement]"){
        formElement = ele;
        break;
      }
    }
    return formElement;
  } // getFormElement

	selectedValue(eleName) {
	  if(document.getElementsByName(eleName)){
	  	let eleValue;
	    const selectEle = document.getElementsByName(eleName);
	    for(let i = 0; i < selectEle.length; i++){
	     eleValue = (selectEle[i].value);
	     console.log(eleName, eleValue)
	    }
	    return eleValue;
	  } else {
	  	console.log(`Element with name ${ eleName } is not found in the dom`)
	  } 
	}

	linkValue(hrefEle) {
	  const href = hrefEle.getAttribute("href");
	  const hrefClass = hrefEle.getAttribute("class");
	  return [href, hrefClass];
	}

	//function to get the values of a checkbox and return it value
	//can be used for both checkbox and radio button
	getCheckboxValue(checkBoxEle){		
		if(checkBoxEle.checked){
			const eleValue = checkBoxEle.value;
			const eleName = checkBoxEle.name;
			return [eleName, eleValue];
		} else {
			return
		}
	}
  
	inputValue(eleId) {
  	let eleObj;
  	let eleName;
  	let eleValue;
		if(document.getElementById(eleId)){
			eleObj = document.getElementById(eleId);
			eleName = eleObj.name;
		  eleNalue = eleObj.value;
		} else {
			return 'No element with the given id'
		}
		return [eleName, eleValue];
	}
  
  fileUpload(fileEle) {
		const fileObj = fileEle.files;
		if(fileObj === undefined){
		return;
		}	else {
			let submittedFiles;
			const eleName = fileEle.name;
			if(fileObj.length  === 1){
				submittedFiles = fileObj[0];
			}	else if (fileObj.length > 1)	{
				submittedFiles = []
				for (i = 0; i < fileObj.length; i++){
				submittedFiles.push(fileObj[i]);
				}
			}
			return [eleName, submittedFiles];
		}	
	}		//end file_upload

	elementEvents(eleId, eventType, callback) {
		// get select field for lecturer name
		if(document.getElementById(eleId)) {
			console.log('Your lecturers are ready');
			const eleType = document.getElementById(eleId);
			listener.newEvent(eleType, eventType, callback, eleType);
		}
	}

	//confirm password
	confirmPasswordValues(passWord1, passWord2){
	  if(passWord1 !== passWord2){
	    return false;
	  }
	  return true;
	}


} // end class definition


export default GetElementValue;