<?php
	// STUDENT ID: 102125413 VINH HUYNH
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Booking Cab"/>
    <meta name="keywords" content="taxi,booking,customer"/>
    <meta name="author" content="Vinh Huynh - 102125413"/>
    <title> Booking - CabsOnline </title>
    <!-- Style Sheets -->
    <link href="styles/booking.css" rel="stylesheet"/>
	<link href="styles/common.css" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<header>
		<span id="brand">
			<!--Reference: https://www.w3schools.com/icons/fontawesome_icons_intro.asp -->
			<i class="fa fa-car fa-2x" style="color:orange"></i> 
			<span id="title">CabsOnline </span>
		</span>
		<nav>
			<?php
			// Navigation Menu
			// Customised Menu - Display Login/Register Button if user not logged int
			if(!isset($_SESSION['emailaccount'])){
				echo "<a href='login.php'>Login</a>\r\n<a href='register.php'>Register</a>\r\n";
			}
			echo "<a id='curPage' href='booking.php'>Booking</a>\r\n"
				."<a href='admin.php'>Admin</a>\r\n";

			// Customised Menu - Display logout button if user is logged in
			if(isset($_SESSION['emailaccount'])){
				echo " <a href='logout.php'>logout</a>\r\n";
			}
			?>
		</nav>
	</header>
	<h1>Booking a Cab</h1>
