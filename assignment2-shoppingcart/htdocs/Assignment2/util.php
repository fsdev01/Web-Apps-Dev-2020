<?php
/* Student ID: 102125413
 * util.php contains common functions
 *
 */

/* sanitiseInput(): santises user input
 * (1) removes leading or trailing spaces
 * (2) remove slashes
 * (3) handle special characters as html entities
 *
 *  @inputs: $input is a user input string
 *  @output: transformed user input string that has been sanitised
 */  
function sanitiseInput($input){
	$input = trim($input);
	$input = stripslashes($input); 
	$input = htmlspecialchars($input); 
	return $input;
}

// Declare Global Variables

// Global Variable - Path of "customers.xml" file
$xmlCustomerPath = "../../data/customer.xml"; 

// Global Variable - Path of "goods.xml" file
$xmlGoodsPath = "../../data/goods.xml";

// Global Variable - Path of "managers.txt" file
$txtManagerPath = "../../data/manager.txt";

// Global Variable - Path of "purchases.xml" file
// Record customer purchases
$xmlPurchasePath = "otherdata/purchases.xml";

// Gloabl Variable - Path of "processItems.xml" file
// Record items processed by the manager
$xmlProcessedPath = "otherdata/processedItems.xml";


// Global Variable - Path of "catalogue.xsl" file
$xslCataloguePath = "xsl/catalogue.xsl";

// Gloabl Variable - Path of "processForm.xsl" file
$xslProcessFormPath = "xsl/processForm.xsl";


?>