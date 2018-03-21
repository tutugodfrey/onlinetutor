export const changeAttribute  = function(eleObject, attrToChange, newAttr, removeAttr) {
  const eleAttr = eleObject.getAttribute(attrToChange);
  if (eleAttr === null || eleAttr !== newAttr) 	{
    //set the attribute and the image become large
    eleObject.setAttribute(attrToChange, newAttr);
  } else if (eleAttr === newAttr && removeAttr === "yes") {
    eleObject.removeAttribute(attrToChange);
  }  else if (eleAttr === newAttr && removeAttr === "no") {
    //no action taken
  } else if(eleAttr === newAttr && (removeAttr !== "yes" || removeAttr !== "no" || removeAttr !== "undefined")){
    eleObject.setAttribute(attrToChange, removeAttr);
  } 
}		//end changeClass

export default changeAttribute;