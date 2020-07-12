<?php
	// Student ID: 102125413
	// MAIN PURPOSE: LISTING OF A NEW ITEM 
	session_start(); //Create new session or resume existing one

	/********************** DEFINED FUNCTIONS **************************/
	require("util.php"); // import common functions and variables


 	/**
	 * validateForm() validates the fields of the form data
	 * @inputs - itemName,itemPrice,item quantity,item description
	 * @returns - errMsg containing any validation error messages 
	 * Task 4.1 - Data Validation for "Add New Item" Form
	 */
	function validateForm($itemName,$itemPrice,$itemQty,$itemDesc){
		$errMsg = ""; // stores error message as string

		// Validation rules
		// Validation Rule 1: item name can't be emtpy
		if(empty($itemName)){
			$errMsg .=  "Item Name cannot be empty <br/>\r\n";
		}

		//Validation Rule 2: item price can't be empty
		if($itemPrice == ""){
			$errMsg .=  "Price field must be filled. <br/>\r\n";
		}else if( !(filter_var($itemPrice,FILTER_VALIDATE_FLOAT) && $itemPrice > 0 ) ){
			$errMsg .=  "Invalid Price Format. It must be float/integer value such that > 0 <br/>\r\n";
		}

		//Validation Rule 3A: item qty can't be empty
		if(empty($itemQty)){
			$errMsg .=  "Quantity field cannot be empty <br/>\r\n";
		} // Validation Rule 3B: quantity must be a positive integer (>0)
		else if( !(filter_var($itemQty, FILTER_VALIDATE_INT) && $itemQty > 0)){
			$errMsg .=  "Invalid Quantity Format. It must be a positive integer. (i.e. whole number > 0) <br/>\r\n";
		} 

		
		//Validation Rule 4: item description can't be empty
		if(empty($itemDesc)){
			$errMsg .=  "Item description cannot be empty <br/>\r\n";
		}

		// Validation Rule 5: Item Name is unique (avoid duplicate items in system)
		if(checkItemExists($itemName)){
			$errMsg .="The Item Name is already exists in the system.<br/>\r\n";
		}

		return $errMsg;
	}


	/**
	 * checkItemExists() checks whether an item exists in XML file
	 *
	 * @input: $itemName - item name
	 * @output: "true" if item name exists in the goods.xml file. Otherwise, return "false"
	 */
	function checkItemExists($itemName){

		// XML file location
		$xmlFile = $GLOBALS['xmlGoodsPath'];

		// Condition: Checks whether the file exists
		// Case 1: File Does not Exist
		if(!file_exists($xmlFile)) {
			return false;
		} else {
		// Case 2: File Exists - Then Perform linear search
			$dom = DOMDocument::load($xmlFile);  // Load XML file into DOM Object
			$itemList = $dom->getElementsByTagName("item"); // Get the List of item "names"

			//Loop through items
			foreach($itemList as $node){

				// Get reference to the "item name" node (element)
				$itemnode = $node->getElementsByTagName("name");
				
				// Get reference to text node and then extract node value
				// item(0) - firstChild is textNode and thus extract value.
				$itemval = $itemnode->item(0)->nodeValue;

				// check whether current item matches the target
				if(strtolower($itemName) == strtolower($itemval) ){
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * generateId(): System generates the id of the next item to be added 
	 * to the catalogue goods.xml
	 * This depends on:
	 * (1) number of items that are currently in goods.xml 
	 * (2) number of items that have been processed/deleted in processedItems.xml
	 * @returns: unique id for the item
	 * Task 3.4 - System will generate item number
	 */
	function generateId(){

		// XML file locations
		$xmlPath1 = $GLOBALS['xmlGoodsPath'];
		$xmlPath2 = $GLOBALS['xmlProcessedPath'];

		$countItems = 0; // Count number of items in each file

		// Condition 1: goods.xml file exists
		if(file_exists($xmlPath1)){

			$dom = new DomDocument("1.0"); // Create DOM Object
			$dom->load($xmlPath1); // Load the xml file
			$countItems += $dom->getElementsByTagName("item")->length;

		}

		// Condition 2: processedItems.xml exists
		if(file_exists($xmlPath2)){
			$dom = new DomDocument("1.0"); // Create DOM Object
			$dom->load($xmlPath2); // Load the xml file
			$countItems += $dom->getElementsByTagName("item")->length;
		}

		// Calculate next id
		$nextId = $countItems + 1;
		return $nextId;
	}



	/**
	 * addItem() - adds new item to goods.xml file
	 *
	 * @precondition: assumes that checkItemExists() has been already executed
	 *                (enforce separation of responsibilities - validation function)
	 * @inputs:  $nextId - id of the new item
	 *           $itemName - item name
	 *           $itemPrice - price of the item
	 *           $itemQty - quanity of the item
	 *           $itemDesc - item description
	 * @outputs: modified "goods.xml" file or new file "goods.xml"
	 * @returns: true - goods.xml updated with new item. Otherwise, return false.
	 * Task 3.4 - Add item to "goods.xml" file
	 * Reference: Assignment 2 Hints - testRegister.php in "A2TestCode.zup"
	 *            Week 7 - Lab 7 - Task A/B
	 */
	function addItem($nextId,$itemName,$itemPrice,$itemQty,$itemDesc){
		
		$dom = new DomDocument("1.0"); // Create XML Dom Object
		$xmlPath = $GLOBALS['xmlGoodsPath']; // XML file location

		//Condition: Check whether the file exists?
		// Case 1: File Exists - Load File
		if(file_exists($xmlPath)){

			$dom->preserveWhiteSpace = FALSE;  // ignore existing white spaces in XML
			$dom->load($xmlPath); 	// Load xml file containing items

			// Get reference to root element <goods>
			$goods = $dom->getElementsByTagName("goods")->item(0);

			// Determine number of items in goods.xml file
			$count = $dom->getElementsByTagName("item")->length;
			//echo "Current Number of Items: $count <br/>\r\n";

		}
		// Case 2: File doesn't exist. Insert Root Element <goods> into XML DOM
		else{
			// Create <goods> as the root element (e.g. as child of XML DOM)
			$goods = $dom->appendChild($dom->createElement("goods"));
		}

		//Insert new <item> into the <goods> element

		//Element 1: Create <item> element and append to <goods> element
		$item = $goods->appendChild($dom->createElement("item"));

		//Element 2: Create <id> element and append to <item> element
		$itemid = $dom->createElement("id");
		$itemid = $item->appendChild($itemid);
		$itemidval = $dom->createTextNode($nextId); // Create text node with text value
		$itemidval = $itemid->appendChild($itemidval); // Append text node to <id>


		//Element 3: Create <name> element and append to <item> element
		$itemname = $item->appendChild($dom->createElement("name"));
		$itemnameval = $itemname->appendChild($dom->createTextNode($itemName)); // Append text node to <name>

		//Element 4: Create <description> element and append to <item> element
		$itemdesc = $item->appendChild($dom->createElement("description"));
		$itemdescval = $itemdesc->appendChild($dom->createTextNode($itemDesc)); // Append text node to <description>

		//Element 5: Create <price> element and append to <item> element
		$itemprice = $item->appendChild($dom->createElement("price"));
		$itempriceval = $itemprice->appendChild($dom->createTextNode($itemPrice)); // Append text node to <price>

		//Element 6: Create <qtyavailable> element and append to <item> element
		$itemqtyavail = $item->appendChild($dom->createElement("qtyavailable"));
		$itemqtyavailval = $itemqtyavail->appendChild($dom->createTextNode($itemQty));

		//Element 7: Create <qtyonhold> element and append to <item> element
		$itemqtyonhold = $item->appendChild($dom->createElement("qtyonhold"));
		$itemqtyonholdval = $itemqtyonhold->appendChild($dom->createTextNode(0)); 

		//Element 8: Create <qtysold> element and append to <item> element
		$itemqtysold = $item->appendChild($dom->createElement("qtysold"));
		$itemqtysoldval = $itemqtysold->appendChild($dom->createTextNode(0)); // Append text node element

		// Save DOM to File
		$dom->formatOutput = true;
		$saveResult = $dom->save($xmlPath);

		// Condition: Check whether the goods.xml file was saved? 
		// Case 1: Fail to add new item. Unable to write to goods.xml file due to file permissions. 
		if($saveResult == false){
			return false;
		} // Case 2: goods.xml was updated.
		else {
			return true;
		}

	}


	/**************** MAIN SECTION OF THE CODE ***************************/

	// Step 1: Check whether a manager is logged in 
	// Step 2: Check the action requested by user

	// CASE A: Manager must be logged in, in order to make product/item listing
	if(isset($_SESSION["mLogin"])){

		//Condition 1: User has submitted an "add" action request
		if(isset($_POST["action"]) && $_POST["action"] == "add"){
			// Condition 2: User has filled the form 
			if( isset($_POST["itemName"]) && isset($_POST["itemPrice"]) && isset($_POST["itemQty"]) && isset($_POST["itemDesc"])){

				// Get the parameters stored in $_POST
				$itemName = $_POST["itemName"];
				$itemPrice = sanitiseInput($_POST["itemPrice"]);
				$itemQty = sanitiseInput($_POST["itemQty"]);
				$itemDesc = $_POST["itemDesc"];

				// Perform Validation on Inputs and store validation error messages into variable
				$errMsg = validateForm($itemName,$itemPrice,$itemQty,$itemDesc);

				// Case 1:  Add new new product/item only if there is no validation messages
				if($errMsg == ""){

					$nextId = generateId(); // System Generates new <id> for the <item>
					$addResult = addItem($nextId,$itemName,$itemPrice,$itemQty,$itemDesc);

					// Condition: Check whether the goods.xml file was updated with new item
					// Case: goods.xml was updated with success
					if($addResult == true){
						echo "<p class='successMsgBox'><strong>Status: </strong> <span class='successMsg'> The item has been listed in the system, and the item number is $nextId </span></p>\r\n";
					} // Case: goods.xml was not updated due to file permission error
					else {
						echo "<p class='errMsgBox'><strong>Error Message:</strong><br/><span class='errMsg'>"
							."The item was not added. File Permission Error. Unable to write to goods.xml file</span></p>";
					}

				} // Case 2: Display Errors Messages
				else { 
					echo "<p class='errMsgBox'><strong>Error Messages:</strong><br/><span class='errMsg'>" . $errMsg ."</span></p>";
				}
			}

		}
	} else { // CASE B: Manager is not logged in 
		echo "<br/><div class='errMsgBox'><strong>Access Denied - Disallowed Operation:</strong> <span class='errMsg'>Manager must logged in first.</span></div><br/>\r\n";
	}





?>