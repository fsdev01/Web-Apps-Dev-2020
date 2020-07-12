/**
	Author: Vinh Huynh 102125413
	Target: login.htm
	Purpose: customer login
**/

/**
 * customerLogin(): validates customers's login credentials
 * Task 4.1 - Login Credentials are validated at the server-side.
 * 
 */
function customerLogin(){

	// Reset the result <div> box
	document.getElementById("result").innerHTML = "";

	// Extract user inputs from the HTML form using DOM manipulation
	let email = document.getElementById("email").value;
	let pwd = document.getElementById("pwd").value;

	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	// Reference: Slide 28 - Week 6 Lecture Notes
	xHRObject.open("POST", "processLogin.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("login")+
						"&email=" + encodeURIComponent(email)+
						"&pwd=" + encodeURIComponent(pwd);

	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){

			var serverResponse = xHRObject.responseText; // Store the server's response
			var divRef = document.getElementById("result"); // Get reference to <div> element in HTML
			
			// Condition: Check whether login was successful?
			// Case 1: Login Credentials are correct
			if(serverResponse == "SUCCESS"){
				 divRef.innerHTML = "<strong>Status:</strong> Customer Succesfully Logged in.<br/>" +
				 		  "<strong>Redirecting to Buying Page ...</strong> ";
				// Redirecting to "buying.htm" page
				setTimeout(function(){window.location.replace("buying.htm");},1000);
			// Case 2: Login Credentials are wrong. Display Error Messages.
			}else {
				divRef.innerHTML = serverResponse;
			}
		}
	}

	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}

/**
 * loginStatus(): Checks whether customer is already logged in the system
 * Must check whether customer is logged in, before displaying login form.
 */
function loginStatus(){
	
	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("GET", "manageLogin.php?action=customerLoginStatus&unique=" + Number(new Date), true);
	
	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){

			// response stores the username of the current customer that is logged in
			var response =  xHRObject.responseText;
			
			// CASE: Customer is logged in
			if(response != ""){
				//alert("Customer: " + response + " is already logged in. Redirecting to buying page");
				
				// Disable User Input
				document.getElementById("loginForm").style.display = "none";

				// Show Message that user is alreay logged
				var msg = "<p><strong>Warning:</strong> Customer " + response + " is already logged in.</p>";
				msg += "<p><em>Redirecting to the 'buying' page ...</em></p>";
				document.getElementById("result").innerHTML =  msg;

				// Redirecting to "buying.htm" page
				setTimeout(function(){window.location.replace("buying.htm");},2000);
			}

		}
	}

	//Step 4: Send HTTP Request to Server
	xHRObject.send(null);
}




// Function executed when the document loads
function init(){
	// Condition: Check whether customer is already logged in?
	loginStatus();

}
window.onload = init;