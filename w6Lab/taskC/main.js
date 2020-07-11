// Reference: Week 5 Lecture Code
// Note: the current <div id="title"> won't be cleared.

function getDoc()
{

	var request = new XMLHttpRequest(); // Create XHR object
	
	// To ensure that responses sent as XML are property handled
	if(request.overrideMimeType){
		request.overrideMimeType("text/xml");
	}

	// Check XHR object was successfully created
	if(request){
		// This will cause the xml file in the /htdoc folder to be sent
		// variable 'request' when the XHR GET request is sent
		// Set parameters for the HTTP request
		request.open("GET","temperature.xml",true);
		request.onreadystatechange = function()
		{
			if( (request.readyState == 4) && (request.status == 200) )
			{
				var xmlDocument = request.responseXML;
				// Now we call the method displayClasses to extract XML data
				displayClasses(xmlDocument);

			}
		}
		request.send(null); // send HTTP request
	}
}

	// doc - XML DOM object acquired from server
	function displayClasses(doc){


		// Get date elements and temp elements
		var dateNodeList = doc.getElementsByTagName('date'); // list of dates
		var tempNodeList = doc.getElementsByTagName('maxtemp'); // list of max tempatures
		var count = dateNodeList.length; // Number of weather events
		var total = 0; // Aggregate the maxtemp


		//Traverse each node in the List
		for(var i = 0; i < dateNodeList.length ;i++)
		{

			// Get the date value
			var dateValue = dateNodeList[i].childNodes[0].nodeValue;

			// get the maxtemp value
			var tempValue = tempNodeList[i].childNodes[0].nodeValue;

			// update to running total
			total = total + parseFloat(tempValue);

			// create <p> to store string
			var newElement = document.createElement("p"); // create <p> ... </p>
			var newStr = dateValue + " : " + tempValue;
			var newTextNode = document.createTextNode(newStr); // create textnode
			newElement.appendChild(newTextNode); //add textnode to <p> node

			// update <div> element from HTMl page: add <p> to <div>
			document.getElementById("title").appendChild(newElement);

		}

		// create <p> to store average string
		var avg = total/count; 
		var newElement = document.createElement("p"); // create <p> ... </p>
		var newStr = "Average Tempeature is "+ " : " + avg;
		var newTextNode = document.createTextNode(newStr);  // create textnode
		newElement.appendChild(newTextNode); //add textnode to <p> node
		// update <div> element from HTMl page: add <p> to <div>
		document.getElementById("title").appendChild(newElement);

		

	}


function init(){
	var myButton = document.getElementById("reqDoc");
	myButton.onclick = getDoc;


}



window.onload = init;