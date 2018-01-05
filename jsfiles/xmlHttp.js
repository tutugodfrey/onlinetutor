const createXmlHttpRequest = function() {
		let xmlHttp;
		try {
			xmlHttp = new XMLHttpRequest();
		} catch(e) {
		const XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.5.0",
						"MSXML2.XMLHTTP", "Microsoft.XMLHTTP");
			for(let i = 0; i < XmlHttpVersions.length && !xmlHttp; i++){
				try {
					xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
				} catch(e){}
			}
		}
		if(!xmlHttp){
			console.log("Error creating the XMLHttpRequest object.");
		} else {
			return xmlHttp;
		}
	}

export default createXmlHttpRequest;