<?php
	// Student ID: 102125413 
	// MAIN PURPOSE: Load Catalogue, Load Cart, Process Purchase, Cancel Purchase

	// Ensure that session_start() is only called once, when there are zero sessions.
	// Reference: https://www.php.net/manual/en/function.session-status.php
	if(session_status() === PHP_SESSION_NONE){
		session_start(); // start new session or resume existing one
	}

	/********************** DEFINED FUNCTIONS **************************/
	require("util.php"); // import common functions and variables


	/* loadCatalogue() - loads the catalogue/items from the "goods.xml" file.
	 * Task 4.2 - Display items where available quantity is greater than zero.
	 * Task 4.3 - Each item has a "add one to cart" button (using XSLT)
	 *
	 * Process on Server Side as suggested in Assignment Hints 4.2
	 * Reference: Week 8 Lab Task A and B
	 */
	function loadCatalogue(){

		$xmlPath = $GLOBALS['xmlGoodsPath'];
		$xslPath = $GLOBALS['xslCataloguePath'];
		
		//Check that the XML and XSL files exist
		// Case 1: xml file doesn't exist
		if(!file_exists($xmlPath)){
			return "<p class='errMsgBox'><strong>Warning:</strong><span class='errMsg'> goods.xml file doesn't exist. Please add items to catalogue.</span></p>\r\n";
		} // Case 2: xsl file does exist
		else if(!file_exists($xslPath)){
			return "<p class='errMsgBox'><strong>Error:</strong><span class='errMsg'> catalogue.xsl file does not exist.</span></p>\r\n";
		} // Case 3: Both xml file and xsl file exists
		else {
			// Create XML DOM object and Load the XML file
			$xmlDoc = new DomDocument; 
			$xmlDoc->load($xmlPath); 

			// Load the XSL file
			$xslDoc = new DomDocument; 
			$xslDoc->load($xslPath);

			// Create XSLT Processor Object
			$proc = new XSLTProcessor; 
			// Import the stylesheet required for transformation
			$proc->importStyleSheet($xslDoc);

			// Transform the XML to HTML using the XSL file
			$output =  $proc->transformToXML($xmlDoc);
			return $output;
		}
	}

	/*
	 * getItemDetails(): get details of an item using the goods.xml
	 *
	 * @input: $itemid - the item id
	 * @output: array of values related to item id
	*/
	function getItemDetails($itemid){
		$xmlPath = $GLOBALS['xmlGoodsPath']; 		// XML file location
		$itemArray = array();		                // array store the details of <item>

		// Condition 1: Check whether the file exists
		// CASE 1: File does not exist
		if(!file_exists($xmlPath)){
			echo "<p class='errMsgBox'><strong>Error:</strong><span class='errMsg'> goods.xml file not found</span><br/><p>\r\n";
		} 
		else { // Case 2: "goods.xml" file exists

			$dom = new DomDocument("1.0");      // Create DOM Object
			$dom->load($xmlPath);               // Load xml file into $dom

			// Get List of <item>
			$items = $dom->getElementsByTagName("item");

			// Traverse over the list of <item>
			foreach($items as $node){

				// Get reference to itemid node
				$curIdNode = $node->getElementsByTagName("id");
				// Extract value from text node contained in <itemid>
				$curId = $curIdNode->item(0)->nodeValue; 

				// Check whether current item matches $itemid (target)
				// Case 1: Item has been found in the XML file
				if($curId == $itemid){
					//Extract the details
					$itemArray["id"] = $itemid;
					$itemArray["name"] = $node->getElementsByTagName("name")->item(0)->nodeValue;
					$itemArray["description"] = $node->getElementsByTagName("description")->item(0)->nodeValue;
					$itemArray["price"] = $node->getElementsByTagName("price")->item(0)->nodeValue;
					$itemArray["qtyavailable"] = $node->getElementsByTagName("qtyavailable")->item(0)->nodeValue;
					$itemArray["qtyonhold"] = $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue;
					$itemArray["qtysold"] = $node->getElementsByTagName("qtysold")->item(0)->nodeValue;
				}
			}
		}
		// return array
		return $itemArray;

	}


	/*
	 * loadCart(): Generates HTML code of the shopping cart stored in Session Variable
	 * Task 4.4 - Display Shopping Cart
	 * Task 4.5 - Display "Remove from Cart" Button
	 * Task 4.6 - Display "Confirm Purchase" or "Cancel Purchase" Buttons
	 */
	function loadCart(){
		$outputHTML = "<h3>Shopping Cart </h3>\r\n"
					."<table id='cartTable'>\r\n"
					."<thead><tr>\r\n"
					."<th>Item Number</th>\r\n"
					."<th>Item Name </th>\r\n"
					."<th>Unit Price</th>\r\n"
					."<th>Quantity</th>\r\n"
					."<th>Subtotal</th>\r\n"
					."<th>Remove</th>\r\n"
					."</tr></thead>\r\n";
		// Condition 1: Check whether the cart is empty
		// CASE 1: Cart is empty
		if(!isset($_SESSION["cart"]) || empty($_SESSION["cart"]) ){
			$outputHTML .= "<tbody><tr id='emptyCart'><td colspan='6' style='text-align:center;'><em>Your Cart is Empty</em></td></tr></tbody>\r\n";
		} 
		else { // CASE 2: Cart is not empty

			$cart = $_SESSION["cart"]; // Extract the cart
			ksort($cart); // Sort the cart according itemid number (where key = itemid)
			$outputHTML .= "<tbody>\r\n"; // HTML Starting Element
			$totalDollar = 0;  // Total Dollar

			// Loop through each item in cart
			foreach($cart as $itemid=>$qty){

				// Get more details about itemid
				$itemDetails = getItemDetails($itemid);
				
				// Calculate subtotal and total dollar
				$subtotal = $qty * $itemDetails['price'];
				$totalDollar = $totalDollar + $subtotal;

				// Generate formatted price and subtotal
				$formattedPrice = number_format($itemDetails['price'],2);
				$formattedSubtotal = number_format($subtotal,2);

				$outputHTML .= "<tr class='item'>\r\n"
								. "<td> $itemid </td>\r\n"
								. "<td> {$itemDetails['name']} </td>\r\n"
								. "<td> $formattedPrice </td>\r\n"
								. "<td> $qty </td>\r\n"
								." <td> $formattedSubtotal </td>\r\n"
								. "<td class='removeBtn'> <button onclick='addRemoveItem(\"remove\",$itemid)'>"
								. "Remove from cart</button></td>\r\n"
								."</tr>\r\n";
			}

			// Generated fromatted total dollar price
			$formattedTotal = number_format($totalDollar,2);
			$outputHTML .= "<tr id='totalBox'><td id='totalTitle' colspan='5' style='text-align:right;'>Total:&nbsp;&nbsp;</td><td id='totalValue' style='text-align:center'>{$formattedTotal}</td></tr>\r\n";

			// Task 4.6 - Add "Confirm Purchase" and "Cancel Button" Buttons
			$outputHTML .= "<tr class='cartMenu'><td colspan='3'><button onclick=\"purchase('confirm')\">Confirm Purchase</button></td>"
						   ."<td colspan='3'><button onclick=\"purchase('cancel')\">Cancel Purchase</button></td></tr>";
			$outputHTML .= "</tbody>\r\n";
		}

		$outputHTML .= "</table>\r\n";
		return $outputHTML;
	}


	/**
	 * updateItemCatalogue(): updates the goods.xml file when user adds an item to cart
	 *  (1) Checks whether qty available is >0 (2) Subtract 1 from qty available
	 *  (3) Increase 1 to quantity on hold
	 *  
	 * @inputs: $itemd - id of an item
	 * @returns: $errMsg - string containing failed update error messages
	 * Task 4.3 - Update goods.xml document when user adds an item to cart
	 */
	function updateItemCatalogue($itemid){
		$errMsg = ""; // accumulate error messages
		$xmlPath = $GLOBALS["xmlGoodsPath"]; // goods.xml file path

		//Condition: Check whether the file exists
		// Case 1: "goods.xml" file exists
		if(!file_exists($xmlPath)){
			$errMsg .= "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'>goods.xml file not found</span><br/></p>\r\n";
		} else {
			
			$dom = new DomDocument("1.0");  // Create DOM Object
			$dom->preserveWhiteSpace = FALSE; // ignore existing white spaces in XML
			$dom->load($xmlPath); // Load xml file containing items

			// Get List of <item>
			$itemList = $dom->getElementsByTagName("item");

			// Loop over each <item> in the list
			foreach($itemList as $node){

				// Get reference to itemid node
				$curIdNode = $node->getElementsByTagName("id");
				// Extract value from text node contained in <itemid>
				$curId = $curIdNode->item(0)->nodeValue; 

				// Check whether current item matches $itemid (target)
				if($curId == $itemid){
					// Extract the <qtyavailable>
					$curItemQtyAvailableNode = $node->getElementsByTagName("qtyavailable");
					$curItemQtyAvailable = $curItemQtyAvailableNode->item(0)->nodeValue;

					// Extract the <qttonhold>
					$curItemQtyHoldNode = $node->getElementsByTagName("qtyonhold");
					$curItemQtyHold = $curItemQtyHoldNode->item(0)->nodeValue;

					 // OLD - TESTING PURPOSES
					//echo "BEFORE: Item Number: $curId  Qty available: $curItemQtyAvailable " ."Qty on Hold: $curItemQtyHold <br/>\r\n";

					// Condition 1: Zero Stock Available for sale (e.g. qtyavailable is less or equal to zero)
					if($curItemQtyAvailable <= 0){
						$errMsg .= "<p class='errMsgBox'><strong>Message:</strong><span class='errMsg'> Sorry this <strong>item no. $itemid</strong> is not available for sale</span></p><br/>\r\n";
					} // Condition 2: Qty Available > 0
					else {

						// Step A: Subtract 1 from qty available
						$curItemQtyAvailableNode->item(0)->nodeValue =  $curItemQtyAvailableNode->item(0)->nodeValue - 1;
						// Step B: Add 1 to quantity on hold
						$curItemQtyHoldNode->item(0)->nodeValue = $curItemQtyHoldNode->item(0)->nodeValue + 1;

						// AFTER - TESTING PURPOSES UPDATED QTY
						//echo "AFTER: Item Number: $curId Qty available: {$curItemQtyAvailableNode->item(0)->nodeValue} "
						//."Qty on Hold: {$curItemQtyHoldNode->item(0)->nodeValue} <br/>\r\n";

						// Save DOM to File
						$dom->formatOutput = true;
						$saveResult = $dom->save($xmlPath);

						// Condition: Check whether the xml was updated?
						// Case 1: Unable to write to "goods.xml" file due file permission error
						if($saveResult == false){
							$errMsg .= "<p class='errMsgBox'><strong>Error: </strong><span class='errMsg'> "
							."Unable to add <strong>item no. $itemid </strong> to shopping cart."
							."<br/><strong>Reason:</strong> Unable to write to goods.xml file due file permission error</span></p><br/>\r\n";
						}
					}

					break; // Break Loop as found the item in the goods.xml
				}
			}
		}
		return $errMsg;
	}

	/**
	 * updateItemCatalogue2(): updates the goods.xml file when the user removes an item from cart
	 *  (1)  Decrease quantity on hold  (2) increase the quantity available 
	 * @inputs: $itemid - item item 
	            $adjustQty - qty removed from qtyonhand and added to qtyavailable
	 * @return: $errMsg - string containing failed update error messages
	 * Task 4.5 - Update XML file when user removes an item from the cart
	 */
	function updateItemCatalogue2($itemid,$adjustQty){
		$errMsg = ""; // accumulate error messages
		$xmlPath = $GLOBALS["xmlGoodsPath"]; // goods.xml file path

		//Condition: Check whether the file exists
		if(!file_exists($xmlPath)){
			$errMsg .= "<strong>Error:</strong><span class='errMsg'> goods.xml file not found</span><br/>\r\n";
		} else {

			// Create DOM Object
			$dom = new DomDocument("1.0"); 
			// ignore existing white spaces in XML
			$dom->preserveWhiteSpace = FALSE; 
			// Load xml file containing items
			$dom->load($xmlPath);
			// Get List of <item>
			$itemList = $dom->getElementsByTagName("item");

			// Loop over <item> list
			foreach($itemList as $node){

				// Get <id> value of current <item>
				$currentId = $node->getElementsByTagName("id")->item(0)->nodeValue;

				// Item is found -> update qty
				if($currentId == $itemid){
					
					// Requirement: Decrease the quantity on hold by qty shown in the cart
					if($node->getElementsByTagName("qtyonhold")->item(0)->nodeValue == 0){
						$errMsg .= "<strong>Error:</strong> <span class='errMsg'>Unable to remove item no. $itemid because Qty on Hold is already Zero.</span><br/>\r\n";
					}else{
						// Requirement: Decrease the quantity on hold by qty shown in the cart
						$node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = 
						$node->getElementsByTagName("qtyonhold")->item(0)->nodeValue - $adjustQty;

						// REquirement: Increase the quantity available by qty shown in the cart
						$node->getElementsByTagName("qtyavailable")->item(0)->nodeValue = 
						$node->getElementsByTagName("qtyavailable")->item(0)->nodeValue + $adjustQty;

						// Save DOM to File if there are no errors
						$dom->formatOutput = true;
						$saveResult = $dom->save($xmlPath);

						// Condition 1: Check whether the file was saved?
						// Case 1: goods.xml was not updated because of file permission issues
						if($saveResult == false){
							$errMsg .= "<strong>Error:</strong> <span class='errMsg'> Unable to remove item no. $itemid from shopping cart<br/>"
									. "<strong>Reason:</strong> Fail to write to goods.xml file due file permission issue</span><br/>\r\n";
						}


					}

					break; // Exit the loop
				}

			}
		}
		return $errMsg;
	}



	/*
	 * updateItemCatalogue3(): updates the goods.xml file when user
	 * (1) confirmPurchase and (2) cancelPurchase
	 * @inputs: $action is either "confirm" or "cancel"
	 * @returns: an array (1) SUCCESS/FAILURE  and (2) SUCCESS/FAILURE MESSAGE
	 * Task 4.6 - update XML document when user presses "confirm purchase" or "cancel purchase"
	 */
	function updateItemCatalogue3($action){
		$successMsg = ""; // record success messages
		$errMsg = ""; // record error messages
		$totalPrice = calculateTotalDollar(); // calculates cart total
		$formattedTotalPrice = number_format($totalPrice,2); 
		$xmlPath = $GLOBALS["xmlGoodsPath"]; // goods.xml file path
		$nodeArray = array(); // store node references in goods.xml which correspond to items in the cart

		//Condition: Check whether the file exists
		// Case 1: goods.xml file doesn't exist
		if(!file_exists($xmlPath)){
			$errMsg .= "<strong>Error:</strong> <span class='errMsg'> goods.xml file not found </span><br/>\r\n";
		} // Case 2: goods.xml file exists and valid actions
		else if ($action == "confirm" || $action == "cancel" ) {

			$dom = new DomDocument("1.0");  // Create DOM Object
			$dom->preserveWhiteSpace = FALSE; // ignore existing white spaces in XML
			$dom->load($xmlPath); 			// Load xml file containing items

			// Get List of <item>
			$itemList = $dom->getElementsByTagName("item");

			// Get cart items
			$cart = $_SESSION["cart"];

			// Sort the cart according itemid number (where key = itemid)
			ksort($cart);

			// Loop over the items in the cart
			foreach($cart as $itemid=>$qty){

				// Find the item in the xml file
				foreach($itemList as $node){

					// Get <id> value of current <item>
					$currentId = $node->getElementsByTagName("id")->item(0)->nodeValue;

					// Found the item in the XML file
					if($itemid == $currentId){
						// ACTION 1: "confirmPurchase"
						if($action=="confirm"){

							// Case 1: Qty on Hold is Zero - it cannot become negative
							if($node->getElementsByTagName("qtyonhold")->item(0)->nodeValue == 0){
								$errMsg .= "<strong>Error:</strong> <span class='errMsg'>Cannot Decrement Qty on Hold for item no. $itemid because it is zero</span><br/>\r\n";
							} else {
								// Decrease Quanity on Hold by qty in cart
								$node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = 
								$node->getElementsByTagName("qtyonhold")->item(0)->nodeValue - $qty;

								// Increase the qty sold by the qty in cart
								$node->getElementsByTagName("qtysold")->item(0)->nodeValue = 
								$node->getElementsByTagName("qtysold")->item(0)->nodeValue + $qty;
							}

						// ACTION 2: "cancelPurchase"
						} else if ( $action == "cancel"){
							// Case 1: Qty on Hold is Zero - it cannot become negative
							if($node->getElementsByTagName("qtyonhold")->item(0)->nodeValue == 0){
								$errMsg .= "<strong>Error:</strong><span class='errMsg'> Cannot Decrement Qty on Hold for item no. $itemid because it is zero </span><br/>\r\n";
							} else {
								// Decrease Quanity on Hold by qty in cart
								$node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = 
								$node->getElementsByTagName("qtyonhold")->item(0)->nodeValue - $qty;

								// Increase the qty available by the qty in cart
								$node->getElementsByTagName("qtyavailable")->item(0)->nodeValue = 
								$node->getElementsByTagName("qtyavailable")->item(0)->nodeValue + $qty;
							}
						}

					}
				}
			}


			// No Errors discovered in the loop
			// (e.g. qty on hold is already zero and can't decrease) => Save XML FIle
			if($errMsg == ""){
				// Save DOM to File
				$dom->formatOutput = true;
				$saveResult = $dom->save($xmlPath);

				// Condition: Check whether the xml file was updated ?
				// Case: Unable to write to "goods.xml" to file permission error
				if($saveResult == false){
					$errMsg .= "<strong>Error:</strong> <span class='errMsg'> Unable to <strong> $action purchase</strong> <br/>"
							. "<strong>Reason:</strong> Unable to write to goods.xml file due file permission issue</span><br/>\r\n";
				}

			}

		}


		$output = array(); // create empty array to return results

		// Check for error Messages
		// Case 1: There is at least 1 error message
		if($errMsg != ""){
			//$errMsg = "<p class='errMsgBox'> $errMsg </p>\r\n";  // format error message
			$output["result"] = "FAILURE"; // store failure status
			$output["msg"] = $errMsg; // store error message

		} // Case 2: There are zero error messages. Create Confirmation Messages.
		else {
			// Create confirmation message
			if($action == "confirm"){
				$successMsg = "<strong>Message:</strong> <span class='successMsg'>Your purchased has been confirmed and total amount due to pay is  &dollar;{$formattedTotalPrice} </span><br/>\r\n";
			} else if ($action == "cancel"){
				$successMsg = "<strong>Message:</strong> <span class='successMsg'>Your purchase request has been cancelled, you are welcome to shop next time </span> <br/>\r\n";
			}
			//$successMsg = "<p class='successMsgBox'> $successMsg </p>\r\n"; // format success message

			// store outcome in array
			$output["result"] = "SUCCESS"; // store success status
			$output["msg"] = $successMsg; // store success message

		}

		return $output;
	}


	/**
	 * calculateTotalDollar(): calculates the total dollar value of the cart
	 * Helper function for Task 4.6 - Show amount due for payment
	 */
	function calculateTotalDollar(){
		$totalDollar = 0;
		// CASE 1: Cart is empty
		if(!isset($_SESSION["cart"]) || empty($_SESSION["cart"]) ){
			$totalDollar = 0;
		// CASE 2: Cart is not empty
		} else {
			$cart = $_SESSION["cart"]; 		// Extract Cart

			// Loop through Cart
			foreach($cart as $itemid=>$qty){
				$itemDetails = getItemDetails($itemid); // Get further info about the $itemid
				$price = $itemDetails["price"]; // Get price of the item
				$subtotal = $price * $qty; // Calculate subtotal
				$totalDollar = $totalDollar + $subtotal; // Aggregate Total
			}
		}
		return $totalDollar;
	}


	/*
	 * recordPurchase(): Saves the customer cart purchase into "purchases.xml" XML file
	 * ENHANCEMENT FEATURE - Record customer purchases in "purchases.xml" file
	 */
	function recordPurchase(){
		date_default_timezone_set("Australia/Melbourne"); // set timezone

		// Condition 1: Check whether the cart is empty
		// CASE 1: Cart is empty
		if(!isset($_SESSION["cart"]) || empty($_SESSION["cart"]) ){
			echo "<p class='errMsgBox'><strong>Error:</strong> <span class='errMsg'> Cart is Empty. Unable to Save Purchases. </span> </p>";
		} 
		else { // CASE 2: Cart is not empty

			// Extract the Cart where key is "itemid" and value is "qty ordered by customer"
			$cart = $_SESSION["cart"];

			$custId = $_SESSION["cLogin"]; // Extract Customer ID
			$xmlPath = $GLOBALS['xmlPurchasePath']; // XML file location
			$dom = new DomDocument("1.0");  // Create XML Dom Object
			$dom->preserveWhiteSpace = FALSE;  // ignore existing white spaces in XML
			$count = 0; // number of purchase transactions in <purchases> root element

			// Check whether the purchases.xml file exists with <purchases> root element
			// Case 1: purchases.xml does exist
			if(file_exists($xmlPath)){

				// Load xml file containing items
				$loadSuccess = $dom->load($xmlPath);

				// Get reference to root element <purchases>
				$purchases = $dom->getElementsByTagName("purchases")->item(0);

				// count number of existing <purchase> within the <purchases> root element
				$count = $purchases->getElementsByTagName("purchase")->length;

			} // Case 2: File doesn't exist. Insert Root Element <purchases> into XML DOM
			else {
				// Create new <purchases> root element
				$purchases = $dom->appendChild($dom->createElement("purchases"));
			}

			ksort($cart); // Sort the $cart storing the customer's purchases using $itemid as key

			// Create <purchase> element within the <purchases> element
			$purchase = $purchases->appendChild($dom->createElement("purchase"));
			
			// Create <transactionid> within <purchase>
			$transactionId = $purchase->appendChild($dom->createElement("transactionid"));
			$transactionIdVal = $transactionId->appendChild($dom->createTextNode($count +1));

			// Create <timestamp> within <purchase>
			$temp = date("d/m/Y H:i:s"); 
			$timestamp = $purchase->appendChild($dom->createElement("timestamp"));
			$timestampVal = $timestamp->appendChild($dom->createTextNode($temp));

			// Create <custid> within <purchase>
			$customerId = $purchase->appendChild($dom->createElement("customerid"));
			$customerIdVal = $customerId->appendChild($dom->createTextNode("$custId"));

			$total = 0; // total dollar value of the purchase (cart)

			// Traverse items in the $cart
			foreach($cart as $id => $qty){

				// Get Details about the $itemid
				$itemDetails = getItemDetails($id);
				// Extract releveant details about the item
				$itemName = $itemDetails['name'];
				$itemPrice = $itemDetails['price'];

				// Calculate prices
				$subtotal = $qty * $itemPrice;
				$total = $total + $subtotal;

				// Create <item> element in <purchase>
				$item = $purchase->appendChild($dom->createElement("item"));

				// Create <itemid> within <item>
				$itemid = $item->appendChild($dom->createElement("itemid"));
				$itemidval = $itemid->appendChild($dom->createTextNode($id));

				// Create <name> within <item>
				$itemname = $item->appendChild($dom->createElement("itemname"));
				$itemnameval = $itemname->appendChild($dom->createTextNode($itemName));

				// Create <unitprice> within <item>
				$itemprice = $item->appendChild($dom->createElement("unitprice"));
				$itempriceval = $itemprice->appendChild($dom->createTextNode($itemPrice));

				// Create <qtyordered> within <item>
				$itemqty = $item->appendChild($dom->createElement("qtyordered"));
				$itemqtyval = $itemqty->appendChild($dom->createTextNode($qty));
			}

			// Create <total> price within <purchase>
			$totalprice = $purchase->appendChild($dom->createElement("totalprice"));
			$totalpriceval = $totalprice->appendChild($dom->createTextNode($total));

			// Create <paidStatus> within <purchase>
			$paidstatus = $purchase->appendChild($dom->createElement("paidstatus"));
			$paidstatusval = $paidstatus->appendChild($dom->createTextNode("UNPAID"));

			// Save DOM to File
			$dom->formatOutput = true;
			$saveResult = $dom->save($xmlPath);


			// Condition: Check whether the file was saved? purchases.xml
			// Case: Unable to write to "purchases.xml" file due to file permission
			if($saveResult == false ){
				$errMsg = "<strong>Warning:</strong> <span class='errMsg'> Purchase was not saved to purchases.xml file  <br/>"
							. "<strong>Reason:</strong> Unable to write to purchases.xml file due file permission issue</span><br/>\r\n";
				echo $errMsg;

			}



		}
	}


	/**
	 * confirmPurchase(): will checkout the goods - controller function
	 * (1) Update XML Document "goods.xml" (2) Clear shopping Cart (3) Display Confirmation Message
	 * @returns: $resultMsg - error or success message
	 * Task 4.6 - Confirm Purchase Function
	 */
	function confirmPurchase(){
		$resultMsg = ""; // String to accumulate result message

		// Condition 1: Check whether the cart is empty
		// CASE 1: Cart is empty
		if(!isset($_SESSION["cart"]) || empty($_SESSION["cart"]) ){
			$resultMsg .= "<p class='errMsgBox'><strong>Error:</strong><span class='errMsg'> Cart is Empty. Cannot Confirm Purchase.</span></p>";
		} // CASE 2: Cart is not empty
		else {
			// STEP 1: Update the XML file
			$updateXMLResultArray = updateItemCatalogue3("confirm");

			// XML UPDATE was either a "SUCCESS" or "FAILURE" 
			$updateXMLResult = $updateXMLResultArray["result"]; 

			// DETAILED MESSAGE ABOUT THE XML UPDATE
			$updateXMLMsg = $updateXMLResultArray["msg"];

			// STEP 2: Clear the cart when there are no update errors in XML file
			// Case 1: goods.xml was updated with success
			if($updateXMLResult == "SUCCESS"){
				$_SESSION['cart'] = array(); // removed items from cart
				$resultMsg .= "<p class='succesMsgBox'> $updateXMLMsg </p>\r\n";
			} // Case 2: goods.xml was not updated (failure)
			else {
				$resultMsg .= "<p class='errMsgBox'><strong>Error: </strong> <span class='errMsg'> Unable to Confirm Purchase</span><br/> $updateXMLMsg </p>\r\n";
			}


		}
		return $resultMsg;
	}


	/**
	 * cancelPurchase(): will remove items from cart - controller function
	 *  (1) Update XML Document "goods.xml"   (2) Clear shopping Cart (2) Display Confirmation Message
	 *  @returns: $resultMsg - error or success message
	 *  Task 4.6 - Cancel Purchase Function
	 */
	function cancelPurchase(){
		$resultMsg = ""; // String to accumulate result message

		// Condition 1: Check whether the cart is empty
		// CASE 1: Cart is empty
		if(!isset($_SESSION["cart"]) || empty($_SESSION["cart"]) ){
			$resultMsg .= "<p class='errMsgBox'><strong>Warning:</strong><span class='errMsg'> Cart is already Empty.</span></p>";
		} // CASE 2: Cart is not empty
		else {
			// STEP 1: Update the XML file
			$updateXMLResultArray = updateItemCatalogue3("cancel");

			// XML UPDATE was either a "SUCCESS" or "FAILURE" 
			$updateXMLResult = $updateXMLResultArray["result"]; 

			// Store SUCCESS/FAILURE Message related to XML UPDATE
			$updateXMLMsg = $updateXMLResultArray["msg"];

			// STEP 2: Clear the cart when there are no update errors in XML file
			// Case 1: goods.xml was updated with success
			if($updateXMLResult == "SUCCESS"){
				$_SESSION['cart'] = array();
				$resultMsg .= "<p class='succesMsgBox'> $updateXMLMsg </p>\r\n";
			} // Case 2: goods.xml was not updated (failure)
			else {
				$resultMsg .= "<p class='errMsgBox'><strong>Error: </strong> <span class='errMsg'> Unable to Cancel Purchase</span><br/> $updateXMLMsg </p>\r\n";
			}


		}

		return $resultMsg;
	}


	/**
	 * addItemCart(): adds an item to the cart
	 * @inputs: id of an item
	 * @outputs: $result - accumulates the error / success message
	 * Task 4.3 - Update the goods.xml file when item is added to cart
	 * Task 4.4 - Update existing item in cart Qty By 1 / Add new item to cart
	 * Reference: Week 7 Lecture/Lab - Shopping Cart Example
	 */
	function addItemCart($itemid){
		$resultMsg = ""; // accumulate success/error Messages

		// Step 1: Check whether the cart exists in $_SESSION supergloabl variable?
		// Case 1: Cart doesn't exist, so create it
		if(!isset($_SESSION["cart"])){
			$_SESSION["cart"] = array();
		} 
		$cart = $_SESSION["cart"]; // Extract session cart into local variable

		// Step 2: Update "goods.xml" file
		$resultMsg .= updateItemCatalogue($itemid);

		// Step 3: Check whether updating "goods.xml" was successful
		// Case A: Update was successful
		if($resultMsg == "") {
			// CASE A: The cart contains 1 or more items in it already
			if(!empty($cart)){

				// Item already exists in the cart
				if(isset($cart[$itemid]) && $cart[$itemid]){
					$cart[$itemid] = $cart[$itemid] + 1; // Increment Qty of Item

				} //Item is not in the cart yet (first time)
				else{
					$cart[$itemid] = 1; // Set qty to 1
				}	

			} // Case B: The cart contains zero items (e.g. empty array/string)
			else{
				$cart[$itemid] = 1; // Set Qty to 1, Key: $itemid, Value: Qty of Item
			}
			$resultMsg = "SUCCESS"; // Success Message send to CLIENT- (no errors)
		}

		$_SESSION["cart"] = $cart; // UPDATE SESSION CART with new CART
		return $resultMsg; // return result of the "addItem" operation
	}


	/**
	 * removeItemCart(): removes an item from the cart
	 * @inputs: id of an item
	 * @outputs: $result - accumulates the error / success message
	 * Task 4.5 - Update XML Document and empty the cart
	 */
	function removeItemCart($itemid){
		$resultMsg = ""; // accumulate success/error Messages

		// Step 1: Check whether cart is empty
		if(!isset($_SESSION['cart']) || empty($_SESSION['cart']) ) {
			$resultMsg .= "<strong>Error:</strong><span class='errMsg'> Unable to remove item number: $itemid from cart. Empty Shopping Cart.</span><br/>";
		}

		// Step 2: Extract Cart from SESSION VARIABLE
		$cart = $_SESSION["cart"];

		// Step 3: Check whether the item in the cart
		// Case A: Item is not in the cart
		if(!isset($cart[$itemid])){
			$resultMsg .= "<strong>Error:</strong><span class='errMsg'> Unable to remove item no. $itemid from cart. It does not exist.</span><br/>";
		} 
		else { //Case B: Item is in the Cart
			
			$qtyToAdjust = $cart[$itemid]; // item qty in the cart

			// Step 3 :  Update the "goods.xml" file
			$resultMsg .= updateItemCatalogue2($itemid,$qtyToAdjust);

			// STEP 4: Remove item from Cart
			// CASE 4A: REMOVE ITEM FROM CART if "goods.xml" was updated succesfully (e.g. no errors)
			if($resultMsg == ""){
				unset($cart[$itemid]); // remove item from cart
				$_SESSION["cart"] = $cart; // update cart with 1 less item
				$resultMsg = "SUCCESS";

			} // CASE 4B: Failed to update "goods.xml" - display error message
			else {
				$resultMsg .= "<strong>Error:</strong> <span class='errMsg'>Failed to remove item from cart</span>\r\n";
				$resultMsg = "<p class='errMsgBox'>" . $resultMsg . "</p>\r\n";
			}

		}

		return $resultMsg;
	}


	/**************** MAIN SECTION OF THE CODE ***************************/

	// Case 1: Customer is Logged In
	if(isset($_SESSION["cLogin"])){
		// MENU OPTIONS - ACTIONS 
		if(isset($_POST["action"])){
			$actionRequest = $_POST["action"]; // extract menu option

			// MENU OPTION 1: getCatalogue
			if($actionRequest == "getCatalogue"){
				echo loadCatalogue();
			} // MENU OPTION 2: getCart	
			else if($actionRequest == "getCart"){
				echo loadCart();
			}
			// MENU OPTION 3: add an item
			else if($actionRequest == 'add'){
				$itemid = $_POST['itemid']; // Extract the id of the item
				echo addItemCart($itemid); // Add item to the cart and update xml

			} // MENU OPTION 4: remove an item
			else if($actionRequest == "remove"){
				$itemid = $_POST['itemid']; // Extract the id of the item
				echo removeItemCart($itemid); // Remove item from cart and update xml

			} // MENU OPTION 5: confirm purchase
			else if($actionRequest == "confirmPurchase"){
				recordPurchase(); // record purchases to purchases.xml file
				echo confirmPurchase(); // update goods.xml and empty cart

			} // MENU OPTION 6: Cancel Purchase
			else if($actionRequest ="cancelPurchase"){
				echo cancelPurchase(); // update goods.xml and empty cart
			}
		} 
	} // Case 2: Customer is not logged in - Access Denied
	else {
		echo "<br/><strong>Error:</strong><span='errMsg'> Access Denied. Customer Must be Logged In.</span><br/>\r\n";
	}
?>