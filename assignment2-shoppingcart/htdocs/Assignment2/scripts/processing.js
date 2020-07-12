/**
	Author: Vinh Huynh 102125413
	Target: processing.htm
	Purpose: processing page
**/


/**
 * processItems(): will process sold items in "goods.xml" file.
 * Sold Items are items with qtyavilable and qtyonhold that are both zero.
 * Task 5.2 - Process Sold Items by updating "goods.xml" file
 **/

function processItems(){

	// Clear old Messages:
	document.getElementById("result").innerHTML = "";

	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("POST", "processPForm.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("processItems");


	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			document.getElementById("result").innerHTML = xHRObject.responseText;
			getProcessForm();
		}
	}

	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}



/**
 * getFunction(): displays the "process" HTML FORM/TABLE
 * (1) Display items where non-zero quanities
 * Task 5.1 - Display table of items with non-zero sold quanities
 **/
function getProcessForm(){
	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	//Step 2: Prepare arguments for XHR object
	xHRObject.open("POST", "processPForm.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("getProcessForm");


	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			document.getElementById("processForm").innerHTML = xHRObject.responseText;
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);

}

/**
 * loginStatus(): Checks whether manager is already logged in the system
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
	
	//Step 3: Assign callback function for async request
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){

			// response stores the username of the current manager that is logged in
			var response =  xHRObject.responseText;
			
			// CASE: Manager is not logged in
			if(response == ""){

				// Disable menu 
				document.getElementById("menu").style.display = "none";

				// Disable form
				document.getElementById("processForm").style.display = "none";

				// Display Alternative Menu Option:
				var msg = "<div><strong> Access Denied.</strong> <span class='errMsg'>Manager must be logged in first.</span><br/>";
				msg += "<div id='deniedMenuOption'><strong>Menu Option:</strong> <a href='buyonline.htm'> Return To Main Menu </a></div></div>";
				document.getElementById("result").innerHTML = msg;
			}  
			else { // CASE 2 : Manager is logged in
				// Inital load of the form
				getProcessForm();
			}
		}
	}

	//Step 4: Send HTTP Request to Server
	xHRObject.send(null);
}



/* Function called when HTMLDocument loads */
function init(){
	loginStatus();
}
// Execute init() function when HTML Doc loads
window.onload = init;