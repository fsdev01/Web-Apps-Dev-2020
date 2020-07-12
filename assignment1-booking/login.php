<?php
	// STUDENT ID: 102125413 VINH HUYNH
	session_start();

	// If there is user already logged in. Automatically, Log them out.
	if(isset($_SESSION['emailaccount'])){
		unset($_SESSION['emailaccount']); // unset variable
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Login - Customer"/>
    <meta name="keywords" content="taxi,login,customer"/>
    <meta name="author" content="Vinh Huynh - 102125413"/>
    <title> Customer Login - CabsOnline </title>
    <!-- Style Sheets -->
    <link href="styles/login.css" rel="stylesheet"/>
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
			<a id='curPage' href='login.php'>Login</a>
			<a href='register.php'>Register</a>
			<a href='booking.php'>Booking</a>
			<a href='admin.php'>Admin</a>
		</nav>
	</header>
    
    <section id="loginBox">
        <h1> Login </h1>
        <form method="post" action="login.php" novalidate="novalidate">
		<p>
			<label for="email">Email</label>
			<input id="email" type="email" name="email" placeholder="johndoe@gmail.com"/>
		</p>
		<p>
			<label for="password">Password</label>
			<input id="password" type="password" name="password" placeholder="password"/>
		</p>
		<input id='loginBtn' type="submit" value="Log In"/>
        </form>
        <p> <strong>New member? </strong> <a href="register.php">Register Now</a></p>
	</section>
<?php
	echo "	<section class='msgBox'>\r\n";
	// Import SQL connection parameters: host, username,pwd and DB_Name
	require_once("settings.php");

	/* sanitiseInput() is a function that sanitises the input 
	 * from the user.
	 *
	 * @inputs: $input is any given string
	 * @return: modified string that has been sanitised.
	 */
	function sanitiseInput($input){
		$input = trim($input); // get rid of leading/trailing whitespaces
		$input = stripslashes($input); // get rid of slashses
		$input = htmlspecialchars($input); // get ride of special characters
		return $input;
	}



	/***************************** SECTION 1: Processing Form Data *******************************/

	// Check whether the user has submitted form data (HTML FORM) via POST request 
	if(isset($_POST['email']) && isset($_POST['password'])){

		// String that accumulates error messages
		$errMsg = ""; 

		// Extract email and password from $_POST global variable
		$email = sanitiseInput($_POST['email']);
		$password = $_POST["password"];

		//Validation Rule 1: Email Address can't be empty
		if(empty($email)){
			$errMsg .= "<strong>Error:</strong> Email Address cannot be empty <br/>\r\n";
		} 
		// Validation Rule 2: Email Address must be a valid format 
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			// Reference: https://www.w3schools.com/php/php_form_url_email.asp
			$errMsg .= "<strong>Error:</strong> Invalid Email Address Format <br/>\r\n";
		}

		//Validation Rule 3: Password can't be empty
		if(empty($password)){
			$errMsg .= "<strong>Error:</strong> Password cannot be empty <br/>\r\n";
		}




	/***************************** SECTION 2: SQL operations - Verfiy Password against DB ***************/

		//Case A: Display any error messages about invalid email address format
		if($errMsg != ""){
			echo "<p class='errMsg'> $errMsg </p>\r\n";
		// Case B: Perform SQL Operatons AND check whether the email/password matches against DB
		} else {

			// Establish DB Connection
			// @ - supress errmsg
			$conn = @mysqli_connect($host,$user,$pwd,$sql_db);


			// Condition: Check whether database connection was established?
			// Case: Handle failed database connection
			if(!$conn){
				echo "<p>Unable to connect to the database server</p>\r\n";
				die("<p> Unable to connect database"
					."Error Code " . mysqli_connect_errno()
					." : " . mysqli_connect_error() . "<p>\r\n");
			}


			// Construct Query String for SQL execution
			$query = "SELECT * FROM CUSTOMER WHERE email='$email'";

			// Excute query statement
			$queryResult = mysqli_query($conn,$query);

			// Condition: Check whether SQL statement was executed succesfully
			// Case: SQL statement executed without any problems
			if($queryResult){

				// Extract first row/record from query result as associative array
				$row = mysqli_fetch_assoc($queryResult);

				// Case 1: First row/record is not empty (e.g. does not point to null)
				if($row){

					// Extract "email" and "password" fields from the first row of the result query
					$retEmail = $row["email"];
					$retPwd = $row['pwd'];


					// IMPORTANT CONDITION: Check whether username/password matches DB records
					// i.e. check if password matches against the DB
					// Case 1: Password matches the database record
					if($retPwd === $password){
						echo "<p>Logged in Succesfully. Redirecting to Booking Page ... </p>\r\n";

						// Set PHP session variable
						$_SESSION['emailaccount'] = $email;

						// Redirect to "Booking Page" as per Task 1 Requirement
						header("Location:booking.php");
					}
					// Case 2: Password doesn't match DB record (mismatch of username/pwd)
					else {
						echo "<p class='errMsg'><strong>Error:</strong> Incorrect login details </p>\r\n";
					}


				// Case 2: First row/record is empty - implies customer hasn't registered an account yet
				// E.g. $row points to null - mysqli_fetch_assoc()
				} else {
					echo "<p class='errMsg'><strong>Error:</strong> No records exist with that email address.<br/>\r\nPlease Register an Account.</p>\r\n";
				}

				// Free Result Pointer
				mysqli_free_result($queryResult);

			// Case: Failed execution of SQL statement
			} else {
				echo "<p class='errMsg'><strong>Error:</strong> Failed SQL Statement Execution: Query Lookup Failed </p>\r\n";
			}
			 
			// Terminate database connection
			mysqli_close($conn);
		}
		
	}

	echo "</section>\r\n";
?>

<footer>
    <p>CabsOnline Pty Ltd &copy; 2020. All rights reserved. </p>
</footer>
    
    
</body>
</html>