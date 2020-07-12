/**
	Author: Vinh Huynh 102125413
	Target: listing.htm
	Purpose: add new item to goods.xml file
**/

/**
 * addItem(): validates form and adds new item to "goods.xml" file
 * Task 3.4 - Validate Inputs and update "goods.xml" file
 */
function addItem(){

	// Reset the result <div> box
	document.getElementById("result").innerHTML = "";

	// Extract user inputs from the HTML form using DOM manipulation
	let itemName = document.getElementById("name").value;
	let itemPrice = document.getElementById("price").value;
	let itemQty = document.getElementById("qty").value;
	let itemDesc = document.getElementById("desc").value;

	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("POST", "processList.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("add")+
						"&itemName=" + encodeURIComponent(itemName)+
						"&itemPrice=" + encodeURIComponent(itemPrice)+
						"&itemQty=" + encodeURIComponent(itemQty)+
						"&itemDesc=" + encodeURIComponent(itemDesc);

	//Step 3: Assign callback function for async request
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			document.getElementById("result").innerHTML = xHRObject.responseText;
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}


/**
 * loginStatus(): Checks whether manager is already logged in the system
 * and execute relevant functions.
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
	// Use SYNC HTTP Request to prevent user from performing other operations while logging out
	xHRObject.open("GET", "manageLogin.php?action=managerLoginStatus&unique=" + Number(new Date), true);
	
	//Step 3: Assign callback function for async request
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){

			// response stores the username of the current manager that is logged in
			var response =  xHRObject.responseText;
			
			// CASE: Manager is not logged in
			if(response == ""){

				// Disable menu 
				document.getElementById("menu").style.display = "none";

				// Disable Menu Box
				document.getElementById("listForm").style.display = "none";

				// Menu Option:
				var msg = "<section><strong>Error:</strong> <span class='errMsg'>Access Denied. Manager must be logged in first.</span><br/>";
				msg += "<div id='deniedMenuOption'><strong>Menu Option:</strong> <a href='buyonline.htm'> Return To Main Menu </a></div></section>";
				document.getElementById("result").innerHTML = msg;
				
			}
		}
	}

	//Step 4: Send HTTP Request to Server
	xHRObject.send(null);
}

/**
 * resetForm(): resets the form when user presses the "reset btn"
 **/
 function resetForm(){
 	document.getElementById("name").value = "";
 	document.getElementById("price").value = "";
	document.getElementById("qty").value = "";
	document.getElementById("desc").value = "";
	document.getElementById("result").innerHTML = "";
 }



// Function executed when the document loads
function init(){
	loginStatus(); // Condition: Check whether manager is already logged in?
}
window.onload = init;



