<?php
	// STUDENT ID: 102125413 VINH HUYNH
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Admin Page"/>
    <meta name="keywords" content="taxi,admin,"/>
    <meta name="author" content="Vinh Huynh - 102125413"/>
    <title> Admin Page - CabsOnline </title>
    <!-- Style Sheets -->
    <link href="styles/admin.css" rel="stylesheet"/>
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
			
			echo "<a href='booking.php'>Booking</a>\r\n";
			echo "<a id='curPage' href='admin.php'>Admin</a>\r\n";
			
			// Customised Menu - Display logout button if user is logged in
			if(isset($_SESSION['emailaccount'])){
				echo " <a href='logout.php'>logout</a>\r\n";
			}
			?>
		</nav>
	</header>

	<section id="adminBox1">
		<h1>Admin page of CabsOnline </h1>
		<h3>1. Click below button to search for all unassigned booking requests with a pick-up time within 3 hours </h3>
		<form method="get" action="admin.php?">
			<input type="submit" value="List All"/>
			<input type="hidden" name="menu" value="getrecords"/>
		</form>
		<br/>
	</section>
<?php
	// STUDENT ID: 102125413 VINH HUYNH

	// Set timezone to Australia EST Time 
	// Reference: 	https://www.php.net/manual/en/function.date-default-timezone-get.php
	date_default_timezone_set("Australia/Melbourne");


	/******************************************** USER DEFINED FUNCTIONS SECTION ****************************************/
	/******************************************************************************************************************/

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


	/*
	 * displayBookings() - displays the list of booking requests which are: (1) within 3 hours from now and (2) not assigned
	 *
	 * @precondition: the web server has access to the DB Layer Server
	 * @inputs: none
	 * @ouputs: html table of all records 
	 * @returns: list of booking reference numbers that can change from "unassigned" to assigned" within 3 hours
	 *
	 */
	function displayBookings(){
		// import SQL connection parameters: host, username,pwd and DB_Name
		require("settings.php");

		// Attempt to establish DB Connection
		$conn = @mysqli_connect($host,$user,$pwd,$sql_db) 
				OR die("<p> Unable to connect database" ."Error Code " . mysqli_connect_errno()
				." : " . mysqli_connect_error() . "<p>\r\n");


		// Get Current Time as String (ignore milliseconds)
		$curTime = date("Y-m-d H:i:00"); 
		// Get new target time -> 3 Hours from Current TIme
		// Reference: https://stackoverflow.com/questions/11386308/add-x-number-of-hours-to-date
		$newTime = date("Y-m-d H:i:00",strtotime("+ 3 hours",strtotime($curTime)));

		// Testing Purposes:
		//echo "<p>Current Time: $curTime  &nbsp;&nbsp;&nbsp; <em>3 Hours Later is: $newTime</em> </p>";
		echo "<p id='curTime'><strong>Current Time: </strong> " . date("H:i ", strtotime($curTime)) . "</p>\r\n";

		// Create SQL SELECT QUERY STRING
		$queryString = "select b.bookno, c.custname, b.passname, b.passphoneno,"
					 	."b.unitno,b.streetno,b.streetname,b.pickupsuburb,b.destsuburb,"
					 	."b.pickupdatetime "
					 	."FROM CUSTOMER c, BOOKING b "
					 	."WHERE c.email = b.custemail AND assignedStatus = 'UNASSIGNED' "
					 	. "AND b.pickupdatetime >= '$curTime' AND b.pickupdatetime <= '$newTime' ";

		// Execute Query Statement
		$queryResult = @mysqli_query($conn,$queryString) 
						OR die("<p>Unable to execute SELECT Query Statement. </p>\r\n"."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");

		// Count number of rows returned from SELECT QUERY
		$rowCount = mysqli_num_rows($queryResult); 


		//Index Array of Booking Numbers that can change from "unassigned" to assigned" within 3 hours
		$bookRefArray = array(); 

		echo "	<section id='resultBox1'>\r\n";
		// Case 1: There is at least 1 request available to be "assigned"
		if($rowCount > 0){

			// Create Table with list of requests
			//echo "<br/>\r\n";
			echo "<table>\r\n"
				."<tr>\r\n"
				."<th>reference # </th>\r\n"
				."<th>customer name </th>\r\n"
				."<th>passenger name</th>\r\n"
				."<th>passenger contact phone</th>\r\n"
				."<th>pickup address</th>\r\n"
				."<th>destination suburb </th>\r\n"
				."<th>pick-up time </th>\r\n"
				."</tr>\r\n";

			// Obtain the first row/record of the query SELECT result
			$row = mysqli_fetch_assoc($queryResult); 
			// Loop through each row/record of the query result
			while($row){
			
				// Generate String for the Address
				$unitStreetNo= ($row['unitno'] == "") ? $row['streetno'] : $row['unitno'] . "/" . $row['streetno']; 
				$pickupAddress = "$unitStreetNo  {$row['streetname']}, {$row['pickupsuburb']}";
				$pickuptime = date("d M H:i",strtotime($row['pickupdatetime']));

				// Display Results
				echo "<tr>\r\n"
					."<td>{$row['bookno']} </td>\r\n"
					."<td>{$row['custname']}</td>\r\n"
					."<td>{$row['passname']} </td>\r\n"
					."<td>{$row['passphoneno']} </td>\r\n"
					."<td>$pickupAddress </td>\r\n"
					."<td>{$row['destsuburb']} </td>\r\n"
					."<td>$pickuptime</td>\r\n";

				// Add booking number to array
				array_push($bookRefArray,$row['bookno']);

				$row = mysqli_fetch_assoc($queryResult); // obtain reference/pointer to next row
			}
			echo "</table>\r\n";

			mysqli_free_result($queryResult); // free queryResult pointer
		}
		// Case 2: There are zero booking requests available 
		else {
			$pickuptime = date("d M H:i",strtotime($curTime));
			echo "	<p id='zeroBookingResult'><strong>Result: Zero bookings found.</strong> <em>All bookings within 3 hours from now <strong>{$pickuptime}</strong> have been assigned.</em><p>\r\n ";
		}
		echo "	</section><br/>\r\n";
		mysqli_close($conn); // close DB connection
		return $bookRefArray; // Return Array of Booking Numbers that can change from "unassigned" to assigned"

	}


	/*
	 * assignRefNo() changes the status of a "booking record" from "unassigned" to "assigned"
	 *
	 * @input: $refNo - booking reference number of the booking record/request that will be updated
	 * @return: $errMsg - string of any error messages
	 *
	 */
	function assignRefNo($refNo){
		// import SQL connection parameters: host, username,pwd and DB_Name
		require("settings.php");

		// String accumulate error messages
		$errMsg = "";

		// Attempt to establish DB Connection and handles DB connection problems
		$conn = @mysqli_connect($host,$user,$pwd,$sql_db) 
				OR die("<p> Unable to connect database" ."Error Code " . mysqli_connect_errno()
				." : " . mysqli_connect_error() . "<p>\r\n");


		// Create SQL UPDATE STRING
		$queryString = "UPDATE BOOKING SET assignedStatus='ASSIGNED' WHERE bookno = '$refNo'";

		// Execute SQL statement and handle any problems with executing SQL statement
		$queryResult = @mysqli_query($conn,$queryString) 
						OR die("<p>Unable to execute UPDATE Query Statement. </p>\r\n"
						."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");


		// Analyse whether executed query HAS UPDATED the row 
		$countRows = mysqli_affected_rows($conn); // $countRows should equal to 1.

		// Case 2: Zero rows updated because (1) booking no does not exist or (2) already assigned to booking or (3) Other
		if($countRows <= 0){
			$errMsg .=  "<strong>Error:</strong> Failed to assign booking request with Reference No. $refNo <br/>\r\n";

			// Attempt to Find out Reason
			// Create Query String
			$queryString = "SELECT * FROM BOOKING WHERE bookno = '$refNo'";
			// Execute SQL statement
			$queryResult = @mysqli_query($conn,$queryString) 
						OR die("<p>Unable to execute SELECT Query Statement. </p>\r\n"
						."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");
			$row = mysqli_fetch_assoc($queryResult); // Fetch first row


			// REASON 1: Booking Number doesn't exist
			if($row == NULL){
				$errMsg .= "<strong>Reason: </strong> Booking Reference No. $refNo does not exist in BOOKING Table<br/>\r\n";

			// REASON 2: Reference No. HAS BEEN ALREADY ASSIGNED
			} else {
				// Extract Assigned Field
				$assignedField = $row['assignedStatus'];
				// Case A: Booking already assigned
				if($assignedField == "ASSIGNED"){
					$errMsg .= "<strong> Reason: </strong>The Booking Reference No. $refNo has already been assigned <br/>\r\n";
				// Case B: unknown error
				} else {
					$errMsg .= "<strong>Unknown Reason.</strong>";
				}

			}
		}


		// Terminate DB connection 
		mysqli_close($conn);
		return $errMsg; // return error message string


	}



	/********************************************** MAIN SECTION OF THE CODE ***********************************************/
	/**********************************************************************************************************************/

	// MENU OPTION: Check whether "getRecords" button was clicked - submits "menu" GET parameter
	if(isset($_GET["menu"]) && $_GET["menu"] == "getrecords"){


		// Step 1: Display the HTML List of Bookings Requests that can be assigned within 3 hours
		// Also store list of booking ref numbers of the table.
		$bookRefArray = displayBookings();
		$countRequests = count($bookRefArray);
			


		// Step 2 - Display "Update" Option to "assign" a request
		// Case A: Only Display "Update" button if there is at least 1 request available to assign
		if($countRequests > 0){
			echo "	<section id='adminBox2'>\r\n";
			echo "<h3>2. Select a reference number below and click 'update' button to assign a taxi to that request</h3>\r\n";
			echo "<form method='get' action='admin.php'>\r\n";
			echo "<label for='refno'>Reference Number: </label>\r\n";
			echo "<select id='refno' name='refno'>\r\n";
			// Loop through list of booking reference numbers that can be potentially be assigned
			foreach($bookRefArray as $refnum){
				echo "<option value='{$refnum}'>{$refnum}</option>\r\n";
			}
			echo "</select>\r\n";

			//echo "<input id='refno' type='text' name='refno'/>\r\n"; // OLD METHOD - Input Number // TESTING PURPOSES
			echo "<input type='submit' value='Update'/>\r\n";
			echo "</form><br/>\r\n";
			echo "	</section>\r\n";
		} 
	}


	// MENU OPTION:  User has selected a "reference number" to update from "unassigned" to "assigned"
	if( isset($_GET["refno"]) ) {
		$refNo = sanitiseInputs($_GET["refno"]);  // extract and sanitise user input


		// Check that Reference number can't be empty
		// Case A: Reference Number is empty
		if(empty($refNo)){
			echo "	<section class='errMsg'>\r\n";
			echo "<p><strong>Error:</strong> Failed Attempt to assign booking request <br/>\r\n"
				."<strong>Reason:</strong> Reference Number cannot be empty</p>\r\n";
			echo "	</section>\r\n";
		} else {

			// attempt to assign ref number via SQL and get any error messages
			$errMsg = assignRefNo($refNo); 

			//Check if whether the booking was succesfully assigned using SQL 
			// Case 1: Succesfully assigned
			if($errMsg == ""){
				echo "	<section id='resultBox2'>\r\n";
				echo "<p><strong>SUCCESS:</strong> The booking request no. $refNo has been properly assigned </p>\r\n";
				echo "	</section>\r\n";
			}// Case 2: Booking request was not succesfully assigned 
			else {
				echo "	<section class='errMsg'>\r\n";
				echo "<p>$errMsg </p>\r\n";
				echo "	</section>\r\n";
			}

		}
	}
?>
<footer>
    <p>CabsOnline Pty Ltd &copy; 2020. All rights reserved. </p>
</footer>
</body>
</html>