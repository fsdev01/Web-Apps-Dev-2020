
// Create XMLHttpRequest object
xhr = new XMLHttpRequest();


function getResults(){
	// prepare arguments for XHR object
	// async - third argument is true
	xhr.open("POST", "conveners.php", true); 
	// assign callback function
	xhr.onreadystatechange = getData;
	// Send HTTP GET Request to server
	xhr.send(null);
}


function getData () {
	if ((xhr.readyState == 4) &&(xhr.status == 200)){
		document.getElementById("results").innerHTML = xhr.responseText;
	} 
}