<?php
	// STUDENT ID: 102125413 
	// MAIN PURPOSE: REGISTER NEW CUSTOMER
	
	session_start(); 	//Create new session or resume existing one

	/********************** DEFINED FUNCTIONS **************************/
	require("util.php"); // import common functions and variables

	/**
	 * validateForm(): Validates the fields of the submitted form data from client.
	 * It implements the validation rules as per Task 2.3
	 *
	 * @inputs - email,first Name, last Name, pwd, confirmPwd and phone number
	 * @returns - errMsg containing any validation error messages 
	 */
	function validateForm($email,$firstName,$lastName,$pwd,$confirmPwd,$phoneNum){
		$errMsg = ""; // stores error message as string

		// Validation Rule 1: All inputs except the contact phone number are given
		if(empty($email)){
			$errMsg .=  "Email Address cannot be empty <br/>\r\n";
		} // Extra validation for email address
		else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$errMsg .= "Invalid Email Address Format. Must contain @ symbol <br/>\r\n";
		}
		if(empty($firstName)){
			$errMsg .= "First Name cannot be empty <br/>\r\n";
		}
		if(empty($lastName)){
			$errMsg .= "Last Name cannot be empty <br/>\r\n";
		}
		if(empty($pwd)){
			$errMsg .= "Passworld Field cannot be empty <br/>\r\n";
		}
		if(empty($confirmPwd)){
			$errMsg .= "Confirm Passworld Field cannot be empty <br/>\r\n";
		}

		// Validation Rule 2: "password" and "confirm password" fields match
		if($pwd != $confirmPwd){
			$errMsg .="Password and Confirm Password fields must be the same <br/>\r\n";
		}

		// Validation Rule 3: Contact Number should follow either (0d)dddddddd or 0d dddddddd
		// Case: Phone Number is not empty, thus check whether it follows correct format
		if(!empty($phoneNum)){
			// Pattern Validation
			// Logic Condition:  NOT [ match (0d)dddddddd OR 0d dddddddd) ]
			if( !(preg_match("/^\(0\d\)\d{8}$/",$phoneNum) OR preg_match("/^0\d \d{8}$/",$phoneNum)) ){
				$errMsg .="Phone Number is not valid format. Valid Formats: (0d)dddddddd OR 0d dddddddd <br/>\r\n";
			}
		}

		// Validation Rule 4: Email Address is unique
		if(checkEmailExists($email)){
			$errMsg .="Email Address is already registered in the system <br/>\r\n";
		}

		return $errMsg;
	}

	/**
	 * checkEmailExists(): checks whether email exists in XML file (Task 2.3)
	 * @input: $email refers customer email address
	 * @output: "true" if email exists in "customer.xml". "false" if email doesn't exist in customer.xml
	 */
	function checkEmailExists($email){

		$xmlFile = $GLOBALS['xmlCustomerPath']; // Path of customer.xml file

		// Condition: Checks whether the file exists
		// Case 1: File Does not Exist
		if(!file_exists($xmlFile)) {
			return false;
		} else {  // Case 2: File Exists - Then Perform linear search
			
			$dom = DOMDocument::load($xmlFile);  // Load XML file into DOM Object
			$custList = $dom->getElementsByTagName("customer"); // Get the List of customer

			//Traverse through each customer
			foreach($custList as $node){
				// Get reference to the "email" node (element)
				$emailnode = $node->getElementsByTagName("email");
				
				// Get reference to text node and then extract node value
				$emailval = $emailnode->item(0)->nodeValue; // item(0) - firstChild is textNode

				// check whether current item matches the target
				if(strtolower($email) == strtolower($emailval) ){
					return true;
				}
				//echo "Email: $emailval <br/>\r\n"; // Testing purposes
			}
		}
		return false;
	}


	/**
	 * addCustomer() - adds new customer to customer.xml file (Task 2.3)
	 *
	 * @precondition: assumes that checkEmailExists() has been already executed
	 *                (enforce separation of responsibilities)
	 * @inputs: email, firstName, lastName, pwd,confirmPwd, phoneNum
	 * @outputs: modified or new customer.xml file 
	 * @returns:  boolean - true if account was created. Otherwise, return false.
	 * Reference: Assignment 2 Hints - testRegister.php in "A2TestCode.zup"
	 *            Week 7 - Lab 7 - Task A/B
	 */
	function addCustomer($email,$firstName,$lastName,$pwd,$confirmPwd,$phoneNum){

		$dom = new DomDocument("1.0"); // Create DOM Object
		$xmlPath = $GLOBALS['xmlCustomerPath']; // XML file location
		$count = 0;  // Number of customers in the customers.xml file
 
		//Condition: Check whether the file exists?
		// Case 1: File Exists - Load File
		if(file_exists($xmlPath)){

			$dom->preserveWhiteSpace = FALSE;  	// ignore existing white spaces in XML
			$dom->load($xmlPath); 	// Load xml file 

			// Get reference to root element <customers>
			$customers = $dom->getElementsByTagName("customers")->item(0);

			// Determine number of customers in customers.xml file
			$count = $dom->getElementsByTagName("customer")->length;
		}
		// Case 2: File doesn't exist. Insert Root Element <customers> into XML DOM
		else{
			$customers = $dom->createElement("customers"); 	// Create <customers> element
			// Make <customers> the root element (e.g. as child of XML DOM)
			// Also get reference of the node<customers> added to DOM
			$customers = $dom->appendChild($customers);
		}

		// Insert new <customer> record into the <customers> record
		// Element 1: Create <customer> element and append to <customers> element
		$customer = $dom->createElement("customer");
		$customer = $customers->appendChild($customer);

		// Element 2: Create <custid> element and append to <customer> element
		$custid = $customer->appendChild($dom->createElement("custid"));
		// Create and Append text node to <custid>
		$custidvalue = $custid->appendChild($dom->createTextNode($count + 1)); 

		// Element 3: Create <firstname> element and append to <customer> element
		$firstname = $customer->appendChild($dom->createElement("firstname")); 
		$firstnamevalue = $firstname->appendChild($dom->createTextNode($firstName)); 

		// Element 4: Create <lastname> element and append to <customer> element
		$lastname = $customer->appendChild($dom->createElement("lastname"));		
		$lastnamevalue = $lastname->appendChild($dom->createTextNode($lastName)); 

		// Element 5: Create <email> element and append to <customer> element
		$emailElement = $customer->appendChild($dom->createElement("email"));
		$emailvalue = $emailElement->appendChild($dom->createTextNode($email)); 

		// Element 6: Create <password> element and append to <customer> element
		$password = $customer->appendChild($dom->createElement("password"));
		$passwordvalue = $password->appendChild($dom->createTextNode($pwd)); 

		// Element 7: Create <phonenumber> element and append to <customer> element
		$phonenumber = $customer->appendChild($dom->createElement("phonenumber"));
		$phonevalue = $phonenumber->appendChild($dom->createTextNode($phoneNum));

		// Save DOM to File
		$dom->formatOutput = true;
		$saveResult = $dom->save($xmlPath); 

		// check whether xml was saved?
		// Case 1: Failure - Unable to save xml file due to file permission
		if($saveResult == false ) {
			return false;
		} // Case 2: Success - Save XML file
		else{
			return true;
		}
		 

	}

	/**************** MAIN SECTION OF THE CODE ***************************/
	// Task 2.3 and Task 2.4
	//Condition 1: User has submitted an "add" action request
	if(isset($_POST["action"]) && $_POST["action"] == "add"){
		// Condition 2: User has filled the form 
		if( isset($_POST["email"]) && isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["pwd"]) && isset($_POST["confirmPwd"]) ){

			// Get the parameters stored in $_POST
			$email = sanitiseInput($_POST["email"]);
			$firstName = sanitiseInput($_POST["firstName"]);
			$lastName = sanitiseInput($_POST["lastName"]);
			$pwd = $_POST["pwd"];
			$confirmPwd = $_POST["confirmPwd"];
			$phoneNum = sanitiseInput($_POST["phoneNum"]);

			//echo "<p>$email : $firstName : $lastName : $pwd : $confirmPwd : $phoneNum</p> \r\n"; // Testing purposes:

			// Perform Validation on Inputs and store validation error messages into variable
			$errMsg = validateForm($email,$firstName,$lastName,$pwd,$confirmPwd,$phoneNum);

			// Case 1:  Add new customer only if there is no validation messages 
			if($errMsg == ""){
				$addResult = addCustomer($email,$firstName,$lastName,$pwd,$confirmPwd,$phoneNum); 

				// Condition: Check whether the customer was added succesfully?
				// Case: Customer was added to "goods.xml" file
				if($addResult == true){
					echo "Message: <Strong>Succesfully</strong> created new account <br/>\r\n";
					echo "<a href='buyonline.htm'>Back to Main Menu</a>"; // Task 2.4 - Back Button
				} // Case: Error - Unable to add customer to "goods.xml" file
				else {
					echo "<p class='errMsgBox'><strong>Error Message:</strong><br/><span class='errMsg'>".
						"Customer was not registered. Permission Denied - Unable to write to customer.xml file</span></p>";
				}
				
			} // Case 2: Display Errors Messages - Validation Errors (e.g. empty fields or email address already registered)
			else { 
				echo "<p class='errMsgBox'><strong>Error Messages:</strong><br/><span class='errMsg'>" . $errMsg . "</span></p>";
			}
		}
	}  //Condition 2: User attempts to register.php directly - redirect to register.htm page
	else {
		header("Location:register.htm");
	}
?>