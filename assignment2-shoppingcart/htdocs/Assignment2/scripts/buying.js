/**
	Author: Vinh Huynh 102125413
	Target: buying.htm
	Purpose: buying page
**/

/**
 * clearMsg(): clear the html elements that store the messages
 */

function clearMsg(){
	document.getElementById("msg1").innerHTML = "";
	document.getElementById("msg2").innerHTML = "";
}

/**
 * purchase(): will either (1) confirm or (2) cancel a purchase
 * 
 * (1) confirm purchase - when user presses "confirm purchase" button
 *     Customer checkouts goods. (e.g. customer wants to buy goods)
 *
 * (2) cancel purchase - when user presses the "cancel purchase" button.
 *     Customer cancel purchase.(e.g. clear entire cart)
 *
 * @inputs: action - "confirm" or "cancel"
 * Task 4.6 - Update "cart" and goods.xml when the user presses "confirm/cancel purchase" button
 */
function purchase(action){

	clearMsg(); // Clear old messages

	// Condition: Valid Actions - Confirm/Cancel Purchase
	if( action == "confirm" || action =="cancel"){

		// Step 1: Create XMLHttpRequest Object
		if (window.XMLHttpRequest) {
			var xHRObject = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) {
			var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		//Step 2: Prepare arguments for XHR object
		xHRObject.open("POST", "processBuying.php" , true);
		xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		var bodyofrequest = "action=" + encodeURIComponent(action+"Purchase");

		//Step 3: Assign callback function
		xHRObject.onreadystatechange = function(){
			if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
				var serverResponse = xHRObject.responseText; // Get Server Response
				document.getElementById("msg2").innerHTML = serverResponse;
				loadCart(); // Reload Cart
				loadCatalogue(); // Reload Catalogue 
			}
		}
		//Step 4: Send HTTP Request to Server
		xHRObject.send(bodyofrequest);

	}
}


/**
* addRemoveItem(): add or removes an item from the "goods.xml" file
* @inputs: action - 'add' or 'remove' an item
*          itemid - the id of an item 
* Task 4.3 - Update goods.xml when user presses "add 1 to cart" button
* Task 4.4 - Update cart (session) when user presses "add 1 to cart" button
* Task 4.5 - Update "goods.xml" and cart when user presses "remove from cart" button
*
*/
function addRemoveItem(action,itemid){

	clearMsg(); // Clear old messages

	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	// Reference: Slide 28 - Week 6 Lecture Notes
	xHRObject.open("POST", "processBuying.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent(action) +
						"&itemid=" + encodeURIComponent(itemid);

	//Step 3: Assign callback function 
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			// Server response either contains:
			// (1) String containing error message (e.g. item not available)
			// (2) String with "SUCCESS" message

			var serverResponseMsg = xHRObject.responseText;
			
			// CASE 1: ERROR MESSAGE 
			if(serverResponseMsg != "SUCCESS"){
				document.getElementById("msg1").innerHTML = serverResponseMsg;
			} // CASE 2: Item added to cart and XML file updated
			else {

				// FORCED Update the Catalogue list that being displayed to user
				// Why? To reflect new qty (10 second refresh is too slow)
				loadCatalogue(); // reload catalogue
				loadCart(); // reload cart
			} 
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}


/**
 * loadCatalogue(): display catalogue items
 * Task 4.2 - Retrieve list of items 
 * Task 4.3 - Display "add one to cart" button
 */
function loadCatalogue(){
	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("POST", "processBuying.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("getCatalogue");

	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			document.getElementById("catalogue").innerHTML = xHRObject.responseText;
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}

/**
 *	loadCart(): Displays the shopping cart
 *  Task 4.4 - Display shopping cart (with items)
 *  Task 4.5 - Display "remove from cart" button
 *  Task 4.6 - Display "confirm purchase","cancel purchase" button
 *  
 **/
function loadCart(){
	// Step 1: Create XMLHttpRequest Object
	if (window.XMLHttpRequest) {
		var xHRObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		var xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//Step 2: Prepare arguments for XHR object
	xHRObject.open("POST", "processBuying.php" , true);
	xHRObject.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	var bodyofrequest = "action=" + encodeURIComponent("getCart");


	//Step 3: Assign callback function
	xHRObject.onreadystatechange = function(){
		if((xHRObject.readyState == 4) && (xHRObject.status == 200)){
			document.getElementById("cart").innerHTML = xHRObject.responseText;
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(bodyofrequest);
}


/*
 * loginStatus(): checks whether customer is logged in 
 *  and executes the the relevant functions.
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

			// Stores the username of the current customer that is logged in
			var response =  xHRObject.responseText;
			
			// CASE 1A: Customer is not logged in.
			if(response == ""){
				// Disable the logout menu link
				document.getElementById("menu").style.display = "none";

				// Display Menu Options
				var msg = "<br/><strong>Error:</strong> <span class='errMsg'>Access Denied. Customer Must be Logged In.</span><br/>";
				msg += "<strong>Menu Option:</strong> <a href='buyonline.htm'>Back to Main Menu</a>";
				document.getElementById("msg1").innerHTML = msg;
			} 
			// CASE 1B: Customer is logged in - thus allow to reload catalogue every 10 seconds
			else {
				// Inital loading of loadCatalogue
				loadCatalogue();
				// Inital Loading of the cart
				loadCart();

				//TASK 4.2 -  REFRESH CATALOGUE EVERY 10 SECONDS
				setInterval(loadCatalogue,10000); // 1 sec = 1000milliseconds(ms)
			}
		}
	}
	//Step 4: Send HTTP Request to Server
	xHRObject.send(null);
}


/* init(): function called when HTMLDocument loads */
function init(){
	loginStatus();
}
window.onload = init;