<?php
	// STUDENT ID: 102125413 VINH HUYNH
	session_start();

	// If there user already logged in. Automatically, Log them out.
	if(isset($_SESSION['emailaccount'])){
		unset($_SESSION['emailaccount']); // unset variable
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Customer Registration"/>
    <meta name="keywords" content="taxi,register,customer"/>
    <meta name="author" content="Vinh Huynh - 102125413"/>
    <title> Registration - CabsOnline </title>
    <!-- Style Sheets -->
    <link href="styles/register.css" rel="stylesheet"/>
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
			<a href='login.php'>Login</a>
			<a id='curPage' href='register.php'>Register</a>
			<a href='booking.php'>Booking</a>
			<a href='admin.php'>Admin</a>
		</nav>
	</header>

	<section id='registerBox'>
		<h1> Register to CabsOnline </h1>
		<p> Please fill the fields below to complete your registration </p>
	
		<!-- Note: novalidate="novalidate" means HTML5 form validation is disabled,thus only PHP validation rules are executed-->
		<form method="post" action="register.php" novalidate="novalidate">

			<!-- Note: The form may be prefilled, when the user completes it with invalid or partial data-->
			<!--       The prefilled feature is implemented using inline php expression within the 'value' attribute of <input> -->
			<!--       Passwords will not be prefilled -->
			<p>
				<label for="pName">Name: </label>
				<input id="pName" type="text" name="pName" required="required" pattern="^[a-zA-Z -]+$" 
					title="Name can only consist of letters, hyphens and spaces" placeholder="John Doe"
					value="<?php (isset($_POST['pName'])) ? ($out=$_POST['pName']) : ($out='') ; echo $out ?>" /> 
			</p>
			<p>
				<label for="password">Password: </label>
				<input id="password" type="password" name="password" required="required" autocomplete="new-password"/>
			</p>
			<p>
				<label for="confirmPassword">Confirm password: </label>
				<input id="confirmPassword" type="password" name="confirmPassword" required="required" autocomplete="new-password"/> 
				<!-- note that: PHP script will check confirm password -->
			</p>
			<p>
				<label for="email">Email: </label>
				<input id="email" type="email" name="email" required="required" placeholder="johndoe@gmail.com"
					value="<?php (isset($_POST['email'])) ? ($out=$_POST['email']) : ($out='') ; echo $out ?>"/>
			</p>
			<p>
				<label for="phoneno">Phone: </label>
				<!-- Reference for Phone Number Regular Expression -->
				<!-- Reference: https://stackoverflow.com/questions/7126345/regular-expression-to-require-10-digits-without-considering-spaces -->
				<input id="phoneno" type="text" name="phoneno" pattern="^(\s*\d\s*){8,}$" required="required" 
					title="Phone No must consist with at least 8 digits (spaces are allowed)" placeholder="9000 8000"
					value="<?php (isset($_POST['phoneno'])) ? ($out=$_POST['phoneno']) : ($out='') ; echo $out ?>" />
			</p>
			<p>
				<input type="submit" value="Register"/>
			</p>
		</form>

		<p> <strong>Already Registered?</strong> <a href="login.php"> Login Here</a></p>
	</section>

	<?php
		/************************** USER DEFINED FUNCTIONS SECTION ****************************************/
	    /*************************************************************************************************s/

		/* sanitiseInput() is a function that sanitises the input 
		 * from the user.
		 *
		 * @inputs: any given string
		 * @return: modified string that has been sanitised.
		 */
		function sanitiseInput($input){
			$input = trim($input); // remove spaces at front/end of string
			$input = stripslashes($input); // remove slashes
			$input = htmlspecialchars($input); // convert special chars to html entities

			return $input;
		}

		/**
		 * displayFormData() displays the form data submitted to PHP server side 
		 * for Testing Purposes.
		 * @inputs: personName - the person's name
		 			password - refers to user's password field
		 			confirmPassword - refers to confirmed password field
		 			email - refers to the person's email address
		 			phoneno - refers to the person's phone number
		 * @output: HTML elements that displays the form data submitted by the user
		 * 
		 */ 
		function displayFormData($personName,$password,$confirmPassword,$email,$phoneno){
			echo "<section id='formData'>";
			echo "<h3> ---- Submitted Form Data ----  </h3>\r\n";
			echo "<p>Person Name: $personName <br/>\r\n"
				."Password: $password <br/>\r\n"
				."Confirmed Password: $confirmPassword <br/>\r\n"
				."Email: $email <br/>\r\n"
				."Phone No: $phoneno </p> \r\n";
			echo "</section>";
		}


		/**
		 * validateForm() validates the fields of the form data
		 * @inputs - personName, password,confirmPassword,email,phoneno
		 * @returns - errMsg containing any validation error messages 
		 *
		 *
		 */
		function validateForm($personName,$password,$confirmPassword,$email,$phoneno){

			// String that accumulates error messages
			$errMsg = "";

			/********************************  VALIDATION RULES  *************************************/
			// Validation Rule 1: Name can only consist of letters, hyphens and spaces
			// Case 1: Name is empty
			if(empty($personName)){
				$errMsg .= "<strong>Name:</strong> (1) cannot be empty (2) It can only consist of letters, hyphens and spaces are allowed. <br/>\r\n";
			// Case 2: Name doesn't satisfy the required format
			}else if(!preg_match("/^[a-zA-Z -]+$/",$personName)){
				$errMsg .= "<strong>Name:</strong> Invalid Format. Name can only consist of letters, hyphens or spaces. <br/>\r\n";
			}

			// Validation Rule 2: Passwords
			// Subrules: (a) Passwords can't be empty (b) password and confirmPassword must be the same
			// Case 1: "password" field is empty
			if(empty($password)){
				$errMsg .= "<strong>Password:</strong> cannot be empty <br/>\r\n";
			} 
			// Case 2: "confirm password" is empty
			if (empty($confirmPassword)){
				$errMsg .= "<strong>Confirm Password:</strong> cannot be empty <br/>\r\n";
			
			}
			// Case 3: "password" and "confirmPassword" are not the same.
			if( $password !== $confirmPassword){
				$errMsg .= "<strong>Confirm Password:</strong> does not match the 'password' field. Mismatched Confirm Password Field<br/>\r\n";
			}

			// Validaiton Rule 3: Email Address
			// Condition: Check that email address is a valid email address (e.g. correct format)
			// Reference: https://www.w3schools.com/php/php_form_url_email.asp
			// Case 1: Email Address is empty
			if(empty($email)){
				$errMsg .= "<strong>Email:</strong> cannot be empty <br/>\r\n";
			} 
			// Case 2: Email address is not valid
			else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
				$errMsg .= "<strong>Email:</strong> is not a valid email address (e.test@gmail.com) <br/>\r\n";
			}

			// Validation Rule 4: Contact Phone Number
			// Phone Number Format: Must consist with least 8 digits where optional spaces
			// Case 1: Phone Number field is empty
			if(empty($phoneno)){
				$errMsg .= "<strong>Phone Number:</strong> cannot be empty <br/>\r\n";
			// Case 2: Phone Number fields doesn't satisfy required format
			} else if(!preg_match("/^(\s*\d\s*){8,}$/",$phoneno)) {
				$errMsg .= "<strong>Phone number:</strong> requires a minimum of 8 digits where spaces are allowed (e.g. 9214 8000) <br/>\r\n";
			}

			return $errMsg;
		}


		/**
		 * emailExists() checks whether the email exists in CUSTOMER table in the Database
		 * Precondition: Assume that input is in the valid email address format (e.g. host@gmail.com)
		 * @inputs:  $email represents customer's email address
		 * @returns: true - if email exists in DB .Otherwise return false.
		 *
		 */
		 function emailExists($email){
			// Import SQL connection parameters: Host, User, Password, Database Name
			require("settings.php"); 

		 	$found = false; // Assume the email doesn't exist in the DB.

	 		// Attempt to Establish DB Connection - $host,$user,$pwd,$sql_db
	 		// die() to handle unsuccessful connection if mysqli_connect() returns false (indicate failure)
			$conn = mysqli_connect($host,$user,$pwd,$sql_db) OR die("<p> Unable to connect to the DB server <br/>" .
					"Error Code: " . mysqli_connect_errno() . " : " .
					mysqli_connect_error() . "</p>\r\n");

			// Create SQL Query String
			$sqlTable = "CUSTOMER";
			$query = "SELECT * FROM $sqlTable WHERE email='$email'";
			
			// Execute the SQL statement 
			// die() handles failed execution of SQL statement if mysqli_query() returns false (indicate failure)
			$queryResult = @mysqli_query($conn,$query) OR die("<p>Unable to execute the EMAIL EXIST query. <br/>".
						"Error Code " . mysqli_errno($conn) .
						" : " . mysqli_error($conn) . "</p>\r\n");

			// Count Number of rows returned in SQL Query Result
			$countRows = mysqli_num_rows($queryResult);

			// Check if the record exists (e.g. row count should be 1)
			// Case 1: Email Address already exists in database
			if($countRows > 0){
				$found = true;
			}

			// terminate DB connection
			mysqli_close($conn);

			return $found;
		 	
		 } 


		 /**
		  * insertRecord() - inserts a new customer record in the CUSTOMER table in the database
		  *
		  * @input $email,$personName,$password,$phoneno
		  * @output - new record inserted into the DB
		  *
		  */
		 function insertRecord($email,$personName,$password,$phoneno){
			// Import SQL connection parameters: Host, User, Password, Database Name
			require("settings.php"); 

		 	// Attempt to Establish DB Connection - $host,$user,$pwd,$sql_db
	 		// die() to handle unsuccessful connection if mysqli_connect() returns false (indicate failure)
			$conn = mysqli_connect($host,$user,$pwd,$sql_db) OR die("<p> Unable to connect to the DB server <br/>" .
					"Error Code: " . mysqli_connect_errno() . " : " .
					mysqli_connect_error() . "</p>\r\n");

			
			// Create SQL Query String
			$sqlTable = "CUSTOMER";
			$query = "INSERT INTO $sqlTable(email,custname,pwd,phoneno) values('$email','$personName','$password','$phoneno')";

			// Execute the SQL statement where $email is Primary Key
			// Note: @mysqli_query will return false if 
			$queryResult = @mysqli_query($conn,$query);
			
			// Case 1: Query was executed successfully
			if($queryResult){
				$addRecord = true; 
			}
			// Case 2: Failed Execution of SQL statement  (e.g. duplicate email primary Key)
			else {
				$addRecord = false;
				//die("<p>Unable to execute the EMAIL EXIST query. <br/>". "Error Code " . mysqli_errno($conn) ." : " . mysqli_error($conn) . "</p>\r\n");
			}

			// terminate DB connection
			mysqli_close($conn);

			return $addRecord;
		 }


    /************************************ MAIN SECTION OF CODE *********************************************/
    
	/*****************************  Processing Form Data *******************************/
	// Import SQL connection parameters: Host, User, Password, Database Name
	require("settings.php"); 
		// CONDITION: Check whether the user has submitted form data (e.g. POST parameters)
		if(isset($_POST["pName"]) && isset($_POST["password"]) && isset($_POST["confirmPassword"]) 
			&& isset($_POST["email"]) && isset($_POST["phoneno"])){

			// Extract and sanitise inputs
			$personName = sanitiseInput($_POST["pName"]);
			$password = $_POST["password"];
			$confirmPassword = $_POST["confirmPassword"];
			$email = sanitiseInput($_POST["email"]);
			$phoneno = sanitiseInput($_POST["phoneno"]);

			// Testing Purposes - Display Form Data entered by the user
			//displayFormData($personName,$password,$confirmPassword,$email,$phoneno);

			// String that accumulates error messages - Validation and SQL Error Messages (e.g. email already exists)
			$errMsg = "";

			// Validate the Form Fields - Format and Password/ConfirmPassword match
			$errMsg = $errMsg . validateForm($personName,$password,$confirmPassword,$email,$phoneno);
			

			// Case A: There is at least 1 validation error
			if($errMsg != ""){
				// Display validation errors.
				echo "<section class='errMsg'>";
				echo "<h3>FORM VALIDATION ERRORS</h3>\r\n";
				echo "<p><strong>Please check the following fields:</strong>\r\n <br/>\r\n"
					.$errMsg
					."</p>\r\n";
				echo "</section>";
			// Case B: User submitted inputs that are valid.
			} else {
				echo "";
				// Check whether email address already exists in database?
				$foundEmail =  emailExists($email);
				// Case 1: Email already exists in the database
				if($foundEmail){
					echo "<section class='errMsg'>";
					echo "<p><strong>Error:</strong> Customer has already registered this email in database. <br/>\r\n"
						." Options: (1) Register with different email address or (2) Login with email that has been already registered </p>\r\n";
					echo "</section>";
				} // Case 2: Email doesn't exist in DB. Add new record to Customer Table.
				else {
					// Insert new record in Customer DB
					$insertSuccess = insertRecord($email,$personName,$password,$phoneno);
					// Case 1: Inserted new customer with success.
					if($insertSuccess){
						echo "<section id='successMsg'>";
						echo "<p> New account has been registered with email: $email </p>\r\n";
						echo "</section>";
						// Set PHP session variable
						$_SESSION['emailaccount'] = $email;

						// Redirect to Booking page
						header("Location:booking.php");
					// Case 2: Insert of new customer failed.
					} else {
						echo "<section class='errMsg'>";
						echo "<p>ERROR: Insertion of new record failed </p>\r\n";
						echo "</section>";
					}
				}

			}
		}
	?>
<footer>
    <p>CabsOnline Pty Ltd &copy; 2020. All rights reserved. </p>
</footer>
</body>
</html>