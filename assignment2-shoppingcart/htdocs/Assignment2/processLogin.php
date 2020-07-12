<?php
	// STUDENT ID: 102125413
	// MAIN PURPOSE: PROCESS CUSTOMER LOGIN

	session_start(); //Create new session or resume existing one

	/********************** DEFINED FUNCTIONS **************************/
	require("util.php"); // import common functions and variables	
	
	/**
	 * validateForm(): validates the fields of the submitted form data
	 * @inputs - email and password
	 * @returns - errMsg containing any validation error messages 
	 */
	function validateForm($email,$pwd){
		$errMsg = ""; // stores error message as string

		// Validation Rule 1A: Email Address  cannot be empty
		if($email == ""){
			$errMsg .=  "Email Address cannot be empty <br/>\r\n";
		} // Extra validation for email address
		elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$errMsg .= "Invalid Email Address Format. Must contain @ symbol <br/>\r\n";
		}

		// Validation Rule 2: Password cannot be empty
		if($pwd == ""){
			$errMsg .="Password cannot be empty <br/>";
		}
		return $errMsg;
	}


	/**
	 * authLogin(): authenticate customer's login details using "customers.xml" file
	 * 
	 * @inputs: $email - customer's email, $pwd - customer's password
	 * @returns: true if credentials are valid. Otherwise, return false.
	 */
	function authLogin($email,$pwd){
		$result = false;  // Assume invalid credentials
		$xmlPath = $GLOBALS['xmlCustomerPath']; // XML file location of the customer.xml

		// Case 1: customer.xml file doesn't exists
		if(!file_exists($xmlPath)){
			echo "<p class='errMsgBox'><strong>Error:</strong><span class='errMsg'> customer.xml file does not exist in</span></p>";
		} //Case 2: The file exists
		else {
			$dom = DOMDocument::load($xmlPath);	// Load the XML file 

			// Get List of customers - nodeList
			$custList = $dom->getElementsByTagName("customer");

			//Iterate over customer nodeList
			foreach($custList as $node){

				// Get reference to <email> element nested within <customer>
				$curEmailNode = $node->getElementsByTagName("email");
				// Extract text node from <email> element
				// (where first item of NodeList is textNode)
				$curEmailVal = $curEmailNode->item(0)->nodeValue;

				// Get reference to <password> element nested within <customer>
				$curPwdNode = $node->getElementsByTagName("password");
				// Extract text node from <password> element
				$curPwdVal = $curPwdNode->item(0)->nodeValue;
 
				//echo "$curEmailVal:$curPwdVal <br/>"; // TESTING PURPOSES

				//Check whether extracted email and password matches the user's input
				if(strtolower($curEmailVal) == strtolower($email) && $curPwdVal == $pwd){
					$result = true;
				}
			}
		}
		return $result;
	}

	/**************** MAIN SECTION OF THE CODE ***************************/
	
	//Condition 1: User has submitted an "login" action request
	if(isset($_POST["action"]) && $_POST["action"] == "login"){
		// Condition 1A: User has filled the form 
		if( isset($_POST["email"])  && isset($_POST["pwd"])  ){

			// Get the parameters stored in $_POST
			$email = sanitiseInput($_POST["email"]);
			$pwd = $_POST["pwd"];

			// Perform Validation on Inputs and store validation error messages into variable
			$errMsg = validateForm($email,$pwd);
			
			// Case 1: Validation Errors such as empty inputs
			if($errMsg != ""){
				echo "<p class='errMsgBox'><strong>Error Messages:</strong><br/><span class='errMsg'>" . $errMsg . "</span></p>";
			} 
			else { // Case 2: No Validation Errors. Next Step: Perform authentication.

				$authResult = authLogin($email,$pwd); // Perform authentication
				
				// Case 2A: Invalid Customer Login Credentials
				if(!$authResult){
					echo "<p class='errMsgBox'><strong>Error: </strong><span class='errMsg'>Invalid Login Credentials</span></p>"; // Display Failure Message
				} // Case 2B: Valid Customer Login Credentials
				else {

					// Display Success Message
					echo "SUCCESS"; 
					
					// Save ManagerId in session variable
					$_SESSION["cLogin"] = strtolower($email);

				}
			}

		}
	//Condition 2: User attempts to login.php directly - redirect to login.htm page
	} else {
		header("Location:login.htm");
	}

?>