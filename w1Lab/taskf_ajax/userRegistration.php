<!--file data.php -->
<?php
	
	// Step 1: check whether input is defined
	if( isset($_GET['namefield']) && isset($_GET['pwdfield']) && isset($_GET['gender']) && isset($_GET['age']) && isset($_GET['email']) ) {


		$name = $_GET['namefield'];
		$pwd = $_GET['pwdfield'];
		$gender = $_GET['gender'];
		$age = $_GET['age'];
		$email = $_GET['email'];

		// check if fields are empty
		if(empty($name) || empty($pwd) || empty($gender) || empty($age) || empty($email)){
			echo "<p> <em>ERROR: Form is Incomplete</em> </p>\r\n";
		} else {

			// Get time and date
			// Reference: https://www.php.net/manual/en/function.date.php
			date_default_timezone_set("Australia/Melbourne");
			$curTime = date("D M j G:i:s T Y");


			//sleep for 5 seconds to slow server response down
			//sleep(5);

			echo "<p> Registration Success! <br/>" 
			     . "Name: $pwd <br/>"
			     . "Password: $pwd <br/>"
			     . "Age: $age <br/>"
			     . "Email: $email <br/>"
			     . "Current Server Time: $curTime</p><br/>\r\n";
		}

	}

	






?>
