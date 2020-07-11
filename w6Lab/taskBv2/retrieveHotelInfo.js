var xHRObject = false;

// Create XMLHttpRequest Object
if (window.XMLHttpRequest)
    xHRObject = new XMLHttpRequest();
else if (window.ActiveXObject)
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");

function retrieveInformation() 
{
	// Create city string such as "London"
	var city = document.getElementById("selectCity").value;
	// Create string to store type associated with the selected hotel: budget,standard or luxury
	var type = "";
	// Get list of all possible types of hotels [budget,standard,luxury]
	var input = document.getElementsByTagName("input");

	// Loop through possible types of hotels
	for (var i=0; i < input.length; i++)
	{ 
		// check the specific input element i
		// Same Syntax as: input.childNodes[i].value;
	    if (input.item(i).checked == true)
	        type = input.item(i).value;
	}

	// Create a ASYNC Request (third parameter is true)
	xHRObject.open("GET", "retrieveHotelInfo.php?id=" + Number(new Date) +"&city=" + city + "&type=" + type, true);
	// Assign callback function to onreadystatechange event
	xHRObject.onreadystatechange = function() {
		if (xHRObject.readyState == 4 && xHRObject.status == 200)
			// set retrieve input from server to div with "information" id
		   document.getElementById('information').innerHTML = xHRObject.responseText;
	}
	// Send Request to Server
	xHRObject.send(null); 
}









