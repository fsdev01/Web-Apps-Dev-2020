<?php
	// Student ID: 102125413
	// MAIN PURPOSE: ALLOW MANAGER TO PROCESS SOLD ITEMS
	
    session_start(); // Create new PHP session or resume existing one

    /********************** DEFINED FUNCTIONS **************************/
	require("util.php"); // import common functions and variables

	/**
	 * getProcessForm(): Generate HTML Code containing the table with non-zero sold items
	 * @returns: error string or HTML Code with Table of Items
	 * Task 5.1 - Display table with non-zero sold quanities from "goods.xml"
	 */
	function getProcessForm(){
		$xmlPath = $GLOBALS["xmlGoodsPath"]; // goods.xml 
		$xslPath = $GLOBALS["xslProcessFormPath"]; // processForm.xsl
		
		// Check that the XML and XSL files exist
		// Case 1: xml file doesn't exist
		if(!file_exists($xmlPath)){
			return "<p class='errMsgBox'><strong>Warning:</strong> <span class='errMsg'>goods.xml file doesn't exist. Please add items to catalogue.</span></p>\r\n";
		} // Case 2: xsl file does exist
		else if(!file_exists($xslPath)){
			return "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'>processForm.xsl file does not exist. </span></p>\r\n";
		} // Case 3: Both xml file and xsl file exists
		else {
			
			$xmlDoc = new DomDocument;  // Create XML DOM object
			$xmlDoc->load($xmlPath); // Load the XML file

			$xslDoc = new DomDocument;  // Create XML DOM object
			$xslDoc->load($xslPath); // Load the XSL file

			// Create XSLT Processor Object
			$proc = new XSLTProcessor; 
			// Import the stylesheet required for transformation
			$proc->importStyleSheet($xslDoc);

			// Transform the XML to HTML using the XSL file
			$output =  $proc->transformToXML($xmlDoc);

			return $output;
		}

	}

	/**
	 * processItems(): removes item(s) from "goods.xml" file where 
	 *      (1) qtyavailable is zero and
	 *      (2) qtyonhold is zero and
	 *      (3) qtysold iz non-zero
	 * @returns: an array containing two items:
	 *           (a) boolean value - true if goods.xml was updated with success (if required). otherwise, false.
	 *           (b) an array containing the removed/processed items
	 * Task 5.2 - Update XML "goods.xml" by clearing items using the above 3 conditions.
     */
	function processItems(){
		$xmlPath = $GLOBALS["xmlGoodsPath"]; // goods.xml file location

		//Condition: Check whether the file exists
		// Case 1: goods.xml doesn't exist
		if(!file_exists($xmlPath)){
			echo "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'>goods.xml file not found</span></p>";
		} else {
		// Case 2: "goods.xml" file exists
			
			$dom = new DomDocument("1.0");  // Create DOM Object
			$dom->preserveWhiteSpace = FALSE;  	// ignore existing white spaces in XML
			$dom->load($xmlPath); 			// Load xml file containing items

			// Get List of <item> in goods.xml
			$itemList = $dom->getElementsByTagName("item");
			// Get reference to <goods> element in goods.xml
			$goodsNode = $dom->getElementsByTagName("goods")->item(0);
			// Store List of nodes references to remove
			$nodesToRemove = array();
			// Store the <items> that was removed 
			$removedItems = array();

			// Loop over <item> list
			foreach($itemList as $node){

				// Extract values from the given <item>
				$itemId = $node->getElementsByTagName("id")->item(0)->nodeValue;
				$itemName = $node->getElementsByTagName("name")->item(0)->nodeValue;
				$itemDesc = $node->getElementsByTagName("description")->item(0)->nodeValue;
				$itemPrice = $node->getElementsByTagName("price")->item(0)->nodeValue;
				$itemQtyAvail = $node->getElementsByTagName("qtyavailable")->item(0)->nodeValue; 
				$itemQtyOnHold = $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue;
				$itemQtySold = $node->getElementsByTagName("qtysold")->item(0)->nodeValue;

				// Remove the <item> from goods.xml based on certain condition
				// (1) qtyavailable is zero (2) qtyonhold is zero (3) qtysold is non-zero
				if($itemQtyAvail == 0 && $itemQtyOnHold == 0 && $itemQtySold > 0 ){
					//echo "$itemId $itemName $itemDesc $itemPrice $itemQtyAvail $itemQtyOnHold $itemQtySold <br/>";
					echo "Removing Item No: $itemId <br/>";
					array_push($nodesToRemove,$node);

					// Stores the details of an <item> 
					$valueArray = array();
					$valueArray["id"] = $itemId;
					$valueArray["name"] = $itemName;
					$valueArray["description"] = $itemDesc;
					$valueArray["price"] = $itemPrice;
					$valueArray["qtyavailable"] = $itemQtyAvail;
					$valueArray["qtyonhold"] = $itemQtyOnHold;
					$valueArray["qtysold"] = $itemQtySold;

					// Store removed item, key = itemid and value = array of item values
					$removedItems[$itemId] = $valueArray;
				}

			}

			// Loop over items to be removed
			foreach($nodesToRemove as $node){
				$goodsNode->removeChild($node);
			}

			// Stores 2 items (1) $saveResult - true/false and (2) $removeItems Array
			$outputArray = array(); 

			// Check whether goods.xml needs to be saved/updated?
			// Case 1: Zero Items needs to be removed.
			if(empty($removedItems)){
				$outputArray['saveXMLResult'] = true;
			}
			// Case 2: At least 1 item needs to be removed.
			else {
				// Save DOM to File
				$dom->formatOutput = true;
				$saveResult = $dom->save($xmlPath);

				// Condition: Check whether the xml was saved?
				// Case 1: Failed to write to "goods.xml" file due file permission
				if($saveResult == false){
					$outputArray['saveXMLResult'] = false;
				} // Case 2: Updated the "goods.xml" file
				else {
					$outputArray['saveXMLResult'] = true;
				}

			}

			// Copied removed items into output array
			$outputArray['removedItems'] = $removedItems;


			return $outputArray; 

		}
	}



	/**
	 * saveRemovedItems(): save processed/removed items into "processedItems.xml" file
 	 * ENHANCEMENT FEATURE.
 	 *
	 * @inputs: $removedItems - items that were removed from the "goods.xml"
	 * @output: file called "processedItems.xml" containing removed items
	 * @returns: true - if the removed items were saved into "processedItems.xml" file.
	 *           Otherwise, return false.
	 **/
	function saveRemovedItems($removedItems){

		$dom = new DomDocument("1.0");  			// Create XML Dom Object
		$xmlPath = $GLOBALS["xmlProcessedPath"]; 	// "processedItems.xml" file location

		// Condition: Check whether the "processedItems.xml" exists?
		// Case 1: File Exists - Load File
		if(file_exists($xmlPath)){

			$dom->preserveWhiteSpace = FALSE; // ignore existing white spaces in XML
			$dom->load($xmlPath); // Load xml file containing items

			// Get reference to root element <goods>
			$goods = $dom->getElementsByTagName("processedgoods")->item(0);
		}
		// Case 2: File doesn't exist. Insert Root Element <goods> into XML DOM
		else{
			// Create <goods> the root element (e.g. as child of XML DOM)
			$goods = $dom->appendChild($dom->createElement("processedgoods"));
		}


		// Sort the $removedItems Array
		ksort($removedItems);

		// Loop through item in the $removeItems Array
		foreach($removedItems as $key=>$value){

			// Extract the array associated with the key
			$tempArray = $value;
			
			// Extract individual values
			$id = $key;
			$name = $tempArray['name'];
			$description = $tempArray['description'];
			$price = $tempArray['price'];
			$qtyavailable = $tempArray['qtyavailable'];
			$qtyonhold = $tempArray['qtyonhold'];
			$qtysold = $tempArray['qtysold'];

			//echo "$itemid $itemname $itemdesc $itemprice $itemqtyavail $itemqtyonhold $itemqtysold <br/>";  // TESTING PURPOSES

			// Element 1: Create <item> element and append to <goods> element
			$item = $goods->appendChild($dom->createElement("item"));

			// Element 2: Create <id> element and append to <item> element
			$itemid = $item->appendChild($dom->createElement("id"));
			$itemidval = $itemid->appendChild($dom->createTextNode($id)); // Append text node to <id>

			// Element 3: Create <name> element and append to <item> element
			$itemname = $item->appendChild($dom->createElement("name"));
			$itemnameval = $itemname->appendChild($dom->createTextNode($name)); // Append text node to <name>

			//Element 4: Create <description> element and append to <item> element
			$itemdesc = $item->appendChild($dom->createElement("description"));
			$itemdescval = $itemdesc->appendChild($dom->createTextNode($description)); // Append text node to <description>

			//Element 5: Create <price> element and append to <item> element
			$itemprice = $item->appendChild($dom->createElement("price"));
			$itempriceval = $itemprice->appendChild($dom->createTextNode($price)); // Append text node to <price>

			//Element 6: Create <qtyavailable> element and append to <item> element
			$itemqtyavail = $item->appendChild($dom->createElement("qtyavailable"));
			$itemqtyavailval = $itemqtyavail->appendChild($dom->createTextNode($qtyavailable));

			//Element 7: Create <qtyonhold> element and append to <item> element
			$itemqtyonhold = $item->appendChild($dom->createElement("qtyonhold"));
			$itemqtyonholdval = $itemqtyonhold->appendChild($dom->createTextNode($qtyonhold)); 

			//Element 8: Create <qtysold> element and append to <item> element
			$itemqtysold = $item->appendChild($dom->createElement("qtysold"));
			$itemqtysoldval = $itemqtysold->appendChild($dom->createTextNode($qtysold)); 
		}

		// Save DOM to File
		$dom->formatOutput = true;
		$xmlResult = $dom->save($xmlPath);

		//Condition: Check whether the processedItems.xml file was updated?
		// Case 1: File Permission Error - Unable to write to processedItems.xml file
		if($xmlResult == false){
			return false;
		} // Case 2: Update was successful.
		else {
			return true;
		}
	}




	/**************** MAIN SECTION OF THE CODE ***************************/

	// Case 1: Manager is logged in
	if(isset($_SESSION["mLogin"]) ){

		// OPTION 1: USER SUBMITS AN ACTION
		if(isset($_POST["action"])){

			$actionRequest = $_POST["action"]; // extract user's action

			// MENU OPTION 1: "getProcessForm"
			if($actionRequest == "getProcessForm"){
				$outputHTML = getProcessForm();
				echo $outputHTML;

			} // MENU OPTION 2: "process Items"
			else if( $actionRequest == "processItems"){
				
				$processedResult = processItems();

				// Condition: Check whether the XML file was updated successfully if required
				// Case 1: Unable to update goods.xml file
				if($processedResult["saveXMLResult"] == false){
					echo "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'>"
					. "Zero items were removed due to  File Permission Error when writing to goods.xml file</span></p>";
				} 
				else { // Case 2: Updated goods.xml file with success

					// Array of removed items from goods.xml file
					$removedItemsArray = $processedResult["removedItems"];

					// Copy the removed items into processedItems.xml
					if(!empty($removedItemsArray)){
						$saveRemoveResult = saveRemovedItems($removedItemsArray);

						// Condition: check whether the removed items were saved to "processedItems.xml"
						// Case: Unable to write to "processedItems.xml"
						if($saveRemoveResult == false && !empty($removedItemsArray)){
							echo "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'>"
							. "The removed items from the 'goods.xml' were not copied into 'processedItems.xml' file."
							. " Reason: File Permission - Unable to write to processedItems.xml file</span></p>";
						} 
					}
				

					// Case 1: Zero items were removed/processed from goods.xml
					if(empty($removedItemsArray)){
						echo "<strong>Status:</strong> Zero Items were processed or removed <br/>";
					} // Case 2: At least 1 removed/processed
					else {
						echo "<strong>Status:</strong> <span class='successMsg'>Successfully Processed Items</span><br/>";
					}


				}




				

			} 
		} 
		else{ // OPTION 2: LOGGED IN USER DOESN'T SUBMIT AN ACTION
			header("Location:processing.htm");
		}

	// CASE 2: Manage is not logged in 
	} else {
		echo "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'>Access Denied Operation. Manager Must be Logged In.</span></p>";
	}


?>