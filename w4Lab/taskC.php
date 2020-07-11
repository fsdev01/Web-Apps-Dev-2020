<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="description" content="Inventory List"/>
	<meta name="keywords" content="inventory,stock,lookup"/>
	<meta name="author" content="VH"/>
	<title> Inventory List</title>
</head>
<body>
	<h2> List of Inventory </h2>

<?php
	
	require_once("localsetting.php");
	// SECTION A : Create the drop-down menu selection
	// Connect to DB
	// Note: mysqli_connect() returns postitive number if successful conneciton, otherwise return False
	$conn = @mysqli_connect($host,$user,$pwd,$sql_db)
			OR die("<p> Unable to connect to database. </p>\r\n"
					."<p> Error Code " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "</p>\r\n" );

	

	// Create the HTML Form
	echo "<form method=\"get\"> \r\n"
		."<p>\r\n<label for='carmake'>Please select a make: </label>\r\n"
		."<select id='carmake' name=\"make\"> \r\n"
		."     <option value='all'>All</option>\r\n";

	// Extract List of Models
	// Step 1: Create SQL Query String
	$queryString = "SELECT DISTINCT make FROM inventory ORDER BY make ASC";
	// Step 2: Execute the query and store result set in pointer $queryResult
	$queryResult = @mysqli_query($conn,$queryString);


	// Retrieve results as a string $row
	// mysql_fetch_row() returns array of the fields of the given record/row - pointer
	while($row = mysqli_fetch_row($queryResult)){
		// Only 1 attribute returned for each row
		echo "     <option value='{$row[0]}'>{$row[0]}</option>\r\n";
	}
	echo "</select>\r\n"
	. "</p>\r\n"
	."<p><input type='submit' value='Search'/> </p>\r\n"
	."</form>\r\n";


	// Clean up operations:
	// Frees up the memory, after using the result pointer
	mysqli_free_result($queryResult);
	// Close DB connection
	mysqli_close($conn);


	//$query = "SELECT Make,Model,Price,Quantity FROM inventory";



	// SECTION B: QUERY SEARCH - LIST OF CAR MODELS FOR THE GIVEN MODEL

	// Check whether the value is selected and is not empty
	if(isset($_GET['make']) && $_GET['make']!=""){

		// Extract car model field selected by the user
		$carMake = $_GET['make'];
		//echo "<p>Car Make $carMake</p>";

		// Connect to the DB
		$conn = @mysqli_connect($host,$user,$pwd,$sql_db)
				OR die("<p> Unable to connect to database. </p>\r\n"
						."<p> Error Code " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "</p>\r\n" );		

		// Query String
		// ASIDE NOTE: Convert to uppercase 
		$queryString = "";
		if($carMake=="all"){
			$queryString = "SELECT Make,Model,Price,Quantity FROM inventory";
		} else {
			$queryString = "SELECT Make,Model,Price,Quantity FROM inventory WHERE make ='{$carMake}'";
		}

		// Execute query string
		$queryResult = @mysqli_query($conn,$queryString) OR 
						die("<p>Unable to query the table 'inventory'. </p>\r\n"
							."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");

		// Count number of rows returned			
		$numRows = mysqli_num_rows($queryResult);
		// Case 1 : Empty Rows
		if($numRows == 0){
			echo "<p>Results: No rows returned </p>\r\n";
		} else{ // Case 2: >0 rows returend
			// Contruct table
			echo "<table border='1'>\r\n"
				."  <tr>\r\n"
				."		<th> Make </th>\r\n"
				."		<th> Model </th>\r\n"
				."		<th> Price </th>\r\n"
				."		<th> Quantity </th>\r\n"
				."	</tr>\r\n";

			// retrieve current record pointed by result pointer
			while ($row = mysqli_fetch_assoc($queryResult)){
				echo "  <tr>\r\n"
					."		<td> {$row['Make']} </td>\r\n"
					."		<td> {$row['Model']} </td>\r\n"
					."		<td> {$row['Price']} </td>\r\n"
					."		<td> {$row['Quantity']} </td>\r\n"
					."	</tr>\r\n"; 
			}

			echo "</table>\r\n";
		}
		// Frees up the memory, after using the result pointer
		mysqli_free_result($queryResult);
		mysqli_close($conn);

	}

?>


</body>
</html>