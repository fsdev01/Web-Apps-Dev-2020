function getInfo(){

	// Create XMLHttpRequest Object
	xhttp = new XMLHttpRequest();

	// Setup parameters for XMLHttpRequest Object
	// Note: async -  third argument is true
	xhttp.open("GET", "XMLTransform.php", true);

	//Assign Callback Function to XHR object
	xhttp.onreadystatechange = function(){
		if(xhttp.readyState == 4 && xhttp.status == 200){
			// Get HTML result from PHP server and assign to HTML
			document.getElementById("result").innerHTML = xhttp.responseText;
		}
	}
	
	// Send HTTP GET request to PHP Server
	xhttp.send();

}