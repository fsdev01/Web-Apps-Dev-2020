<?php
 $xmlFile = "hotel.xml";
$HTML = "";
$count = 0;
$dom = DOMDocument::load($xmlFile); // Load XML file into DOM Object
// Alternative:  $dom = new DomDocument( ); $dom -> load($xmlFile);

// Use a PAIR OF ARRAYS to STORE MATCHED HOTELS (e.g. matched city and hotel grade)
// POSITIONAL ARRAYS. WHERE $priceArray[0] is linked to $dataArray[0] and so on..
// ARRAY 1: Stores the Prices
$priceArray = array();
// ARRAY 2:Used to store the HTML content associated with each price - type and city
$dataArray = array();


// Get list of hotel elements
$hotel = $dom->getElementsByTagName("hotel");

// Extract Properties of the hotel
foreach($hotel as $node)
{
	$citynode = $node->getElementsByTagName("City");
	$cityval = $citynode->item(0)->nodeValue; // Get the text value - firstChild textNode

	$typenode = $node->getElementsByTagName("Type");
	$typeval = $typenode->item(0)->nodeValue;

	$namenode = $node->getElementsByTagName("Name");
	$nameval = $namenode->item(0)->nodeValue;

	$pricenode = $node->getElementsByTagName("Price");
	$priceval = $pricenode->item(0)->nodeValue;


	// if hotel type and city match choice, add to the data to be sent back to client
	// Refer to JS file for the paramters
	if( ($typeval==$_GET["type"]) && ($cityval == $_GET["city"])  )
	{
		$temp = "<br><span>Hotel: " . $nameval . "</span><br><span>Price: "
				. $priceval . "</span><br>";

		// Add the price element to an array
		array_push($priceArray,$priceval);
		// Add the HTML element to an array
		array_push($dataArray,$temp);

		$count++; // Number of hotels found
	}

}



// if no hotels have been found, set the return message to a string which indicates this
if($count == 0)
{
	$HTML = "<br><span>No hotels available </span>";
} // There is at least 1 matched hotel
else { // count > 0
	// Sort the hotels according price
	// Reference: https://www.php.net/manual/en/function.array-multisort.php
	array_multisort($priceArray,$dataArray);
	// Combine new string
	foreach($dataArray as $element)
	{
		$HTML = $HTML . $element;
	}

}

echo $HTML;
?>

