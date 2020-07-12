<?php
	// Student ID: 102125413
	// MAIN PURPOSE: Manager Login Process

	session_start(); //Create new session or resume existing one
	
	/********************** DEFINED FUNCTIONS **************************/
	require("util.php"); // import common functions and variables

	/**
	 * validateForm() validates the fields of the form data
	 * @inputs - managerId and password
	 * @returns - errMsg containing any validation error messages 
	 */
	function validateForm($managerId,$pwd){
		$errMsg = ""; // stores error message as string
		// Validation Rule 1: ManagerId and Password fields cannot be empty
		if($managerId == ""){
			$errMsg .= "Manager Id cannot be empty <br/>";
		}
		if($pwd == ""){
			$errMsg .="Password cannot be empty <br/>";
		}
		return $errMsg;
	}


	/**
	 * authLogin(): authenticate manager's login details against "manager.txt" file
	 * @inputs: manager id and password
	 * @returns: true - valid manager credentials.Otherwise, return false.
	 * Reference: Week 3 Lecture Example Code "bowling_admin.php"
	 * Task 3.1 - Authenticate Manager Login Details
	 */
	function authLogin($managerId,$pwd){
		$result = false; // Assume invalid credentials
		$filePath = $GLOBALS['txtManagerPath']; // File location of the manager.txt

		// Step 1: Load the file
		// Case 1: The  the file doesn't exists
		if(!file_exists($filePath)){
			echo "<p class='errMsgBox'><strong>Error:</strong><span class='errMsg'> manager.txt file does not exist in </span></p>";
		} //Case 2: The file exists
		else {
			$managers = file($filePath);  // Load file into array where each line corresponds to array item

			// Iterate over each manager in the array
			foreach($managers as $manager){

				// Remove New Line Character (\n) from string (current line)
				// Reference: https://thisinterestsme.com/php-remove-newlines/
				$currentManager = str_replace(array("\n","\r"),"",$manager);

				// Split string into two parts: (1)managerId  (2)password
				$managerDetails = explode(", ",$currentManager);
				$curManagerId = $managerDetails[0];
				$curManagerPwd = $managerDetails[1]; // trim - remove leading and trailing spaces

				//echo "$curManagerId:$curManagerPwd <br/>\r\n";

				// Compare manager login id and password (e.g. whether current item matches target id)
				// Case 1: Valid Login Details
				if( (strtolower($curManagerId) == strtolower($managerId)) && $curManagerPwd == $pwd  ){
					$result = true;
				}
			}
		}

		return $result;
	}



	/**************** MAIN SECTION OF THE CODE ***************************/
	//Condition 1: User has submitted an "login" action request
	if(isset($_POST["action"]) && $_POST["action"] == "login"){
		// Condition 2: User has filled the form 
		if( isset($_POST["managerId"])  && isset($_POST["pwd"])  ){

			// Get the parameters stored in $_POST
			$managerId = sanitiseInput($_POST["managerId"]);
			$pwd = $_POST["pwd"];


			// Testing purposes:
			//echo "<p>$managerId : $pwd </p> \r\n";


			// Perform Validation on Inputs and store validation error messages into variable
			$errMsg = validateForm($managerId,$pwd);
			// Case 1: Invalid Inputs (e.g. empty strings)
			if($errMsg != ""){
				echo "<p class='errMsgBox'><strong>Error Messages:</strong><br/><span id='errMsg'>" . $errMsg . "</span></p>";
			} // Case 2: Valid Inputs. Next Step: Perform authentication.
			else {
				$authResult = authLogin($managerId,$pwd);
				// Case 1: Invalid Manager Login Credentials
				if(!$authResult){
					echo "<p class='errMsgBox'><strong>Error:</strong><span class='errMsg'> Invalid Login Credentials</span></p>"; 
				} // Case 2: Valid Manager Login Credentials
				else {
					echo "<br/><strong class='successMsg'>Status: Succesfully logged into system </strong><br/>"; // Display Success Message
					
					// Save ManagerId in session variable
					$_SESSION["mLogin"] = strtolower($managerId);

					// Display two links for  (1) "listing" and (2) "processing" - Task 3.1
					echo "<div id='successMenuOptions'>\r\n"
						."<strong>Menu Options:</strong> <br/>\r\n"
						."<a href='listing.htm'>Listing</a> <br/>\r\n"
						."<a href='processing.htm'>Processing</a> <br/>\r\n"
						."<a href='logout.htm' onclick=\"logoutUser('manager')\">Logout</a>\r\n"
						."</div>\r\n";
				}
			}
		}
	//Condition 2: User attempts to register.php directly - redirect to register.htm page
	} else {
		header("Location:mlogin.htm");
	}
?>