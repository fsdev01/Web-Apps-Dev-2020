<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="description" content="PHP Database"/>
	<meta name="keywords" content="PHP,Database,SQLI"/>
	<meta name="author" content="VH"/>
	<title>Database</title>
</head>
<body>
	
	<?php 
		include("connectInventory.php");
		
		// Create an object of the inventoryConnect class
		$db = new InventoryConnect();
		
		// Get the List of Manufactuers/Makes to display on HTML
		$manu = $db->getMakes();

		// Create a form
		echo "<form method='get'>\r\n"
		."<p><label for='carmake'>Please select a make: </label>\r\n"
		."<select id='carmake' name='make'> \r\n"
		."     <option value='all'>All</option>\r\n";

		foreach($manu as $item){
			echo "     <option value='{$item}'>{$item}</option>\r\n";
		}

		echo "</select>\r\n"
		. "</p>\r\n"
		."<p><input type='submit' value='Search'/> </p>\r\n"
		."</form>\r\n";
		

		
		// Menu Option: User selects a 'car' make
		if(isset($_GET['make'])){
			// Set Car Make 
			$db->setMake($_GET['make']);
			// Get inventory data and display it 
			$db->getInventories();
		}

		// Close DB Connection
		$db->closeConnection();

	?>

		



</body>
</html>