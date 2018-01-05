
const EventListener = class {
  newEvent(elementObject,  eventType, callBack, callBackArgument){
    if(elementObject.addEventListener){
      elementObject.addEventListener(eventType, function(event) {
      event.preventDefault(); 
        if(callBackArgument === undefined) {
          callback();
        } else {
          callBack(callBackArgument);
        }
      },
      false );
    } else if (element_object.attachEvent) {
      elementObject.attachEvent("on"+ eventType, function(event){
      event.preventDefault(); 
        if(callBackArgument === undefined) {
          callback();
        } else {
          callBack(callBackArgument);
        }
      });
    }
  }   //close the function block

    //use popstate to update the content area
  popState(callback) {
    let storedHistoryData;
    addEventListener(
      "popstate",
      function (e) {
        const storedHistoryData = e.state;
        callback(storedHistoryData)
      } 
    );    //end popstate
  }

}

export default EventListener;