
import GetElementValue from './GetElementValue';
import changeAttribute from './changeAttribute';
import Request from './Request';
const request = new Request()
const elementType  = new  GetElementValue()

const formValueCollector = function(formControl) {
	console.log('eletype', formControl)
	const formElement = elementType.getFormElement(formControl)
	const method = formElement.method;
	const url = formElement.action;
	let allFieldsValidated = true;
	let radioChecked = false;
	let passwordArray = [];
	let checkValues = {};
	let checkFieldNames = [];
	let emailIsValid = false;
  let formInfo = '';
	// let formInfo = {};
	let formControls = formElement.elements;
  const emailRegExp = /\w+@\w+\.(net|com|org)/;
  const regex = /requiredFields/;
  for(let elem of formControls){
    let eleValue;
    const eleType = elem.type;
    console.log(eleType)
    let eleName = elem.getAttribute('name');
    if(elem.getAttribute('class')){
      const eleClass = elem.getAttribute('class');
     	if(eleClass.match(regex)){
	      let eleId
	      if(eleType === 'text'){
	        eleValue = elem.value.trim();
	        if(eleValue === '' || eleValue === ' '){
	            changeAttribute(elem, 'class', 'fail-validation form-control');
	            allFieldsValidated = false;
	        } else {
	        	if(eleName === 'email'){
		          emailIsValid = emailRegExp.test(elem.value);
		          if(!emailIsValid){
		            changeAttribute(elem, 'class', 'fail-validation form-control');
		            allFieldsValidated = false;
		          };
	        	} 
	        	formInfo = `${eleName}=${eleValue}&${formInfo}`;
	        	// formInfo[eleName] = eleValue
	        }  
	      } else if(eleType === "email") {
	      	eleValue = elem.value.trim();
	        if(eleValue === '' || eleValue === ' '){
	            changeAttribute(elem, 'class', 'fail-validation form-control');
	            allFieldsValidated = false;
	        } else {
	        	if(eleName === 'email'){
		          emailIsValid = emailRegExp.test(elem.value);
		          if(!emailIsValid){
		            changeAttribute(elem, 'class', 'fail-validation form-control');
		            allFieldsValidated = false;
		          };
	        	} 
	        	formInfo = `${eleName}=${eleValue}&${formInfo}`;
	        	// formInfo[eleName] = eleValue
	        }
	      } else if (eleType === 'password') {
	      	eleValue = elem.value.trim()
          if(eleValue === ''){
            changeAttribute(elem, 'class', 'fail-validation form-control');
            allFieldsValidated = false;
	        }  else {
	          passwordArray.push(eleValue);
	          // formInfo[eleName] = eleValue
	          formInfo = `${eleName}=${eleValue}&${formInfo}`;
	        }    
	      } else if (eleType === 'select-one'){
          eleValue = elementType.selectedValue(eleName);
          if(eleValue === "select" || eleValue === "default" || eleValue === ""){
            changeAttribute(elem, 'class', 'fail-validation form-control');
            allFieldsValidated = false;
        	} else {
        		// formInfo[eleName] = eleValue;
        		formInfo = `${eleName}=${eleValue}&${formInfo}`;
        	}
	      } else if ( eleType === 'radio' || eleType === 'checkbox') {
	      	if(checkFieldNames.indexOf(eleName) === -1) {
	      		checkFieldNames.push(eleName)
	      	}
	      	
          eleValue = elementType.getCheckboxValue(elem);
          if(eleValue !== undefined) {
            radioChecked = true;
            if(!checkValues.eleName) {
            	checkValues[eleName] = [eleValue];
            } else {
            	checkValue[eleName].push(eleValue)
            } 
          } /*else {
           	radioChecked = true;
          }*/
	      }
	    } else {
	    	// ele with class name but is not a requiredField
	    	if ( eleType === 'radio' || eleType === 'checkbox') {
	    		if(checkFieldNames.indexOf(eleName) === -1) {
	      		checkFieldNames.push(eleName)
	      	}
          eleValue = elementType.getCheckboxValue(elem);
          if(eleValue !== undefined) {
            radioChecked = true;
            if(!checkValues.eleName) {
            	checkValues[eleName] = [eleValue];
            } else {
            	checkValues[eleName].push(eleValue)
            } 
          } 
	      } else if(eleType === "hidden") {
	      	eleValue = elem.value;
	      	formInfo = `${eleName}=${eleValue}&${formInfo}`;
	    		// formInfo[eleName] = eleValue
	      } else {
	      	eleValue = elem.value;
	      	formInfo = `${eleName}=${eleValue}&${formInfo}`;
	    		// formInfo[eleName] = eleValue
	      }    	
	    }
   	} else {
   		// element with no class attributes
   		 if (eleType === 'select-one'){
          eleValue = elementType.selectedValue(eleName);
          if(eleValue === 'select' || eleValue === ''){
            changeAttribute(elem, 'class', 'fail-validation form-control');
            allFieldsValidated = false;
        	} else {
        		// formInfo[eleName] = eleValue;
        		formInfo = `${eleName}=${eleValue}&${formInfo}`;
        	}
	      } else if(eleType === "hidden") {
		      	eleValue = elem.value;
		      	formInfo = `${eleName}=${eleValue}&${formInfo}`;
		    		// formInfo[eleName] = eleValue
		    } else {
		    	eleValue = elem.value;
	      	formInfo = `${eleName}=${eleValue}&${formInfo}`;
		    }
   	}
  }

  // get values checkValues to formInfo
  if(checkFieldNames.length > 0) {
  	for(let checkboxName of checkFieldNames) {
  		if(checkValues[checkboxName]) {
  			let fieldValues = []
  			const checkedData = checkValues[checkboxName];
  			if(checkedData.length === 1){
  				fieldValues = checkedData[0][1]
  			} else {
  				for(let value of checkedData) {
	  				fieldValues.push(value[1])
	  			}
  			}
  		  formInfo = `${checkboxName}=${fieldValues}&${formInfo}`;
  			// formInfo[checkboxName] = fieldValues
  		} else {
  			console.log('not checked data')
  		}
  	}
  }

  if(radioChecked === false) {
    const radioEle = document.getElementById('radio-div');
    // bug fix needed here
    //changeAttribute([radioEle, 'class', 'failValidation form-group']);
    //allFieldsValidated = false;
  }
 
  if(passwordArray.length > 1){
    let confirmPassword;
    if(passwordArray.length === 2){
      confirmPassword = elementType.confirmPasswordValues(passwordArray[0], passwordArray[1])
    } else if (passwordArray.length === 3) {
      confirmPassword = elementType.confirmPasswordValues(passwordArray[1], passwordArray[2])
    }
    if(!confirmPassword){
      allFieldsValidated = false;
    } 
  }

  if(allFieldsValidated === false){
    const pElement = document.getElementById('validation-notice');
    pElement.style.visibility = 'visible';
    console.log('some fields require validation')
  }  else {
    // proceed with form submittion
    if(formInfo.lastIndexOf("&") === formInfo.length - 1){
    	formInfo = formInfo.substring(0, formInfo.length-1)
    }
    request.formRequest(method, url, formInfo)
  } 
}

export default formValueCollector