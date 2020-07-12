/**
	Author: Vinh Huynh 102125413
	Target: listing.htm, processing.htm, buying.htm
	Purpose: login management functions
**/

/*
 * logoutUser(): logouts the current customer or manager
 * Task 3.5 - Logout Manager and Display message
 * Task 4.7 - Cancel Purchase and Logout User
 * Task 5.3 - Logout Manager and Display Logout Message
 *
 * @inputs: userType - 'customer' or 'manager'
 */
function logoutUser(userType){
	
	// Check that valid user 'customer' or 'manager'
	// Case 1: Valid user
	if(userType == "customer" || userType == "manager"){
		// Step 1: Create XMLHttpRequest Object
		if (window.XMLHttpRequest) {
			var xHRObject = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) {
			var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
		}

		//Step 2: Prepare arguments for XHR object
		// Use SYNC HTTP Request to prevent cust/mgr from performing other operations while logging out
		xHRObject.open("GET", "manageLogin.php?action=logout&type="+ userType + "&unique=" + Number(new Date), false);
		
		//Step 3: Assign callback function
		xHRObject.onreadystatechange = function(){
			if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
				var response =  xHRObject.responseText;
				//alert(response + " has been logged out.");
			}
		}
		//Step 4: Send HTTP Request to Server
		xHRObject.send(null);
	}  // Case 2: Invalid User
	else {
		alert("Error: Invalid User Type. Must be either customer or manager");
	}
}

