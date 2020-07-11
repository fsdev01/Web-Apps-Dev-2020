<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="description" content="Add Inventory Item"/>
	<meta name="keywords" content="inventory,stock,add"/>
	<meta name="author" content="VH"/>
	<title> Inventory - Add New Item</title>
</head>
<body>
	<h2> Add new item(s) to Inventory </h2>

	<!-- Create Form for new inventory item -->
	<form method="get">
		<p> <label>Make: <input type="text" name="make"></label> </p>
		<p> <label>Model: <input type="text" name="model"></label> </p>
		<p> <label>Price: <input type="text" name="price"></label> </p>
		<p> <label>Quantity: <input type="text" name="quantity"></label> </p>
		<p> <input type="submit" value="add"/></p>
	</form>

</body>





<?php

	require_once("localsetting.php");
    // Function Defintion Block - Sanitise Inputs
    function sanitise_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


	// Section A: Add new item to inventory table
	
	// Check if the input variables are set by the form
	if(isset($_GET['make']) && isset($_GET['model']) && isset($_GET['price']) && isset($_GET['quantity']) ){

		// Check that the fields 'value' is not empty
		$make = sanitise_input($_GET['make']);
		$model = sanitise_input($_GET['model']);
		$price = sanitise_input($_GET['price']);
		$quantity = sanitise_input($_GET['quantity']);

		// Check for input string
		if(empty($make)|| empty($model) || empty($price) || empty($quantity) ) {
			echo "<p> Form is Incomplete. Please fill out form.</p>\r\n";
		}
		else { // Case: Fields are non-empty
				// Note: mysqli_connect() returns postitive number if successful conneciton, otherwise return False
			$conn = @mysqli_connect($host,$user,$pwd,$sql_db)
					OR die("<p> Unable to connect to database. </p>\r\n"
							."<p> Error Code " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "</p>\r\n" );
			// Create Query String
			$queryString = "INSERT INTO inventory(make,model,price,quantity)
							VALUES ('$make','$model','$price','$quantity')";

			$queryResult = @mysqli_query($conn,$queryString)
							OR die("<p>Unable to execute the query.</p>"
								."<p>Error Code " . mysqli_errno($conn) . " : " . mysqli_error($conn) . "</p>\r\n");

			echo "<p>Successfully added " . mysqli_affected_rows($conn) . "record(s). </p>";

			// Close DB connection
			mysqli_close($conn);
		}

	}


	// Section B: Show the table
	echo "<hr/>";





	// SECTION B: QUERY SEARCH - LIST OF CAR MODELS FOR THE GIVEN MODEL

	// Connect to the DB
	$conn = @mysqli_connect($host,$user,$pwd,$sql_db)
			OR die("<p> Unable to connect to database. </p>\r\n"
					."<p> Error Code " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "</p>\r\n" );		

	// Query String
	$queryString = "SELECT Make,Model,Price,Quantity FROM inventory";


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

	

?>


</body>
</html>