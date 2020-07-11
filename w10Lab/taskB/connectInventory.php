<?php
// Student ID: 102125413

class InventoryConnect {
	private $dbConnect; // DB Connection Object
	private $selectedCarMake; // car 'make' selected by the user

	// Reference: Slides 49,68 and 60- Lecture 10
	// Task B - Part 1:  Write constructor to initalise parameters for connecting to mySQL server
	function __construct(){
		$this->dbConnect = @new mysqli("feenix-mariadb.swin.edu.au","s102125413","280494","s102125413_db");
		$this->selectedMake = ""; 

		// Check whether there is an db connection error 
		if($this->dbConnect->connect_error){
			die("<p>Unable to connect to database server.</p>" 
				. "<p>Error Code:" . $this->dbConnect->errno . " : " . $this->dbConnect->connect_error . "</p>");
		}
	}


	/**
	 * getMakes(): gets "make" information from database.
	 * This information is used to populate the drop-down list for all makes
	 **/
	function getMakes(){
		$makeList = array(); // Create an empty array

		// Step 1: Create SQL Query String
		$queryString = "SELECT DISTINCT make FROM inventory ORDER BY make ASC";
		// Step 2: Execute Query
		$queryResult = $this->dbConnect->query($queryString) OR 
						die("<p>Unable to execute the query.</p>"
							. "<p>Error Code " 
							. $this->dbConnect->errno . " : " . $this->dbConnect->error . "</p>");

		//echo "<p> Count Rows:" . $queryResult->num_rows . "</p>"; 

		// Retrieve results as a string $row
		// mysql_fetch_row() returns array of the fields of the given record/row - pointer
		while($row = $queryResult->fetch_row() ) {
			// Only 1 attribute returned for each row
			array_push($makeList,$row[0]);
		}

		// Frees up the memory, after using the result pointer
		$queryResult->close();

		return $makeList; // return array of car manufactuers/makes

	}



	/**
	 * setMake() - set car 'make' which is selected by a user
	 * Setter method for the $selectedCarMake instance variable
	 **/
	function setMake($newMake){
		$this->selectedCarMake = $newMake;
	}


	/**
	 * getInventories() - get inventory data for a 'particular' make OR
	 * 'all' makes from database and display it on the page
	 **/
	function getInventories(){

		$carMake = $this->selectedCarMake; // get car make

		// Step 1: Create SQL Query String
		$queryString = "";
		if($carMake=="all"){
			$queryString = "SELECT Make,Model,Price,Quantity FROM inventory";
		} else {
			$queryString = "SELECT Make,Model,Price,Quantity FROM inventory WHERE make ='{$carMake}'";
		}

		// Step 2: Execute Query
		$queryResult = $this->dbConnect->query($queryString) OR 
						die("<p>Unable to execute the query.</p>"
							. "<p>Error Code " 
							. $dbConnect->errno . " : " . $dbConnect->error . "</p>");


		// Count number of rows returned			
		$numRows = $queryResult->num_rows;

		// Case 1 : Empty Rows
		if($numRows == 0){
			echo "<p>Results: No rows returned </p>\r\n";
		} else{ // Case 2: At least 1 row returend
			
			// Contruct table
			echo "<table border='1'>\r\n"
				."  <tr>\r\n"
				."		<th> Make </th>\r\n"
				."		<th> Model </th>\r\n"
				."		<th> Price </th>\r\n"
				."		<th> Quantity </th>\r\n"
				."	</tr>\r\n";

			// retrieve current record pointed by result pointer
			while ($row = $queryResult->fetch_assoc() ){
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
		$queryResult->close();
	}


	/**
	 * closeConnection(): close the db connection
	 **/
	function closeConnection() {
		$this->dbConnect->close(); 
	}


}




?>