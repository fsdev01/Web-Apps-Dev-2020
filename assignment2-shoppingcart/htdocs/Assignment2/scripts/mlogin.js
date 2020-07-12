/**
	Author: Vinh Huynh 102125413
	Target: mlogin.htm
	Purpose: manager login
**/

/**
 *	managerLogin(): validates manager's login credentials
 *  Task 3.1 - Validate login against "manager.txt" file
 */
function managerLogin(){
	// Reset the result <div> box
	document.getElementById("result").innerHTML = "";

	// Extract user inputs from the HTML form using DOM manipulation
	let managerId = document.getElementById("managerId").value;
	let pwd = document.getElementById("pwd").value;

	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("POST", "processMLogin.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("login")+
						"&managerId=" + encodeURIComponent(managerId)+
						"&pwd=" + encodeURIComponent(pwd);

	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			// Store authentication response
			var serverResponse = xHRObject.responseText;

			// SUCCESS OUTCOME: User has correct login credentials
			// Disable the manager's ability to press the login button again
			// because he/she are already logged in 
			if(serverResponse.toLowerCase().indexOf('success') != -1 ){
				document.getElementById("loginBtn").disabled = true;
				document.getElementById("managerId").disabled = true;
				document.getElementById("pwd").disabled = true;

			}

			document.getElementById("result").innerHTML = serverResponse;
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}


/**
 * loginStatus():Checks whether manager is already logged in the system
 * Must check whether manager is logged in, before displaying login form.
 **/
function loginStatus(){
	
	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("GET", "manageLogin.php?action=managerLoginStatus&unique=" + Number(new Date), true);
	
	//Step 3: Assign callback function  
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){

			// Response stores the username of the current manager that is logged in
			var response =  xHRObject.responseText.toUpperCase();
			
			// CASE: Manager is logged in
			if(response != ""){
				// Display Message
				//alert("Manager " + response + " is already logged in.");
				
				// Disable User Input
				document.getElementById("loginForm").style.display = "none";

				// Show Message that user is already logged in
				var msg = "<strong class='successMsg'>Message:</strong> <span class='successMsg'>Manager '" + response + "' is already logged in.</span><br/>";
				msg += "<div class='successMenuOptions'><strong>Menu Options:</strong><br/>";
				msg += "<a href='listing.htm'>Listing</a> <br/>";
				msg += "<a href='processing.htm'>Processing</a> <br/>";
				msg += "<a href='logout.htm' onclick=\"logoutUser('manager')\">Logout</a></div>"

				document.getElementById("result").innerHTML =  msg;
			}
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(null);
}

// Function executed when the document loads
function init(){
	// Condition: Check whether manager is already logged in?
	loginStatus();
}
window.onload = init;