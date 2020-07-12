<?php
	// STUDENT ID: 102125413
	// MAIN PURPOSE: Manage the Login process for customers and managers

	// Ensure that session_start() is only called, if there are zero sessions.
	// Reference: https://www.php.net/manual/en/function.session-status.php
	if(session_status() === PHP_SESSION_NONE){
		session_start(); // start new session or resume existing one
	}


	// MENU ACTIONS: 
	// (1) logout - logout a manager/customer
	// (2) managerLoginStatus - checks whether a manager is logged in
	// (3) customerLoginStatus - checks whether a user logged in
	// (4) displayMsg - display logout message


	// Action 1: Logout a user - customer/manager 
	if(isset($_GET["action"]) && $_GET["action"] == "logout" && isset($_GET["type"]) && $_GET["type"] != ""){

		// Type of User to logout - Manager or Customer
		$userType = $_GET["type"];

		// Case A: Logout the Manager - Task 3.5 and Task 5.3
		if($userType == "manager"){
			// Check whether manager is logged in
			// CASE 1: Manager is logged in 
			if( isset($_SESSION["mLogin"]) && $_SESSION["mLogin"] != ""){

				// Clear Login Details
				$managerId = strtoupper($_SESSION["mLogin"]);
				unset($_SESSION["mLogin"]); 

				// Send manager id that was logged out to the client
				echo "$managerId";

				$_SESSION["logoutMsg"] = "<div class='logoutMsg'>Thanks. The Manager $managerId has been logged out!!!</div><br/>";

			}  // Case 2: Manager is not logged in 
			else {
				echo "";
			}

		} // Case B: Logout the Customer - Task 4.7
		else if( $userType == "customer"){

			// Check whether customer is logged in
			// CASE 1: Customer is logged in 
			if( isset($_SESSION["cLogin"]) && $_SESSION["cLogin"] != ""){
				// Empty the Cart
				include("processBuying.php");
				$cancelResult = cancelPurchase();

				$logoutMessage = ""; // store logout message

				// Check whether purchase was cancelled properly?
				// (goods.xml was updated and cart was emptied)
				// Case: Error - something went wrong with emptying the cart and updating goods.xml
				if( strpos($cancelResult,"Error") ){
					echo $cancelResult;
					$logoutMessage .= $cancelResult; // store result
				}

				// Clear Login Details
				$customerId = $_SESSION["cLogin"];
				unset($_SESSION["cLogin"]);

				// Send the customer id that was logged out to the client
				echo "$customerId";

				// Create Logout Message that will be displayed when "logout.htm" is displayed
				$logoutMessage .= "<div class='logoutMsg'>Thanks. The Customer $customerId has been logged out!!!</div><br/>";
				$_SESSION['logoutMsg'] = $logoutMessage;

			} // Case 2: Customer is not logged in
			else {
				echo "";
			}

		}
	} // Action 2: Check whether the manager is logged in
	else if (isset($_GET["action"]) && $_GET["action"] == "managerLoginStatus" ){

		// Case 1: Manager is Logged In
		if( isset($_SESSION["mLogin"]) &&  $_SESSION["mLogin"] != ""){
			echo $_SESSION["mLogin"]; 			// Return manager id to client
		} // Case 2: Manager is not logged in
		else {
			echo ""; // Return empty string to client
		}

	
	} // Action 3: Check whether the customer is logged in
	else if( isset($_GET["action"]) && $_GET["action"] == "customerLoginStatus" ){
		
		// Case 1: Customer is Logged In
		if( isset($_SESSION["cLogin"]) &&  $_SESSION["cLogin"] != ""){
			echo $_SESSION["cLogin"]; 			// Return manager id to client
		} // Case 2: Customer is not logged in
		else {
			echo ""; // Return empty string to client
		}

	} // Action 4: Display message of last logged out user
	else if( isset($_GET["action"]) && $_GET["action"] == "displayMsg"){

		// CASE A: There is a log out message to display
		if(isset($_SESSION["logoutMsg"])){

			// Send Message to Client
			echo $_SESSION["logoutMsg"];
			// Unset the variable
			unset($_SESSION["logoutMsg"]);

		} // CASE B: No Message to display to client
		else {
			// Send Empty Message
			echo "";
		}

	}
?>