<?php
	// Set timezone to Australia EST Time 
	// Reference: 	https://www.php.net/manual/en/function.date-default-timezone-get.php
	date_default_timezone_set("Australia/Melbourne");


	/**************************************** DEFINED FUNCTIONS SECTION *****************************************/
	/************************************************************************************************************/

	/* sanitiseInput() is a function that sanitises the input from the user.
	 *
	 * @inputs: $input - any given string
	 * @return: modified string that has been sanitised.
	 */
	function sanitiseInputs($input){
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}


	/**
	 * displayForm() displays the HTML form for booking a cab
	 * Note: The form will be prefilled if the user (1) completes the form with invalid data (2) partial completed fields
 	 *
	 * @inputs: $email - customer's email
	 *			$passName - passenger's name
	 *			$passPhoneNo - passenger's phone number
	 *			$unitNo,$streetNo,$streetName,
	 *          $picksuburb - pickup suburb
	 *          $destsuburb - destination suburb
	 *          $pickupDate - scheduled pickup date for taxi service
	 *			$pickupTime - scheduled pickup time for taxi service
	 * @output: html5 form with booking fields
	 */
	function displayForm($email,$passName,$passPhoneNo,$unitNo,$streetNo,$streetName,$picksuburb,$destsuburb,$pickupDate,$pickupTime){
		echo "<section id='displayFormBox'>\r\n";
        echo "<p> <em>Currently Logged In: $email &nbsp; &nbsp;</em> <br/>";
		echo "<strong>Please fill the fields below to book a taxi</strong></p>\r\n";

		// Note that: HTML5 Validation is turned off using the attribute in the form, novalidate='novalidate'
		echo "<form method='post' action='booking.php' novalidate='novalidate' >
				<p>
					<label for='pName'>Passenger Name</label>
					<input id='pName' type='text' name='pName' pattern='^[a-zA-Z -]+$' placeholder='John Doe'
					title='Name can only consist of letters, spaces and hyphens' required='required' value='{$passName}'/> 
				</p>
				<p>
					<label for='phoneNum'>Contact phone of the passenger</label>
					<input id='phoneNum' type='text' name='phoneNum' pattern='^(\s*\d\s*){8,}$' required='required'
					 title='Phone No must consist with at least 8 digits (spaces are allowed)' value='{$passPhoneNo}'
					 placeholder='9214 8000'/>
				</p>
				<fieldset>
					<legend> Pickup Address </legend>
					<p>
						<label for='unitNum'>Unit Number </label>
						<input id='unitNum' type='text' name='unitNum' value='{$unitNo}'/> 
					</p>
					<p>
						<label for='streetNo'>Street Number </label>
						<input id='streetNo' type='text' name='streetNo' pattern='^\d+[a-zA-Z]{0,1}$' placeholder='24'
						required='required' title='Street Number begins with digits followed by optional single letter (e.g. 12A or 12)' value='{$streetNo}'/>
					</p>
					<p>
						<label for='streetName'>Street Name </label>
						<input id='streetName' type='text' name='streetName' pattern='[a-zA-Z- ]+' placeholder='Wakefield St'
						title='Street Name can only consist of letters, spaces and hyphens' required='required' value='{$streetName}'/>
					</p>
					<p>
						<label for='picksuburb'>Suburb </label>
						<input id='picksuburb' type='text' name='picksuburb' pattern='[a-zA-Z- ]+' placeholder='Hawthorn'
						title='Pickup Suburb can only consist of letters, spaces and hyphens' required='required' value='{$picksuburb}' />
					</p>
				</fieldset>
				<p>
					<label for='destsuburb'>Destination Suburb </label>
					<input id='destsuburb' type='text' name='destsuburb' pattern='[a-zA-Z- ]+' placeholder='Parkville'
					title='Destination Suburb can only consist of letters, spaces and hyphens' required='required' value='{$destsuburb}' />
				</p>
				<p>
					<label for='pickupDate'>Pick update date: </label>
					<input id='pickupDate' type='date' name='pickupDate' required='required' value='{$pickupDate}' />
				</p>
				<p>
					<label for='pickupTime'>Pick update time: </label>
					<input id='pickupTime' type='time' name='pickupTime' required='required' value='{$pickupTime}'/>
				</p>
				<p>
					<input type='hidden' name='email' value='{$email}'/>
					<input type='submit' value='Book'/>
				</p>
			</form>";
		echo "</section>\r\n";
	}

	/**
	 * validateForm() validates the form fields entered by the user
	 *  It implements the following validation rules:
	 *  (1) Passenger's name: (a) can't be empty (b) must consist of letters, hyphens or spaces 
	 *  (2) Passenger's phone number: (a) can't be empty (b) requires a min of 8 digits with optional spaces (e.g. 9214 8000)
	 *  (3) Unit Number field is optional. However, when filled it must start with digits followed by a optional single letter (100A)
	 *  (4) Street Number: (a) can't be empty (b) must start with digits followed by an optional single letter
	 *  (5) Street Name : (a) can't be empty (b) must consist of letters, hyphens or spaces 
	 *  (6) Pickup Suburb: (a) can't be empty (b) must consist of letters, hyphens or spaces 
	 *  (6) Destination Suburb: (a) can't be empty (b) must consist of letters, hyphens or spaces 
	 *  (7) Pickup date: (1) can't be empty (b) must be a valid date format
	 *  (8) Pickup time: (1) can't be empty (b) must be a valid time format
	 *  (9) Pickup Date + Time: Datetime must be at least 40 minutes from current time
	 * 
	 * @inputs $passName - passenger's name
	 *         $passPhoneNo - passenger's phone number
     *         $unitNo,$streetNo,$streetName,
     *         $picksuburb - pickup suburb for trip
     *         $destsuburb - destination suburb for trip
     *         $pickupDate - pickup date of taxi trip
     *         $pickupTime - pickup time of taxi trip
     *		   $currentDateTime - represents current time and date
	 * @returns $errMsg - string containing any validation error messages
	 *
	 */
	function validateForm($passName,$passPhoneNo,$unitNo,$streetNo,$streetName,$picksuburb,$destsuburb,$pickupDate,$pickupTime,$currentDateTime){
		// String accumulates error messages
		$errMsg = "";

		// Parameter 1: Passenger Name
		// Case 1A: Empty String
		if(empty($passName)){
			$errMsg .= "<strong>Passenger Name:</strong> cannot be empty. <br/>\r\n";
		} 
		// Case 1B: Name doesn't match the required format
		else if(!preg_match("/^[a-zA-Z -]+$/",$passName)){
			$errMsg .= "<strong>Passenger Name:</strong> Invalid Format. Name can only consist of letters, hyphens or spaces. <br/>\r\n";
		}


		// Parameter 2:  Passenger's Phone Number
		// Case 2A: Empty String
		if(empty($passPhoneNo)){
			$errMsg .= "<strong>Passenger's Phone Number:</strong> cannot be empty <br/>\r\n";
		// Case 2B: Phone Number doesn't match required format
		} else if(!preg_match("/^(\s*\d\s*){8,}$/",$passPhoneNo)) {
			$errMsg .= "<strong>Phone Number:</strong> requires a minimum of 8 digits with optional spaces (e.g. 9214 8000) <br/>\r\n";
		}

		// Parameter 3: Unit Number
		// Case 3A: Optional Field doesn't match required format.
		// 			Format - Start with Digits followed by an optional letter such as 12A
		// Only validate the format if the unit number field is not empty
		if(!empty($unitNo) && !preg_match("/^\d+[a-zA-Z]?$/",$unitNo)){
			$errMsg .= "<strong>Unit Number:</strong> must begin with digits followed by optional single letter (e.g. 100A or 100)<br/>\r\n";
		}

		// Parameter 4: Unit Number
		// Case 4A: Street Number can't be empty
		if(empty($streetNo)){
			$errMsg .= "<strong>Street Number:</strong> cannot be empty<br/>\r\n";
		// Case 4B: Street number must start with digits followed by an optional letter such as 12A
		} else if(!preg_match("/^\d+[a-zA-Z]?$/",$streetNo)){
			$errMsg .= "<strong>Street Number:</strong> must begin with digits followed by optional single letter (e.g. 100A or 100)<br/>\r\n";
		}

		// Parameter 5: Street Name
		// Case 5A: Street Name can't be empty
		if(empty($streetName)){
			$errMsg .= "<strong>Street Name:</strong> can't be empty <br/>\r\n";
		// Case 5B: Street name doesn't match required format.
		} else if(!preg_match("/^[a-zA-Z- ]+$/",$streetName)) {
			$errMsg .= "<strong>Street Name:</strong> can only consist of letters, spaces and hyphens<br/>\r\n";
		}


		// Paremeter 6: Pickup Suburb
		// Case 6A: Suburb can't be empty
		if(empty($picksuburb)){
			$errMsg .= "<strong>Pickup Suburb:</strong> cannot be empty <br/>\r\n";
		// Case 6B: Suburb doesn't match required format
		} else if(!preg_match("/^[a-zA-Z- ]+$/",$picksuburb)) {
			$errMsg .= "<strong>Pickup Suburb:</strong> can only consist of letters, spaces and hyphens <br/>\r\n";
		}


		// Parameter 7: Destination Suburb
		// Case 7A: Suburb can't be empty
		if(empty($destsuburb)){
			$errMsg .= "<strong>Destination Suburb:</strong> cannot be empty <br/>\r\n";
		// Case 7B: Suburb doesn't match required format
		} else if(!preg_match("/^[a-zA-Z- ]+$/",$destsuburb)) {
			$errMsg .= "<strong>Destination Suburb:</strong> can only consist of letters, spaces and hyphens <br/>\r\n";
		}

		// Parameter 8 and 9: Pickup date and Time
		// Conditions: (1) Pickupdate and Pickup time
		$validTimeDate = true;

		// Parameter 8: Pickup Date
		// Case 8A:Pickup date can't be empty and must follow required format
		// UI will entered date as dd/mm/yyyy , but it will be captured as yyyy-mm-dd
		if ( empty($pickupDate) || !preg_match("/^\d{4}-\d{2}-\d{2}$/",$pickupDate)) {
			$errMsg .= "<strong>Pickup date:</strong> Invalid Date Format. It must follow (1) dd/mm/yyyy and (2) valid date<br/>\r\n";
			$validTimeDate = false;
		// Case 8B: the individual date components "day","month" and "year" must be valid
		} else {
			$dateArray = explode("-",$pickupDate); // convert dateString into an Array
			if(!checkdate($dateArray[1], $dateArray[2], $dateArray[0])){ // checkdate(month,day,year)
				$errMsg .="<strong>Pickup date:</strong> Invalid Date Component <br/>\r\n";
			}

		}

		// Parameter 9: Pickup Time
		// Case 9A: It can't be empty
		if(empty($pickupTime)){
			$errMsg .= "<strong>Pickup time:</strong> cannot be empty <br/>\r\n";
			$validTimeDate = false;
		// Case 9B: Must follow valid time format hour:minute
		} else if(!preg_match("/^\d{2}:\d{2}$/",$pickupTime)){
			$errMsg .= "<strong>Pickup time:</strong> Invalid Time Format. It must follow hh:mm (such as 13:00) <br/>\r\n";
			$validTimeDate = false;
		}

		// Parameter 8 & 9 - Pickup date and time must be valid 
		// SPECIAL CONDITION: datetime must be at least 40 minutes from current time
		if($validTimeDate){

			// Step 1: Get string of current time and date
			$curDateTimeString = $currentDateTime;
			// Step 2: Create string of the pickup time and date
			$targetDateTimeString = date("$pickupDate {$pickupTime}:00");

			// Step 3: Calculate time difference: TargetTime - CurrentTime
			$timeDifference = strtotime($targetDateTimeString) - strtotime($curDateTimeString); // time difference in seconds
			$minutes = $timeDifference / 60; // time difference in minutes 
			
			// TESTING PURPOSES - SEE BEHIND CALCULATIONS
			/*
			echo "<p>Current Date and Time: " . $curDateTimeString . "<br/>\r\n";
			echo "Booking Date and Time: $targetDateTimeString <br/>\r\n";
			echo "Difference in Minutes: $minutes </p>\r\n";
			*/

			// Step 4: Check whether the current datetime is 40 minutes from currentTime
			// CASE A: Booking time is not within 40 minutes of currentTime, thus append error message
			// Reference: https://www.php.net/manual/en/function.date.php
			if($minutes < 40) {
				$errMsg .=  "<strong>Pickup date/time:</strong> Breached 40 Minute Rule. Please change pickup date/time."
						." <br/>\r\n<span id='specialRule'>The Booking Time must be 40 minutes from current time:</span>" 
						. " <span id='specialRuleDate'> ". date("d M Y   g:i:sA", strtotime($curDateTimeString))."</span><br/>\r\n";
			} 
		}

		return $errMsg;
	}



	/*
	 * addRecord() Adds new record to the BOOKING table in the DB and returns associated Booking Reference number
	 * 
	 * @precondition: Customer EMAIL ADDRESS must exist in CUSTOMER TABLE. Foreign Key Constraint.
	 * @inputs  $email - Customer's email address
	 *			$passName - Passenger's name
	 *			$passPhoneNo - Passenger's Phone Number
	 *			$streetNo,$streetName
	 *			$picksuburb - pickup suburb location for taxi service trip
	 *			$destsuburb - destination suburb for taxi trip  
	 *			$pickupDateTime - scheduled pickup time and date for taxi service
	 *          $generatedDateTime - refers to current time and date
	 * @returns If new booking recorded was created, return Booking Reference Number
	 *          Otherwise, return -1 indicating booking/record was not created.
	 */
	function addRecord($email,$passName,$passPhoneNo,$streetNo,$streetName,$picksuburb,$destsuburb,$pickupDateTime,$generatedDateTime){
		
		// Import SQL parameters $host (hostname), $user, $pwd and $sql_db (database_name)
		require("settings.php");

		$targetDateTimeString = $pickupDateTime; // refers to pickup date/time
		$curDateTimeString = $generatedDateTime; // refers to current date/time


		// Attempt to establish DB Connection
		// die() - when @mysqli_connect() returns false because failed DB connection
		$conn = @mysqli_connect($host,$user,$pwd,$sql_db) 
				OR die("<p> Unable to connect database" ."Error Code " . mysqli_connect_errno()
				." : " . mysqli_connect_error() . "<p>\r\n");


		/***************** PRECONDITION: CUSTOMER EMAIL MUST EXIST IN CUSTOMER TABLE *****************/
        /***************** FOREIGN KEY CONSTRAINT OF BOOKING TABLE ***********************************/
        // Note: mySQL will throw an ERROR if you attempt to insert booking record where FK constraint not satisfied
        
        // Create SQL SELECT QUERY STRING
        $queryString = "SELECT * FROM CUSTOMER WHERE email='$email'";
        
        // Execute SQL Statement and handle if SQL execution goes wrong
        $queryResult = @mysqli_query($conn,$queryString) 
                OR die("<p>Unable to execute SELECT Query. </p>\r\n"
                ."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");
        
        // Count # of rows returned from SELECT QUERY
		$numRows = mysqli_num_rows($queryResult); 
        
        // Case A: The CUSTOMER EMAIL DOESN'T EXIST IN CUSTOMER table.
        if($numRows == 0){
            die("<p><strong>Foreign Key Error:</strong> Customer Email '{$email}' doesn't exist in CUSTOMER table</p>\r\n");
        }
        
        
		/********************************** INSERTION OF NEW RECORD  **********************************/


		// Create SQL INSERTION STRING
		// SPECIAL NOTE: (1) SQL will automatically assigned unique booking number and (2) set assignedStatus to "Unassigned"
		// Case 1: Empty Unit Number Field
		if(empty($unitNo)){
			$queryString = "INSERT INTO BOOKING(custemail,passname,passphoneno"
				 .",streetno,streetname,pickupsuburb,destsuburb"
				 .",pickupdatetime,generateddatetime) "
				 ." VALUES ('$email','$passName','$passPhoneNo', "
				 		."'$streetNo', '$streetName', "
				 		."'$picksuburb','$destsuburb','$targetDateTimeString','$curDateTimeString') ";

		} // Case 2: Empty Unit Number Provided
		else {
			$queryString = "INSERT INTO BOOKING(custemail,passname,passphoneno"
				 .",unitno,streetno,streetname,pickupsuburb,destsuburb"
				 .",pickupdatetime,generateddatetime) "
				 ." VALUES ('$email','$passName','$passPhoneNo', "
				 		."'$unitNo', '$streetNo', '$streetName', "
				 		."'$picksuburb','$destsuburb','$targetDateTimeString','$curDateTimeString') ";

		}

		// Execute SQL Statement and handle if SQL execution goes wrong
		// NOTE statement 
		$queryResult = @mysqli_query($conn,$queryString) 
				OR die("<p>Unable to execute insert query statement. </p>\r\n"
				."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");


		// Check # of rows inserted into SQL
		$rowsAdded =  mysqli_affected_rows($conn);
		if($rowsAdded == 0){
			die("<p><strong>ERROR:</strong> Fail to add new record to database</p>\r\n");
		}

		/********************* GET PRIMARY KEY OR BOOKING REFERENCE NUMBER of the NEWLY ADDED RECORD ************/
		
		// SQL String - extract booking number
		$queryString = "SELECT bookno FROM BOOKING "
					  ."WHERE custemail='$email' AND passName='$passName' AND passphoneno='$passPhoneNo' "
					  ."AND streetno='$streetNo' AND streetname='$streetName' AND pickupsuburb='$picksuburb' "
					  ."AND destsuburb='$destsuburb' AND pickupdatetime='$targetDateTimeString' AND generateddatetime='$curDateTimeString'";

		// Execuete the SQL Statement and handle errors relating to SQL execution
		$queryResult = @mysqli_query($conn,$queryString) 
						OR die("<p>Unable to execute SELECT Query. </p>\r\n"
						."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");

		// count # of rows returned from SELECT QUERY
		$numRows = mysqli_num_rows($queryResult); 

		$refNo = -1; // ATTN: -1 denotes encountered Error
		// Case 1: SELECT QUERY RETURNED A RECORD
		if($numRows > 0){
			$row = mysqli_fetch_row($queryResult); // extract the current row of the DB
			$refNo = $row[0]; // extract the BOOKING NUMBER
		// Case 2: SELECT QUERY Returned ZERO Results
		} else {
			echo "<section class='errMsg'>\r\n";
			echo("<p><strong>ERROR:</strong> Unable to Find Newly Added Record in DB</p>\r\n");
			echo "</section>\r\n";
		}

		// Terminate SQL Connection
		mysqli_close($conn);

		// Return the primary key (booking reference number) of the newly added record
		return $refNo; 

	}

	/*
	 * getCustomerName() returns the customer name associated with the given email address
	 *
	 * @input: email address of a customer 
	 * @return: if email address found in CUSTOMER table, return customer name.
	 *			Otherwise, return -1 to indicate customer email doesn't exist in DB.
	 */
	function getCustomerName($email){
		
		$custName = "" ;  // String stores customer name

		// Import SQL parameters $host (hostname), $user, $pwd and $sql_db (database_name)
		require("settings.php");

		// Attempt to establish DB Connection 
		$conn = @mysqli_connect($host,$user,$pwd,$sql_db) 
			OR die("<p> Unable to connect database" ."Error Code " . mysqli_connect_errno()
					." : " . mysqli_connect_error() . "<p>\r\n");


		// Construct SQL QUERY String
		$queryString = "SELECT custname FROM CUSTOMER WHERE email='$email'";

		// Execute SQL Statement and handle if SQL statement execution fails
		$queryResult = @mysqli_query($conn,$queryString) 
			OR die("<p>Unable to execute SELECT Query. </p>\r\n"
			."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");

		// count # of rows returned FROM SELECT QUERY
		$countRows = mysqli_num_rows($queryResult); 

		// Condition: Check whether email exists in the database?
		// CASE 1: Customer Email Found in CUSTOMER table
		if($countRows > 0){
			$row = mysqli_fetch_row($queryResult); // extract the first row/record of the query result
			$custName = $row[0]; // extract the customer name field

		// Case 2: Customer Email Not FOUND in CUSTOMER table
		} else {
			$custName = "-1";
		}

		mysqli_free_result($queryResult); // Free result pointer
		mysqli_close($conn);	 // Close DB Connection

		// Return Customer Name
		return $custName; 
	}

	/* 
	 * generateMessage():
	 *  (1) Displays confirmation message of booking
	 *  (2) Sends confirmation email to the customer (bounced email)
	 *
	 * @inputs: $refNo - refers to Booking Reference Number
	 *			$email - refers to Customer Email Address
	 *          $pickupDateTime - scheduled date/time for pickup
	 * @ouput:  HTML with confirmation message
	 * @return: none
	 */
	function generateMessage($refNo, $email, $pickupDateTime){
		// Shared Message used by output and mail()
		// Message defined by Task 2 - Part 4 Requirement
		echo "<section id='confirmMsg'>\r\n";
		$msgPartA = "Your booking reference is $refNo. ";
		$msgPartB = "We will pick up passengers in front of your provided address at "
			. date("g:i A ", strtotime($pickupDateTime)) . " on "
			. date("d M Y",strtotime($pickupDateTime));
		$confirmMsg = $msgPartA . "<br/>\r\n " .$msgPartB;


		/************* DISPLAY MESSAGE  ***********************/
		echo "<h3>Confirmation Message</h3>\r\n"
			. "<p>Booking Request has been created. <br/>"
			. "Thank you! " . $confirmMsg . ". </p>\r\n";


		/************* SEND MESSAGE via mail() ***********************/
		// Reference: Task 2 - Part 4 Assignment Outline
		$custName = getCustomerName($email); // Customer Name
		$to = $email; // Email Address of Customer
		$subject = "You booking request with CabsOnline"; // Email Subject Line
		$message = "Dear $custName,\r\nThanks for booking with CabsOnline!\r\n" . $msgPartA . "\r\n" . $msgPartB;
		$header = "From: booking@cabsonline.com.au";

		// Attempt to send the mail message
		// Note: The Email will bounced back to user's inbox
		$sendMail = mail($to,$subject,$message,$header,"-r 102125413@student.swin.edu.au");
		if($sendMail){
			echo "<p><strong>Success:</strong> Email was succesfully sent to $email</p></section>\r\n";
		} else {
			echo "</section><p id='failedEmail'><strong>Warning:</strong> Confirmation Email was not sent to $email. </p>\r\n";
		}
		

		// Display other menu options
		echo "<section id='menuOptions'>\r\n";
		echo "<p><strong>Menu Option:</strong> <a href='booking.php'>Create Another Booking</a><br/></p>\r\n ";
		echo "</section>\r\n";


	}




	/************************************ MAIN SECTION OF CODE **************************************/
	/**********************************************************************************************/

	// Extract data from the form
	// Common Components
	$passName = isset($_POST['pName']) ? sanitiseInputs($_POST['pName']) : "" ; 
	$passPhoneNo = isset($_POST['phoneNum']) ? sanitiseInputs($_POST['phoneNum']) : "" ; 
	$unitNo = isset($_POST['unitNum']) ? sanitiseInputs($_POST['unitNum']) : "" ;
	$streetNo = isset($_POST['streetNo']) ? sanitiseInputs($_POST['streetNo']) : "" ;
	$streetName = isset($_POST['streetName']) ? sanitiseInputs($_POST['streetName']) : "" ;
	$picksuburb = isset($_POST['picksuburb']) ? sanitiseInputs($_POST['picksuburb']) : "" ;
	$destsuburb = isset($_POST['destsuburb']) ? sanitiseInputs($_POST['destsuburb']) : "" ;
	$pickupDate = isset($_POST['pickupDate']) ? sanitiseInputs($_POST['pickupDate']) : "" ;
	$pickupTime = isset($_POST['pickupTime']) ? sanitiseInputs($_POST['pickupTime']) : "" ;




	// CASE 1: Display Form - Redirected from login/register page (First Time)
	if( isset($_SESSION["emailaccount"]) && !isset($_POST['email']) ) {
		// extract email address from PHP session
		$email = sanitiseInputs($_SESSION["emailaccount"]);
		// display the HTML form
        displayForm($email,$passName,$passPhoneNo,$unitNo,$streetNo,$streetName,$picksuburb,$destsuburb,$pickupDate,$pickupTime);

	// CASE 2: User completes the Registration Form. Process the form.
	} else if(isset($_SESSION['emailaccount']) && isset($_POST['email']) && 
		sanitiseInputs($_POST['email']) == sanitiseInputs($_SESSION['emailaccount']) ){

		// Extract email address from (1) the submitted FORM via $_POST[] or (2) the session variable
        $email = sanitiseInputs($_POST['email']) OR sanitiseInputs($_SESSION["emailaccount"]);
        
        // Get the Current Time 
        $curDateTimeString = date("Y-m-d H:i:s");
        
        // Convert pickup input field into date String
        $targetDateTimeString = date("$pickupDate {$pickupTime}:00");
        
        // VALIDATE INPUT FORM FIELDS
        $errMsg = validateForm($passName,$passPhoneNo,$unitNo,$streetNo,$streetName,$picksuburb,$destsuburb,$pickupDate,$pickupTime,$curDateTimeString);
        
        // Outcome of Input Field Validation
        // Case A: Form contain errors - incomplete fields or fields don't match required formats
        if($errMsg){
            
			// Display Error Messages
			echo "<section class='errMsg'>\r\n";
            echo "<h3>FORM VALIDATION ERRORS</h3>";
			echo "<span><strong>Please check the following fields:</strong> <br/>"
				  .$errMsg ."</span>\r\n";            
			echo "</section>\r\n";
			
            // Display the Form - with prefilled data
            displayForm($email,$passName,$passPhoneNo,$unitNo,$streetNo,$streetName,$picksuburb,$destsuburb,$pickupDate,$pickupTime);

        }
        // Case B: Form contains valid data. THEN CREATE NEW record in DB (e.g. create new Booking ENTRY)
        else {
            // Add new record to DB and retrieve the booking/reference number associated with it.
            $refNo = addRecord($email,$passName,$passPhoneNo,$streetNo,$streetName,$picksuburb,$destsuburb,$targetDateTimeString,$curDateTimeString);
            // CASE:  New Recorded was Added Succesfully
            if($refNo >= 0){
            	// Display HTML Confirmation Message and Send Email Confirmation
                generateMessage($refNo, $email,$targetDateTimeString); 
            }

        }
	} // Case 3: User visits "booking" page without providing email (e.g. has not logged in or registered)
    else {
        echo "<section id='notLoggedInMsg'>\r\n";
		echo "<p> <strong>Error: Please login in or register first. </strong> <br/> Redirecting to Login Page ... </p>\r\n";
		echo "</section>\r\n";
		//header("location: login.php");
		// Reference: https://stackoverflow.com/questions/18305258/display-message-before-redirect-to-other-page
		echo "<script>setTimeout(\"location.href='login.php';\",2500);</script>";
		
    }
?>
<footer>
    <p>CabsOnline Pty Ltd &copy; 2020. All rights reserved. </p>
</footer>
</body>
</html>