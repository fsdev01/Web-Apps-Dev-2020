/**
	Author: Vinh Huynh 102125413
	Target: register.htm
	Purpose: Validate inputs form data and add new customer record
**/

/**
 * addCustomer(): adds new customer at the server-side
 * Task 2.3: Send HTTP Request to Server using AJAX.
 * The server will:
 *  (1) Perform validation
 *  (2) Add new customer to customer.xml file
 *  
 */
function addCustomer(){

	// Reset the result <div> box
	document.getElementById("result").innerHTML = "";

	// Extract user inputs from the HTML form using DOM manipulation
	let email = document.getElementById("email").value;
	let firstName = document.getElementById("firstName").value;
	let lastName = document.getElementById("lastName").value;
	let pwd = document.getElementById("pwd").value;
	let confirmPwd = document.getElementById("confirmPwd").value;
	let phoneNum = document.getElementById("phoneNum").value;


	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}


	//Step 2: Prepare arguments for XHR object
	// Reference: Slide 28 - Week 6 Lecture Notes
	xHRObject.open("POST", "processReg.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("add")+
						"&email=" + encodeURIComponent(email)+
						"&firstName=" + encodeURIComponent(firstName)+
						"&lastName=" + encodeURIComponent(lastName)+
						"&pwd=" + encodeURIComponent(pwd)+
						"&confirmPwd=" + encodeURIComponent(confirmPwd)+
						"&phoneNum="+encodeURIComponent(phoneNum);


	//Step 3: Assign callback function for async request
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){

			// Clear Password Fields 
			document.getElementById("pwd").value = "";
			document.getElementById("confirmPwd").value = "";
			// Display server's response
			document.getElementById("result").innerHTML = xHRObject.responseText;
		}
	}

	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}

/**
 * resetForm(): when the user presses the "reset" btn
 **/
 function resetForm(){
 	document.getElementById("email").value = "";
 	document.getElementById("firstName").value = "";
 	document.getElementById("lastName").value = "";
 	document.getElementById("pwd").value = "";
 	document.getElementById("confirmPwd").value = "";
 	document.getElementById("phoneNum").value = "";
 	document.getElementById("result").innerHTML = "";
 }
