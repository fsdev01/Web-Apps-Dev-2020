/**
	Author: Vinh Huynh 102125413
	Target: logout.htm
	Purpose: logout current user
**/

/**
 *	logout(): Display "logout message"
 **/
function displayLogoutMsg(){
	// Reset the result <div> box
	document.getElementById("result").innerHTML = "";

	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("GET", "manageLogin.php?action=displayMsg&unique=" + Number(new Date) , true);

	//Step 3: Assign callback function for async request
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			document.getElementById("result").innerHTML = xHRObject.responseText;
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(null);
}

// Initalises the login.htm page
function init(){
	displayLogoutMsg(); // Display Logout Message
}

window.onload = init;